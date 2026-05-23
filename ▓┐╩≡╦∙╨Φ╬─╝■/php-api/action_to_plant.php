<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$userid=$jsondata->userid;
$actiontype=$jsondata->actiontype;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($actiontype=="")exitJson(1,"缺少必要的参数");

$plant=getPlantInfo($userid);

$db = getDb();
if($openid!=$userid){
	if(getPlantLog($plant["id"],$openid,$actiontype)){
		if($actiontype==2){
			exitJson(1,"已经为它浇过水了，去访问别的朋友吧~");
		}else if($actiontype==1){
			exitJson(1,"已经为它除过虫了，去访问别的朋友吧~");
		}
	}else{
		$user_info=getUserSimpleInfo($openid);
		if($actiontype==2){
			addVisitLog($openid,$userid,$actiontype,"帮忙浇水");
			addCoinHistory($openid,10,"为好友浇水");
			sendActionNotice($userid,$user_info["nickname"]."刚刚帮忙浇了水");
		}else if($actiontype==1){
			addVisitLog($openid,$userid,$actiontype,"帮忙除虫");
			addCoinHistory($openid,10,"为好友除虫");
			sendActionNotice($userid,$user_info["nickname"]."刚刚帮忙除了虫");
		}
	}
}


if($actiontype==2)
{
	addPlantLog($plant["id"],$openid,$actiontype,"浇水");
	$plant["vthirsty"]=0;
	$sql = "update ".getTablePrefix()."_plants set vthirsty=0 where ownerid='$userid' and quantity>0 order by createtime desc LIMIT 1";
}
else if($actiontype==1)
{
	addPlantLog($plant["id"],$openid,$actiontype,"除虫");
	$plant["vworm"]=0;
	$sql = "update ".getTablePrefix()."_plants set vworm=0 where ownerid='$userid' and quantity>0 order by createtime desc LIMIT 1";
}
mysql_query($sql,$db) or die(mysql_error());

exitJson(0,"",array("plant"=>getPlantInfo($userid)));




?>