<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}
$now=time();

$bank_info=getBankInfo($openid);

$from=$jsondata->from;
if($from==""||$from=="normal"){
	addCoinHistory($openid,$bank_info["coin"],"储蓄罐提出");
	addCoinToBank($openid,-$bank_info["coin"]);

	exitJson(0,"",array("coin"=>$bank_info["coin"]));
}else if($from=="ad"){
	
	$lastbankoutad=$_SESSION['lastbankoutad'];
	if($lastbankoutad){
		if($now-$lastbankoutad<15){
			exitJson("1","内测期不用看广告，但需等待15秒");
		}
	}
	$_SESSION['lastbankoutad']=$now;

	$buff=mt_rand(10,50);
	$coin=ceil(intval($bank_info["coin"])*(1+$buff/100));
	addCoinHistory($openid,$coin,"储蓄罐看广告提出");
	addCoinToBank($openid,-$bank_info["coin"]);

	exitJson(0,"",array("coin"=>$coin,"buff"=>$buff));
}




?>