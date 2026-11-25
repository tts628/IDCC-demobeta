<?php
require __DIR__.'/../lib.php';
use DataExchange\{PostgresTarget,Schema,SyncError};
function assertTarget($condition,$message){if(!$condition)throw new Exception($message);}
class CatalogStatement extends PDOStatement {
    private array $parameters=[];
    public function __construct(private CatalogPdo $db,private string $sql){}
    public function execute(?array $params=null):bool{$this->parameters=$params??[];$this->db->executions[]=[$this->sql,$this->parameters];return true;}
    public function fetchAll(int $mode=PDO::FETCH_DEFAULT,mixed ...$args):array{
        if(str_contains($this->sql,'FROM pg_catalog.pg_class'))return $this->db->relations;
        if(str_contains($this->sql,'FROM information_schema.columns'))return $this->db->fields;
        return [];
    }
    public function fetchColumn(int $column=0):mixed{
        if(str_contains($this->sql,'pg_try_advisory'))return $this->db->lock;
        if(str_contains($this->sql,'pg_constraint'))return $this->db->blockers;
        return 2;
    }
}
class CatalogPdo extends PDO {
    public array $relations=[['oid'=>'42','relname'=>'dyeproduction','relkind'=>'r','relrowsecurity'=>false]];
    public array $fields=[['column_name'=>'id','is_generated'=>'NEVER','is_identity'=>'NO','column_default'=>null],['column_name'=>'note','is_generated'=>'NEVER','is_identity'=>'NO','column_default'=>null]];
    public array $sql=[];public array $executions=[];public int $blockers=0;public bool $lock=true;public bool $transaction=false;
    public function __construct(){}
    public function prepare(string$query,array$options=[]):PDOStatement|false{return new CatalogStatement($this,$query);}
    public function query(string$query,?int$mode=null,mixed...$args):PDOStatement|false{$this->sql[]=$query;return new CatalogStatement($this,$query);}
    public function exec(string$sql):int|false{$this->sql[]=$sql;return 0;}
    public function beginTransaction():bool{$this->transaction=true;return true;}
    public function inTransaction():bool{return $this->transaction;}
    public function rollBack():bool{$this->transaction=false;return true;}
    public function commit():bool{$this->transaction=false;return true;}
}
$columns=Schema::columns([['Name'=>'ID','Type'=>4],['Name'=>'Note','Type'=>-9]]);
$pdo=new CatalogPdo;$target=new PostgresTarget($pdo);$info=$target->inspect('DyeProduction',$columns);
assertTarget($info['table']==='dyeproduction'&&$info['columns']===['id','note'],'Existing lower-case table not reused');
$target->begin();$target->stage('exchange_test',$columns);
$target->append('exchange_test',$columns,[['1',"'; DROP TABLE outside; --"],['2',null]]);
[$sql,$params]=end($pdo->executions);
assertTarget(!str_contains($sql,'DROP TABLE outside')&&$params[1]==="'; DROP TABLE outside; --",'Data concatenated into SQL');
$target->replace([['table'=>'DyeProduction','columns'=>$columns,'stage'=>'exchange_test','rows'=>2]]);$target->commit();
$sql=implode("\n",$pdo->sql);
assertTarget(str_contains($sql,'DELETE FROM "public"."dyeproduction"'),'Wrong replacement table');
assertTarget(!preg_match('/DROP TABLE|TRUNCATE|CASCADE/i',$sql),'Unsafe global/cascading deletion');
foreach(['foreign_key','identity','row_security','view','ambiguous_case']as$case){
    $p=new CatalogPdo;
    if($case==='foreign_key')$p->blockers=1;
    if($case==='identity')$p->fields[0]['is_identity']='YES';
    if($case==='row_security')$p->relations[0]['relrowsecurity']=true;
    if($case==='view')$p->relations[0]['relkind']='v';
    if($case==='ambiguous_case')$p->relations[]=$p->relations[0];
    try{(new PostgresTarget($p))->inspect('DyeProduction',$columns);throw new Exception('Unsafe target accepted:'.$case);}catch(SyncError$error){}
    assertTarget(!$p->sql,'Target guard changed data');
}
$p=new CatalogPdo;$p->lock=false;$target=new PostgresTarget($p);
try{$target->begin();throw new Exception('Concurrent exchange accepted');}catch(SyncError$error){$target->rollback();}
assertTarget(!$p->transaction,'Lock failure transaction not released');
echo "PASS: production target adapter guards (mocked PostgreSQL), case mapping, bound data, scoped replacement, unsafe target rejection and concurrency lock\n";
