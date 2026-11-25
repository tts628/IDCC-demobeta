<?php
require __DIR__.'/../sync.php';
use DataExchange\{Source,Target,Schema,Exchange,SyncError,CommitUncertain};
function ensure($condition,$message){if(!$condition)throw new Exception($message);}
$definitions=exchangeDefinitions();ensure(count($definitions)===8,'Eight definitions required');
$columns=Schema::columns([['Name'=>'ID','Type'=>-5],['Name'=>'Note','Type'=>-9]]);
final class FakeSource implements Source {
    public array $data=[];public bool $finished=false;public bool $aborted=false;public ?string $fail=null;
    public function __construct(public array $columns,array$definitions){foreach($definitions as$i=>$d)$this->data[$d['query']]=$i===7?[]:[[(string)($i+1),"繁體中文\t\n'\\"],[(string)($i+100),null]];}
    public function describe(string$sql):array{return$this->columns;}
    public function rows(string$sql,array$columns):iterable{foreach($this->data[$sql]as$row){yield$row;if($sql===$this->fail)throw new SyncError('Simulated source failure');}}
    public function beginRead():bool{return true;}
    public function finishRead():void{$this->finished=true;}
    public function abortRead():void{$this->aborted=true;}
}
final class FakeTarget implements Target {
    public array $tables=[];public array $working=[];public array $stages=[];public array $calls=[];public bool $failReplace=false;public bool $failCommit=false;
    public function __construct(array$definitions){foreach($definitions as$d)$this->tables[$d['table']]=[['old']];$this->tables['Unrelated']=[['keep']];}
    public function inspect(string$table,array$columns):array{return ['table'=>$table,'exists'=>true,'columns'=>array_column($columns,'name')];}
    public function begin():void{$this->calls[]='begin';$this->working=$this->tables;$this->stages=[];}
    public function stage(string$stage,array$columns):void{$this->stages[$stage]=[];}
    public function append(string$stage,array$columns,array$rows):void{array_push($this->stages[$stage],...$rows);}
    public function count(string$stage):int{return count($this->stages[$stage]);}
    public function replace(array$jobs):void{$this->calls[]='replace';foreach($jobs as$i=>$job){$this->working[$job['table']]=$this->stages[$job['stage']];if($this->failReplace&&$i===2)throw new SyncError('Simulated target failure');}}
    public function commit():void{if($this->failCommit)throw new RuntimeException('Lost acknowledgement');$this->calls[]='commit';$this->tables=$this->working;}
    public function rollback():void{$this->calls[]='rollback';$this->working=$this->tables;}
}
$source=new FakeSource($columns,$definitions);$target=new FakeTarget($definitions);$events=[];$engine=new Exchange($source,$target,function($line)use(&$events){$events[]=$line;});
$jobs=$engine->check($definitions);ensure(!$target->calls,'Check performed writes');
$summary=$engine->sync($jobs,1);
ensure(count($summary)===8 && $summary['DyeStepVisits']===0,'Wrong counts or empty table not handled');
ensure($target->tables['DyeStepVisits']===[],'Full sync did not clear target for empty source');
ensure($target->tables['Unrelated']===[['keep']],'Unrelated table changed');
ensure($target->tables['DispenserEvent'][0][1]==="繁體中文\t\n'\\",'Text changed');
ensure($source->finished && in_array('commit',$target->calls,true),'No atomic completion');
$before=$target->tables;$engine->sync($jobs,2);ensure($before===$target->tables,'Repeat full sync duplicated data');
foreach(['source','target','nul']as$failure){
    $s=new FakeSource($columns,$definitions);$t=new FakeTarget($definitions);$before=$t->tables;
    if($failure==='source')$s->fail=$definitions[3]['query'];
    if($failure==='target')$t->failReplace=true;
    if($failure==='nul')$s->data[$definitions[4]['query']]=[['1',"bad\0text"]];
    $x=new Exchange($s,$t,static fn($line)=>null);
    try{$x->sync($x->check($definitions),1);throw new Exception('Expected failure');}catch(SyncError$error){}
    ensure($t->tables===$before && in_array('rollback',$t->calls,true),'Failure left partial target data');
}
$s=new FakeSource($columns,$definitions);$t=new FakeTarget($definitions);$t->failCommit=true;$x=new Exchange($s,$t,static fn($line)=>null);
try{$x->sync($x->check($definitions));throw new Exception('Expected uncertain commit');}catch(CommitUncertain$error){}
$precise=Schema::columns([['Name'=>'Amount','Type'=>3,'Precision'=>38,'Scale'=>12],['Name'=>'Date','Type'=>93,'Scale'=>7],['Name'=>'Blob','Type'=>-3],['Name'=>'Flag','Type'=>-7]]);
ensure($precise[0]['pg_type']==='numeric(38,12)' && Schema::value('12345678901234567890.123456789012',$precise[0])==='12345678901234567890.123456789012','Decimal precision lost');
ensure($precise[1]['pg_type']==='text' && Schema::value('2026-01-01 00:00:00.1234567',$precise[1])==='2026-01-01 00:00:00.1234567','Date precision lost');
ensure(Schema::value("\0\t\xff",$precise[2])==='\\x0009ff','Binary changed');
ensure(Schema::value(false,$precise[3])==='f'&&Schema::value(null,$precise[3])===null,'Boolean/null changed');
try{Schema::columns([['Name'=>'variant','Type'=>-150]]);throw new Exception('Unsupported type accepted');}catch(SyncError$error){}
$error=new PDOException('Failing row contains dummy-secret');$error->errorInfo=['23505',7,'dummy-secret'];ensure(!str_contains(exchangeError($error),'dummy-secret'),'Error leaked data');
echo "PASS: eight-table atomic sync, repeatability, empty-source replacement, source/target failure rollback, commit uncertainty, precision/binary/null handling and safe errors\n";
