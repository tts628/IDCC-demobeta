<?php
declare(strict_types=1);
namespace DataExchange;

class SyncError extends \RuntimeException {}
final class CommitUncertain extends SyncError {}

final class Schema
{
    public static function quote(string $name): string
    {
        if ($name === '' || strlen($name) > 63 || str_contains($name, "\0")) throw new SyncError('欄位或資料表名稱為空、過長或含無效字元。');
        return '"' . str_replace('"', '""', $name) . '"';
    }
    public static function columns(array $metadata): array
    {
        $columns = []; $seen = [];
        foreach ($metadata as $field) {
            $name = (string) ($field['Name'] ?? ''); self::quote($name);
            $key = strtolower($name);
            if (isset($seen[$key])) throw new SyncError('來源查詢有重複欄位名稱：' . $name);
            $seen[$key] = true;
            $type = (int) $field['Type']; $scale = (int) ($field['Scale'] ?? 0);
            [$pg, $kind] = match ($type) {
                -5 => ['bigint','number'], 4 => ['integer','number'], 5, -6 => ['smallint','number'],
                -7 => ['boolean','boolean'], 6, 8 => ['double precision','number'], 7 => ['real','number'],
                -2, -3, -4 => ['bytea','binary'], -11 => ['uuid','text'],
                91 => ['date','date'],
                93 => $scale > 6 ? ['text','text'] : ['timestamp(6)','timestamp'],
                92, -154 => $scale > 6 ? ['text','text'] : ['time(6)','time'],
                -155 => $scale > 6 ? ['text','text'] : ['timestamptz(6)','timestamptz'],
                1, 12, -1, -8, -9, -10, -152 => ['text','text'],
                2, 3 => ['numeric','number'],
                default => throw new SyncError('不支援的來源欄位型別：' . $name . '（SQL type ' . $type . '），已停止，不會猜測轉換。'),
            };
            if (in_array($type, [2,3], true)) {
                $precision = (int) ($field['Precision'] ?? 0);
                if ($precision < 1 || $precision > 38 || $scale < 0 || $scale > $precision) throw new SyncError('來源數值精度無效：' . $name);
                $pg = "numeric($precision,$scale)";
            }
            $columns[] = ['name'=>$name,'pg_type'=>$pg,'kind'=>$kind,'source_type'=>$type,'scale'=>$scale];
        }
        if (!$columns) throw new SyncError('來源查詢沒有欄位。');
        return $columns;
    }
    public static function value($value, array $column): ?string
    {
        if ($value === null) return null;
        if (is_resource($value)) { $value = stream_get_contents($value); if ($value === false) throw new SyncError('來源大型欄位讀取失敗：' . $column['name']); }
        if ($column['kind'] === 'binary') return '\\x' . bin2hex((string) $value);
        if ($column['kind'] === 'boolean') return ((string) $value === '0' || $value === false) ? 'f' : 't';
        if ($value instanceof \DateTimeInterface) $value = $value->format(match($column['kind']) {'date'=>'Y-m-d','time'=>'H:i:s.u','timestamptz'=>'Y-m-d H:i:s.uP',default=>'Y-m-d H:i:s.u'});
        if (is_float($value)) $value = sprintf('%.17g', $value);
        $value = (string) $value;
        if (str_contains($value, "\0")) throw new SyncError('來源文字含 PostgreSQL 不接受的 NUL 字元，欄位：' . $column['name'] . '。不會自動刪除字元。');
        return $value;
    }
}

interface Source
{
    public function describe(string $sql): array;
    public function rows(string $sql, array $columns): iterable;
    public function beginRead(): bool;
    public function finishRead(): void;
    public function abortRead(): void;
}
interface Target
{
    public function inspect(string $table, array $columns): array;
    public function begin(): void;
    public function stage(string $stage, array $columns): void;
    public function append(string $stage, array $columns, array $rows): void;
    public function count(string $stage): int;
    public function replace(array $jobs): void;
    public function commit(): void;
    public function rollback(): void;
}

