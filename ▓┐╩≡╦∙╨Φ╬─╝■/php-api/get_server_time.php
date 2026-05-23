<?php
header("Content-type:text/html;charset=utf-8");
include_once 'functions.php';
include_once 'sqlutils.php';

$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;

if($openid){
	$user_info=getUserSimpleInfo($openid);
	if(!$user_info)exitJson(1,"数据有误，重启应用");
}

$now=time();

exitJson(0,"",array('server_time' => $now));


?>