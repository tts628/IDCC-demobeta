<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
ini_set('display_errors','0');ini_set('log_errors','0');set_time_limit(0);
require_once __DIR__.'/lib.php';
use DataExchange\{Exchange,Schema,SqlServerSource,PostgresTarget,SyncError,CommitUncertain};

function exchangeDefinitions(): array
{
    $manifest=json_decode(file_get_contents(__DIR__.'/tables.json'),true,512,JSON_THROW_ON_ERROR);
    $expected=['DispenserEvent','DispenserProduction','DyeEnergyUsage','DyeEvent','DyelotsBulkedRecipe','DyeMachines','DyeProduction','DyeStepVisits'];
    if(($manifest['schema']??'')!=='public'||($manifest['source_profile']??'')!=='SPC'||($manifest['target_profile']??'')!=='IDCC_SERVER'||($manifest['mode']??'')!=='full_replace')throw new SyncError('交換設定必須是 SPC → 正式 IDCC_SERVER，public schema、full_replace。');
    $names=array_column($manifest['tables']??[],'table');$sorted=$names;$allowed=$expected;sort($sorted);sort($allowed);
    if($sorted!==$allowed)throw new SyncError('交換設定必須包含指定的八張表，每張一次。');
    $definitions=[];
    foreach($manifest['tables']as$entry){
        if($entry['sql']!=='sql/'.$entry['table'].'.sql')throw new SyncError('SQL 檔案路徑不正確。');
        $query=file_get_contents(__DIR__.'/'.$entry['sql']);
        // Reject accidental source writes. Only the shipped SELECT/CTE definitions are accepted.
        $tokens=preg_replace("~'(?:''|[^'])*'|\[(?:\]\]|[^\]])*\]|\"(?:\"\"|[^\"])*\"|/\*.*?\*/|--[^\r\n]*~s",' ',$query);
        if(!preg_match('/^\s*(SELECT|WITH)\b/i',$tokens)||str_contains(rtrim(rtrim($tokens),';'),';')||preg_match('/\b(INSERT|UPDATE|DELETE|MERGE|DROP|ALTER|TRUNCATE|CREATE|EXEC|EXECUTE|GRANT|DENY|REVOKE|DBCC|USE|GO|INTO|OPENROWSET|OPENDATASOURCE|BULK)\b/i',$tokens))throw new SyncError('來源 SQL 必須是單一唯讀 SELECT：'.$entry['table']);
        $definitions[]=['table'=>$entry['table'],'query'=>$query];
    }
    return $definitions;
}