final class SqlServerSource implements Source
{
    private bool $transaction = false;
    public function __construct(private $connection, private int $timeout = 0) {}
    public static function failure(string $step): SyncError
    {
        $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS) ?: []; $codes = [];
        foreach ($errors as $error) $codes[] = (int) ($error['code'] ?? 0);
        $why = in_array(208,$codes,true) ? '來源缺少資料表或物件' : (in_array(207,$codes,true) ? '來源缺少 SQL 需要的欄位' : (in_array(229,$codes,true) ? '來源帳號沒有讀取權限' : '來源連線或查詢失敗，請檢查設定、SQL 欄位及 SQL Server 版本'));
        return new SyncError($step . '：' . $why . '（代碼 ' . implode(',', array_unique($codes)) . '）。');
    }
    private function prepare(string $sql)
    {
        $stmt = @sqlsrv_prepare($this->connection, $sql, [], ['QueryTimeout'=>$this->timeout]);
        if ($stmt === false) throw self::failure('準備 SPC 查詢失敗');
        return $stmt;
    }
    public function describe(string $sql): array
    {
        $stmt = $this->prepare($sql);
        try { $meta = @sqlsrv_field_metadata($stmt); if ($meta === false) throw self::failure('讀取 SPC 欄位結構失敗'); return Schema::columns($meta); }
        finally { sqlsrv_free_stmt($stmt); }
    }
    public function rows(string $sql, array $columns): iterable
    {
        $stmt = $this->prepare($sql);
        try {
            $meta = @sqlsrv_field_metadata($stmt);
            if ($meta === false || Schema::columns($meta) !== $columns) throw new SyncError('來源欄位結構在檢查後發生變動，已停止。');
            if (!@sqlsrv_execute($stmt)) throw self::failure('執行 SPC 查詢失敗');
            while (true) {
                $row = @sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
                if ($row === null) break;
                if ($row === false) throw self::failure('讀取 SPC 資料失敗');
                yield $row;
            }
        } finally { sqlsrv_free_stmt($stmt); }
    }
    public function beginRead(): bool
    {
        $stmt = @sqlsrv_query($this->connection, 'SELECT snapshot_isolation_state FROM sys.databases WHERE name = DB_NAME()');
        $state = $stmt === false ? null : sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
        if ($stmt !== false) sqlsrv_free_stmt($stmt);
        if ((int) ($state[0] ?? 0) !== 1) return false;
        $stmt = @sqlsrv_query($this->connection, 'SET TRANSACTION ISOLATION LEVEL SNAPSHOT');
        if ($stmt === false) throw self::failure('啟動來源唯讀快照失敗');
        sqlsrv_free_stmt($stmt);
        if (!@sqlsrv_begin_transaction($this->connection)) throw self::failure('啟動來源唯讀快照失敗');
        $this->transaction = true; return true;
    }
    public function finishRead(): void
    {
        if ($this->transaction && !sqlsrv_commit($this->connection)) throw self::failure('結束來源唯讀快照失敗');
        $this->transaction = false;
    }
    public function abortRead(): void { if ($this->transaction) { @sqlsrv_rollback($this->connection); $this->transaction = false; } }
}

