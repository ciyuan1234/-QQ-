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


$headimg=$jsondata->avatarUrl;
$city=$jsondata->city;
$country=$jsondata->country;
$gender=$jsondata->gender;
$language=$jsondata->language;
$nickname=$jsondata->nickName;
$province=$jsondata->province;

$openid=$jsondata->openid;

session_start();

if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}
if($headimg=="" || $openid=="" || $nickname==""){
	exitJson(2,"请求数据不全");
}

$db=getDb();
$now=time();

$user_info=getUserSimpleInfo($openid);
if($user_info){
	$sql = "update `".getTablePrefix()."_members` set nickname='$nickname', headimg='$headimg',lastlogin='$now' where openid='$openid' LIMIT 1";
	mysql_query($sql, $db) or die(mysql_error());

	if($user_info["headimg"]==""){
		addCoinHistory($openid,500,"用户首次授权赠送");
	}
}else{
	exitJson(3,"不存在的openid");
}

exitJson(0,"用户信息已更新",array("user_info"=>getUserSimpleInfo($openid)));


?>