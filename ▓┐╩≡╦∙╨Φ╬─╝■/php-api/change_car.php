<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$cartype=$jsondata->cartype;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($pottype==""){
	exitJson(1,"缺少必要的参数");
}

$db=getDb();
$sql="update ".getTablePrefix()."_members set cartype='$cartype' where openid='$openid' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());


exitJson(0,"成功");


?>