final class PostgresTarget implements Target
{
    private array $inserts = [];
    public function __construct(private \PDO $pdo, private string $schema = 'public') { Schema::quote($schema); }
    private function qualified(string $table): string { return Schema::quote($this->schema) . '.' . Schema::quote($table); }
    public function inspect(string $table, array $columns): array
    {
        Schema::quote($table);
        $stmt = $this->pdo->prepare('SELECT c.oid,c.relname,c.relkind,c.relrowsecurity FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON n.oid=c.relnamespace WHERE n.nspname=? AND lower(c.relname)=lower(?)');
        $stmt->execute([$this->schema,$table]); $matches = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (count($matches)>1) throw new SyncError('目標有多個僅大小寫不同的資料表：' . $table);
        if (!$matches) return ['table'=>$table,'exists'=>false,'columns'=>array_column($columns,'name')];
        $match = $matches[0];
        if ($match['relkind'] !== 'r' || in_array($match['relrowsecurity'],[true,'t',1,'1'],true)) throw new SyncError('目標必須是未啟用 RLS 的一般資料表：' . $table);
        $stmt=$this->pdo->prepare("SELECT (SELECT count(*) FROM pg_catalog.pg_constraint WHERE contype='f' AND (conrelid=? OR confrelid=?)) + (SELECT count(*) FROM pg_catalog.pg_trigger WHERE tgrelid=? AND NOT tgisinternal) + (SELECT count(*) FROM pg_catalog.pg_inherits WHERE inhrelid=? OR inhparent=?)");
        $stmt->execute(array_fill(0,5,$match['oid']));
        if ((int)$stmt->fetchColumn()>0) throw new SyncError('目標有外鍵、觸發器或繼承關聯，為避免影響其他表已停止：' . $table);
        $stmt=$this->pdo->prepare('SELECT column_name,is_generated,is_identity,column_default FROM information_schema.columns WHERE table_schema=? AND table_name=? ORDER BY ordinal_position');
        $stmt->execute([$this->schema,$match['relname']]); $fields=$stmt->fetchAll(\PDO::FETCH_ASSOC); $lookup=[];
        foreach ($fields as $field) {
            if ($field['is_generated'] !== 'NEVER' || $field['is_identity'] === 'YES' || str_starts_with((string)$field['column_default'],'nextval(')) throw new SyncError('目標有自動產生或流水號欄位，工具不會自動更改它：' . $table);
            $key=strtolower($field['column_name']); if (isset($lookup[$key])) throw new SyncError('目標欄位大小寫不明確：' . $table); $lookup[$key]=$field['column_name'];
        }
        if(count($fields)!==count($columns)) throw new SyncError('目標欄位數與來源不同，請先調整結構；不會自動刪除或新增現有欄位：' . $table);
        $names=[];foreach($columns as $column){$key=strtolower($column['name']);if(!isset($lookup[$key]))throw new SyncError('目標缺少來源欄位：'.$table.'.'.$column['name']);$names[]=$lookup[$key];}
        return ['table'=>$match['relname'],'exists'=>true,'columns'=>$names];
    }
    public function begin(): void
    {
        $this->pdo->beginTransaction();
        $this->pdo->exec("SET LOCAL lock_timeout='30s'; SET LOCAL statement_timeout=0");
        $value=$this->pdo->query('SELECT pg_try_advisory_xact_lock(913726,1)')->fetchColumn();
        if(!in_array($value,[true,'t',1,'1'],true))throw new SyncError('另一個 DataExchange 正在執行，請稍後重試。');
    }
    public function stage(string $stage, array $columns): void
    {
        $definitions=array_map(static fn($c)=>Schema::quote($c['name']).' '.$c['pg_type'],$columns);
        $this->pdo->exec('CREATE TEMP TABLE '.Schema::quote($stage).' ('.implode(',',$definitions).') ON COMMIT DROP');
    }
    public function append(string $stage, array $columns, array $rows): void
    {
        if(!$rows)return;
        $key=$stage;
        if(!isset($this->inserts[$key]) || $this->inserts[$key]['rows']!==count($rows)){
            $fields=implode(',',array_map(static fn($c)=>Schema::quote($c['name']),$columns));
            $tuple='('.implode(',',array_fill(0,count($columns),'?')).')';
            $this->inserts[$key]=['rows'=>count($rows),'statement'=>$this->pdo->prepare('INSERT INTO '.Schema::quote($stage).' ('.$fields.') VALUES '.implode(',',array_fill(0,count($rows),$tuple)))];
        }
        $values=[];foreach($rows as $row)foreach($row as $value)$values[]=$value;
        $this->inserts[$key]['statement']->execute($values);
    }
    public function count(string $stage): int { return (int)$this->pdo->query('SELECT COUNT(*) FROM '.Schema::quote($stage))->fetchColumn(); }
    public function replace(array $jobs): void
    {
        $targets=[];
        foreach($jobs as $job){
            $target=$this->inspect($job['table'],$job['columns']);
            if(!$target['exists']){
                $definitions=array_map(static fn($c)=>Schema::quote($c['name']).' '.$c['pg_type'],$job['columns']);
                $this->pdo->exec('CREATE TABLE '.$this->qualified($target['table']).' ('.implode(',',$definitions).')');
            }
            $targets[]=$target;
        }
        $this->pdo->exec('LOCK TABLE '.implode(',',array_map(fn($t)=>$this->qualified($t['table']),$targets)).' IN ACCESS EXCLUSIVE MODE');
        foreach($jobs as $index=>$job){
            $target=$this->inspect($targets[$index]['table'],$job['columns']);
            $name=$this->qualified($target['table']);
            $this->pdo->exec('DELETE FROM '.$name);
            $dst=implode(',',array_map([Schema::class,'quote'],$target['columns']));
            $src=implode(',',array_map(static fn($c)=>Schema::quote($c['name']),$job['columns']));
            $this->pdo->exec('INSERT INTO '.$name.' ('.$dst.') SELECT '.$src.' FROM '.Schema::quote($job['stage']));
            if((int)$this->pdo->query('SELECT COUNT(*) FROM '.$name)->fetchColumn()!==$job['rows'])throw new SyncError('目標筆數驗證失敗：'.$job['table']);
        }
    }
    public function commit(): void { $this->pdo->commit(); }
    public function rollback(): void { if($this->pdo->inTransaction())$this->pdo->rollBack(); }
}

