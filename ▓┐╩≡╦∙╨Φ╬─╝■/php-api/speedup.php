<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$userid=$jsondata->userid;
$isbuy=$jsondata->isbuy;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

$now=time();
$plant=getPlantInfo($userid);

if($now-intval($plant["seedtime"])>intval($plant["growduration"])){
	exitJson(0,"",array("seedtime"=>intval($plant["seedtime"])));
}


if($isbuy){
	if(!addCoinHistory($openid,-2000,"加速5小时")){
		exitJson(2,"阳光币不足");
	}
	$seedtime=intval($plant["seedtime"])-18000;
}else{
	if($openid!=$userid){
		if(getVisitLogToday($userid,$openid,3)){
			exitJson(3,"每天只能为相同好友加速一次哦");
		}
		$seedtime=intval($plant["seedtime"])-3600;
		addVisitLog($openid,$userid,3,"帮忙加速");

		$user_info=getUserSimpleInfo($openid);
		sendActionNotice($userid,$user_info["nickname"]."刚刚帮忙加速了生长");
	}else{
		// exitJson(1,"广告还没有准备好");

		$lastspeedup=$_SESSION['lastspeedup'];
		if($lastspeedup){
			if($now-$lastspeedup<15){
				exitJson("1","内测期不用看广告，但需等待15秒");
			}
		}
		$_SESSION['lastspeedup']=$now;
		$seedtime=intval($plant["seedtime"])-1800;
	}
}
addPlantLog($plant["id"],$openid,6,"加速");

$db = getDb();
$sql = "update ".getTablePrefix()."_plants set seedtime=$seedtime where ownerid='$userid' and quantity>0 order by createtime desc LIMIT 1";
mysql_query($sql,$db) or die(mysql_error());

exitJson(0,"",array("seedtime"=>$seedtime));




?>