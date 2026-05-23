<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';
// require_once "lock.php";
// $newClass = new RedSpinlock('lockKey', $redisIP, $redisPort);
// $lock  = $newClass->lock(毫秒数不传默认3000秒);
// if ($lock) {
//    // 执行代码（这里是你整个执行的代码）

//    $newClass->unlock();
   
// }


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=mysql_real_escape_string($jsondata->openid);
$userid=mysql_real_escape_string($jsondata->userid);

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

$db = getDb();

$plant=getPlantInfo($userid);
if(!$plant)exitJson(1,"这盆植物已经被摘光了");

if($plant["level"]<5)exitJson(2,"植物还没成熟呢");

//虫害以及干旱影响比例
$vworm_vthirsty=(1-intval($plant["vworm"])/100)*(1-intval($plant["vthirsty"])/100);
//收成比例
$vgain=intval($plant["vgain"]);
//收成比例浮点
$v_vgain=$vgain/100;
//剩余果实数量
$quantity=intval($plant["quantity"]);
//标准果实数量
$ori_quantity=intval($plant["ori_quantity"])*$vworm_vthirsty;


if($openid==$userid){
	//自己采摘
	$gain_percent=1;
	$gain_quantity=floor($ori_quantity*($v_vgain*$gain_percent));
}else{
	//采摘别人
	if(getPlantLog($plant["id"],$openid,4))exitJson(1,"已经采摘过这盆植物了，去访问别的朋友吧~");

	$gain_percent=mt_rand(5,50)/100;
	$gain_quantity=ceil($ori_quantity*($v_vgain*$gain_percent));

	addVisitLog($openid,$userid,4,"摘了".$plant["type_cn"]."x".$gain_quantity);

	$user_info=getUserSimpleInfo($openid);
	sendActionNotice($userid,$user_info["nickname"]."刚刚采摘了".$plant["type_cn"]."x".$gain_quantity);
}


//剩余果实数量=计算前剩余果实数量减本次采摘数量
$quantity=$quantity-$gain_quantity;
if($quantity<=0||$openid==$userid)$quantity=0;

$vgain=floor(($v_vgain-$v_vgain*$gain_percent)*100);

$sql = "update ".getTablePrefix()."_plants set quantity=$quantity,vgain=$vgain where ownerid='$userid' and quantity>0 order by createtime desc LIMIT 1";

mysql_query($sql,$db) or die(mysql_error());

addPlantLog($plant["id"],$openid,4,"采摘数量".$gain_quantity);
addToWarehouse($openid,$plant["type"],$plant["type_cn"],$gain_quantity,$plant["unitprice"]);

$plant["quantity"]=$quantity;
$plant["vgain"]=$vgain;
exitJson(0,"",array("gain_quantity"=>$gain_quantity,"plant"=>$plant));




?>