final class Exchange
{
    public function __construct(private Source $source,private Target $target,private $emit) {}
    public function check(array $definitions): array
    {
        $jobs=[];
        foreach($definitions as $definition){
            ($this->emit)('檢查結構：'.$definition['table']);
            $columns=$this->source->describe($definition['query']);
            $target=$this->target->inspect($definition['table'],$columns);
            ($this->emit)($definition['table'].'：'.count($columns).' 個欄位；'.($target['exists']?'替換既有目標資料':'建立新目標表'));
            $jobs[]=$definition+['columns'=>$columns];
        }
        return $jobs;
    }
    public function sync(array $jobs,int $batchSize=500): array
    {
        $summary=[];
        try{
            $this->target->begin();
            $snapshot=$this->source->beginRead();
            ($this->emit)($snapshot?'來源使用唯讀 SNAPSHOT，一致時點讀取。':'來源未啟用 SNAPSHOT，採逐表讀取；持續寫入時，各表讀取時點可能不同。');
            foreach($jobs as $index=>&$job){
                $job['stage']='exchange_'.bin2hex(random_bytes(8));$job['rows']=0;
                $this->target->stage($job['stage'],$job['columns']);
                $batch=[];$bytes=0;$size=min($batchSize,max(1,intdiv(60000,count($job['columns']))));
                ($this->emit)('['.($index+1).'/'.count($jobs).'] '.$job['table'].'：開始讀取全部資料');
                foreach($this->source->rows($job['query'],$job['columns'])as$row){
                    if(count($row)!==count($job['columns']))throw new SyncError('來源每列欄位數不一致：'.$job['table']);
                    $values=[];foreach($job['columns']as$i=>$column){$value=Schema::value($row[$i],$column);$values[]=$value;$bytes+=strlen($value??'');}
                    $batch[]=$values;$job['rows']++;
                    if(count($batch)>=$size||$bytes>=4*1024*1024){$this->target->append($job['stage'],$job['columns'],$batch);$batch=[];$bytes=0;($this->emit)($job['table'].'：已讀取 '.$job['rows'].' 筆');}
                }
                $this->target->append($job['stage'],$job['columns'],$batch);
                if($this->target->count($job['stage'])!==$job['rows'])throw new SyncError('暫存筆數驗證失敗：'.$job['table']);
                $summary[$job['table']]=$job['rows'];
                ($this->emit)($job['table'].'：暫存完成，共 '.$job['rows'].' 筆');
            }
            unset($job);
            $this->source->finishRead();
            ($this->emit)('全部來源讀取完成，正在一次替換正式 IDCC 的目標資料…');
            $this->target->replace($jobs);
            try{$this->target->commit();}catch(\Throwable$error){throw new CommitUncertain('最後提交結果未能確認。請先檢查目標筆數，再決定是否重跑。',0,$error);}
            return $summary;
        }catch(\Throwable$error){
            try{$this->source->abortRead();}catch(\Throwable$ignored){}
            try{$this->target->rollback();}catch(\Throwable$ignored){}
            throw $error;
        }
    }
}
