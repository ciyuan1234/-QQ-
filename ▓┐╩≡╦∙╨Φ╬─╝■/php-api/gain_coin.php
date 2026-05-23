<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$coin=$jsondata->coin;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

$from=$jsondata->from;

if($from==""){
	exitJson(2,"缺少必要的参数");
}

$now=time();
if($from=="sun"){
	$lastgain=$_SESSION['lastgain'];
	if($lastgain){
		if($now-$lastgain<3){
			exitJson("3","你点得太快了");
		}
	}
	if($coin<=0||$coin>5){
		exitJson("4","非法的金额");
	}

	$_SESSION['lastgain']=$now;

	addCoinToBank($openid,$coin);
	exitJson(0,"收集到了".$coin."个阳光币，已存入小猪存钱罐",array("coin"=>$coin));
}



?>