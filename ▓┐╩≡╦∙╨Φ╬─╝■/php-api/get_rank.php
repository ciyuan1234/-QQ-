<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$isglobal=$jsondata->isglobal;
$ranktype=$jsondata->ranktype;
$page=$jsondata->page;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($ranktype=="")$ranktype="coin";

if($page==""||!$page)$page=0;

$limit=50;


$db=getDb();

if($isglobal){
	$sql="select * from ".getTablePrefix()."_members where headimg<>'' order by coin desc LIMIT ".($page*$limit).",$limit";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$ranklist = array();
	while ($row = mysql_fetch_assoc($res)) {
		$row["openid"]="";
		$ranklist[]=$row;
	}

	$sql="SELECT b.* FROM (SELECT t.*, @rank := @rank + 1 AS rank FROM (SELECT @rank := 0) r,amazpot_members AS t where headimg<>'' ORDER BY t.coin DESC) AS b WHERE b.openid = '$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());
	$myrank=mysql_fetch_assoc($res);

	exitJson(0,"",array("ranklist"=>$ranklist,"myrank"=>$myrank["rank"]));
}else{
	$sql="select * from amazpot_members where headimg<>'' and (openid in (SELECT userid FROM `amazpot_visitlog` WHERE ownerid='$openid' group by userid) or openid = '$openid') order by coin desc limit ".($page*$limit).",$limit";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$ranklist = array();
	while ($row = mysql_fetch_assoc($res)) {
		$row["openid"]="";
		$ranklist[]=$row;
	}

	$sql="SELECT b.* FROM (SELECT t.*, @rank := @rank + 1 AS rank FROM (SELECT @rank := 0) r,(select * from amazpot_members where headimg<>'' and (openid in (SELECT userid FROM `amazpot_visitlog` WHERE ownerid='$openid' group by userid) or openid = '$openid') order by coin desc) AS t ORDER BY t.coin DESC) AS b WHERE b.openid = '$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());
	$myrank=mysql_fetch_assoc($res);

	exitJson(0,"",array("ranklist"=>$ranklist,"myrank"=>$myrank["rank"]));
}


?>