function exchangeConnections(): array
{
    if(!extension_loaded('sqlsrv')||!extension_loaded('pdo_pgsql'))throw new SyncError('缺少 SQLSRV 或 PDO_PGSQL，請先重建專案的網站環境。');
    $get=static function(string$key,?string$fallback=null){$value=getenv($key);return ($value===false||$value==='')&&$fallback!==null?getenv($fallback):$value;};
    $host=$get('SPC_DB_HOST','HOST');$name=$get('SPC_DB_NAME','MDSVN_SPC_DB');$user=$get('SPC_DB_UID','UID');$password=$get('SPC_DB_PWD','PWD');
    foreach([$host,$name,$user,$password]as$value)if($value===false||$value==='')throw new SyncError('SPC 設定未完整，請先填妥並儲存套用。');
    $trust=filter_var(getenv('SPC_DB_TRUST_SERVER_CERTIFICATE')?:'false',FILTER_VALIDATE_BOOLEAN);
    $source=@sqlsrv_connect($host,['Database'=>$name,'UID'=>$user,'PWD'=>$password,'CharacterSet'=>'UTF-8','ReturnDatesAsStrings'=>true,'LoginTimeout'=>15,'Encrypt'=>true,'TrustServerCertificate'=>$trust]);
    if($source===false)throw SqlServerSource::failure('SPC 連線失敗');
    try{
        // Always the primary PostgreSQL profile, never the Demo-routed DbMapper IDCC slot.
        $primary=strtolower(getenv('IDCC_DB_DRIVER')?:'')==='pgsql';
        $pgHost=($primary?getenv('IDCC_DB_HOST'):false)?:getenv('IDCC_PG_HOST')?:'postgres';
        $pgPort=($primary?getenv('IDCC_DB_PORT'):false)?:getenv('IDCC_PG_PORT')?:'5432';
        $pgName=($primary?getenv('IDCC_DB_NAME'):false)?:getenv('POSTGRES_DB')?:'idcc';
        $pgUser=($primary?getenv('IDCC_DB_UID'):false)?:getenv('POSTGRES_USER')?:'idcc';
        $pgPassword=($primary?getenv('IDCC_DB_PWD'):false)?:getenv('POSTGRES_PASSWORD');
        if($pgPassword===false||$pgPassword==='')throw new SyncError('正式 IDCC PostgreSQL 密碼未設定，請先執行安裝。');
        foreach([$pgHost,$pgPort,$pgName]as$part)if(preg_match('/[;\x00-\x20]/',$part))throw new SyncError('正式 IDCC PostgreSQL 連線格式不正確。');
        $pdo=new PDO("pgsql:host=$pgHost;port=$pgPort;dbname=$pgName;connect_timeout=15",$pgUser,$pgPassword,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
        return [$source,$pdo];
    }catch(Throwable$error){sqlsrv_close($source);throw$error;}
}

function exchangeError(Throwable$error): string
{
    if($error instanceof SyncError)return $error->getMessage();
    if($error instanceof PDOException){
        $state=(string)($error->errorInfo[0]??$error->getCode());
        $reasons=['23505'=>'違反唯一值限制','23502'=>'來源含目標不接受的空值','23503'=>'外鍵限制','22P02'=>'欄位內容與目標型別不符','22001'=>'內容超過目標欄位長度','22008'=>'日期超出範圍','22021'=>'字元編碼不相容','42501'=>'目標帳號權限不足','55P03'=>'目標正被其他工作使用，取得鎖定逾時','28P01'=>'目標帳號或密碼不正確','08006'=>'目標連線中斷'];
        return '正式 IDCC PostgreSQL 操作失敗（SQLSTATE '.$state.'）：'.($reasons[$state]??'請檢查目標連線、欄位型別及資料表限制').'。';
    }
    return '交換程式遇到未預期的問題；請檢查套件是否完整。未顯示原始錯誤，以免包含資料內容或帳密。';
}

function exchangeMain(array$args): int
{
    $sync=false;$check=false;$yes=false;$batch=500;$timeout=0;
    for($i=1;$i<count($args);$i++){
        switch($args[$i]){
            case '--help':echo "用法：php DataExchange/sync.php --check\n      php DataExchange/sync.php --sync [--yes] [--batch-size 500] [--source-timeout 0]\n預設只檢查。來源 SPC SQL Server → 正式 IDCC PostgreSQL；無業務資料篩選。\n";return 0;
            case '--check':$check=true;break;
            case '--sync':$sync=true;break;
            case '--yes':$yes=true;break;
            case '--batch-size':case '--source-timeout':
                $flag=$args[$i];$value=$args[++$i]??'';
                if(!ctype_digit($value))throw new SyncError('參數必須是非負整數：'.$flag);
                if($flag==='--batch-size'){$batch=(int)$value;if($batch<1||$batch>5000)throw new SyncError('批次筆數須為 1–5000。');}
                else{$timeout=(int)$value;if($timeout>86400)throw new SyncError('來源逾時最多 86400 秒，0 表示不限制。');}
                break;
            default:throw new SyncError('不支援的參數。請使用 --help。');
        }
    }
    if($check&&$sync)throw new SyncError('不能同時指定 --check 與 --sync。');
    $emit=static function(string$message):void{echo $message.PHP_EOL;flush();};
    $sourceConnection=null;
    try{
        $definitions=exchangeDefinitions();
        $emit('來源：SPC SQL Server → 目標：正式 IDCC PostgreSQL（不受 Demo 開關影響）');
        $emit('範圍：指定八張表。所有資料列篩選已移除，欄位計算與關聯保留。');
        [$sourceConnection,$pdo]=exchangeConnections();
        $source=new SqlServerSource($sourceConnection,$timeout);$target=new PostgresTarget($pdo);
        $exchange=new Exchange($source,$target,$emit);$jobs=$exchange->check($definitions);
        if(!$sync){$emit('檢查完成，沒有更動來源或目標資料。正式交換請使用 --sync。');return 0;}
        if(!$yes){
            $emit('將全量替換正式 IDCC 的這八張表；來源零筆也會清空對應目標表。其他表不在同步範圍。');
            $emit('輸入 SYNC 確認執行，其他輸入取消：');
            if(trim(fgets(STDIN)?:'')!=='SYNC'){$emit('已取消，未更動資料。');return 0;}
        }
        $summary=$exchange->sync($jobs,$batch);
        $emit('交換完成，八張目標表已一併提交：');
        foreach($summary as$table=>$count)$emit('  '.$table.'：'.$count.' 筆');
        return 0;
    }finally{if($sourceConnection!==null)sqlsrv_close($sourceConnection);}
}

if(realpath($_SERVER['SCRIPT_FILENAME']??'')===__FILE__){
    try{exit(exchangeMain($argv));}
    catch(Throwable$error){
        fwrite(STDERR,exchangeError($error).PHP_EOL);
        fwrite(STDERR,$error instanceof CommitUncertain?'最後提交結果不明，請先檢查目標資料。'.PHP_EOL:'同步未成功。提交前的目標變更會回復，不會保留半套資料。'.PHP_EOL);
        exit(1);
    }
}
