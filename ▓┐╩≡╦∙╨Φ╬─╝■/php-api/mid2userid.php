<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$mid=$jsondata->mid;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($mid==""){
	exitJson(1,"缺少必要的参数");
}

$db=getDb();
$sql="select openid from ".getTablePrefix()."_members where `id`='$mid' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());
$row = mysql_fetch_assoc($res);

$userid=$row["openid"];

if(!$userid)exitJson(0,"",array("userid"=>$openid));

if(isNewVisitor($openid,$userid)){
	addPrestige($userid);
	addVisitLog($openid,$userid,0,"新访客，声望+1");
}

exitJson(0,"",array("userid"=>$userid));

?>