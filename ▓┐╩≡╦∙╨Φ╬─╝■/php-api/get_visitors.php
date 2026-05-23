<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$action=$jsondata->action;
$page=$jsondata->page;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($page=="")$page=0;

if($action=="myvisits"){
	exitJson(0,"",array("myvisits"=>getMyVisits($openid,$page)));
}else{
	exitJson(0,"",array("visitors"=>getVisitors($openid,$page)));
}



?>