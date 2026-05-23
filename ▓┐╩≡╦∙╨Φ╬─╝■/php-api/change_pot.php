<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$pottype=$jsondata->pottype;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($pottype==""){
	exitJson(1,"缺少必要的参数");
}

// $plant=getPlantInfo($openid);
// if($plant){
// 	exitJson(2,"等采摘后才可以更换哦");
// }

$db=getDb();
$sql="update ".getTablePrefix()."_members set pottype='$pottype' where openid='$openid' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());


exitJson(0,"更换成功",array("pot"=>getPotInfo($pottype)));


?>