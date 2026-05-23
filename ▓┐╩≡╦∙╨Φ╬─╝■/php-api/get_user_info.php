<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$userid=$jsondata->userid;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($userid==""){
	exitJson(1,"缺少必要的参数");
}

if($userid!=$openid){
	if(isDailyNewVisitor($openid,$userid)){
		addCoinHistory($userid,5,"好友每日访问");
		addVisitLog($openid,$userid,0,"好友每日访问，阳光币+5");
	}
}

$user_info=getUserSimpleInfo($userid);

$plant=getPlantInfo($userid);

exitJson(0,"",array(
	"user_info"=>$user_info,
	"bank"=>getBankInfo($userid),
	"pot"=>getPotInfo($user_info["pottype"]),
	"car"=>$user_info["cartype"],
	"plant"=>$plant,
	"visitors"=>getVisitors($userid)
));


?>