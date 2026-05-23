<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$db=getDb();


//小猪储蓄罐自增长
$sql="UPDATE  `".getTablePrefix()."_bank` SET coin = IF( coin + growcoin < capacity, coin + growcoin, capacity )";
$res=mysql_query($sql,$db) or die(mysql_error());


$now=time();

//植物的虫害状态
$sql="UPDATE  `".getTablePrefix()."_plants` SET vworm = IF( vworm + 10 < 100, vworm + 10, 100 ) where unix_timestamp(now())<(seedtime+growduration-stepduration) and unix_timestamp(now())>(seedtime+stepduration) and rand()<0.1";
$res=mysql_query($sql,$db) or die(mysql_error());

//植物的干旱状态
$sql="UPDATE  `".getTablePrefix()."_plants` SET vthirsty = IF( vthirsty + 10 < 100, vthirsty + 10, 100 ) where unix_timestamp(now())<(seedtime+growduration-stepduration) and unix_timestamp(now())>(seedtime+stepduration) and rand()<0.5";
$res=mysql_query($sql,$db) or die(mysql_error());

$hour=intval(date("H",$now));
$minute=intval(date("i",$now));
if($hour>8&&$hour<20){
	$sql="select ownerid from `".getTablePrefix()."_plants` where unix_timestamp(now())<(seedtime+growduration-stepduration) and unix_timestamp(now())>(seedtime+stepduration) and vthirsty>0";
	$res=mysql_query($sql,$db) or die(mysql_error());

	while ($row = mysql_fetch_assoc($res)) {
		sendDrinkRemindNotice($row["ownerid"],"你的植物口渴了！为它和你自己补充点水分吧！");
	}
}

if($hour==0&&$minute<30){
	$data=array("stockpercent"=>7);
	$fp = fopen("today.json", "w");
    fwrite($fp, json_encode($data));
    fclose($fp);
}else if($hour==8&&$minute<30){
	
}
//