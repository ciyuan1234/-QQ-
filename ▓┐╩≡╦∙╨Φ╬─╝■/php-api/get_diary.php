<?php
// ini_set('display_errors',1); //错误信息 
// ini_set('display_startup_errors',1); //php启动错误信息 
// error_reporting(-1); 
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$date=$jsondata->date;//2019-12-13

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

$db=getDb();
$t_time=strtotime($date);

$user_info=getUserSimpleInfo($openid);

$now=time();
$diary=null;

$todaytime=strtotime(date('Y-m-d',$now));
$jointime=strtotime(date('Y-m-d',$user_info["joindate"]));

$has_prev=($t_time-$jointime)>=86400;
$has_next=$t_time<$todaytime;

$diary=array();
$diary["hasnext"]=$has_next;
$diary["hasprev"]=$has_prev;
$diary["daycount"]=floor(($t_time-$jointime)/86400)+1;
$diary["date"]=$t_time;
$diary["date_fmt"]=date('Y年m月d日',$t_time);

$spends = array();
$totalspend=0;
$sql="select * from `".getTablePrefix()."_coinhistory` where createdate>$t_time and createdate<($t_time+86400) and ownerid='$openid' and `value`<0 LIMIT 100";
$res=mysql_query($sql,$db) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$spends[]=$row;
	$totalspend+=intval($row["value"]);
}
$diary["totalspend"]=abs($totalspend);
$diary["spends"]=$spends;

$incomes = array();
$totalincome=0;
$sql="select * from `".getTablePrefix()."_coinhistory` where createdate>$t_time and createdate<($t_time+86400) and ownerid='$openid' and `value`>0 LIMIT 100";
$res=mysql_query($sql,$db) or die(mysql_error());
while ($row = mysql_fetch_assoc($res)) {
	$incomes[]=$row;
	$totalincome+=intval($row["value"]);
}
$diary["totalincome"]=$totalincome;
$diary["incomes"]=$incomes;

$diary["visitlogs"]=getVisitorsByDate($openid,$t_time);

$diary["bevisitcount"]=getVisitorsCountByDate($openid,$t_time);
$diary["visitcount"]=getVisitCountByDate($openid,$t_time);

exitJson(0,"",array("diary"=>$diary));




?>