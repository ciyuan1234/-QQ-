<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$type=$jsondata->type;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($type==""){
	exitJson(1,"缺少必要的参数");
}

$plant=getPlantInfo($openid);
if($type=="01"){
	if(!addCoinHistory($openid,-60,"购买普通肥料"))exitJson(2,"阳光币不足");
	$buff=mt_rand(5,20);
	$plant["vgain"]=intval($plant["vgain"])+$buff;

	$sql="update `".getTablePrefix()."_plants` set vgain=".$plant["vgain"]." where `id`=".$plant["id"]." LIMIT 1";
}else if($type=="03"){
	if(!addCoinHistory($openid,-100,"购买稀有肥料"))exitJson(2,"阳光币不足");
	$buff=mt_rand(5,10);
	$plant["vrare"]=intval($plant["vrare"])+$buff;

	//每5次必出稀有植物
	$hack_rare=0;
	$_SESSION['rolling_times']=intval($_SESSION['rolling_times'])+1;
	if($_SESSION['rolling_times']!=0&&$_SESSION['rolling_times']%5==0)$hack_rare=1;
	//

	$user_info=getUserSimpleInfo($openid);

	if(mt_rand(0,100)/100<$plant["vrare"]/100+intval($user_info["prestige"])/10000||$hack_rare||$plant["rare"]==1){
		$seed=getRandomSeeds(1,$plant['type']);
	}else{
		$seed=getRandomSeeds(0,$plant['type']);
	}

	$type_change_sql="";
	if($seed["rare"]>=$plant["rare"]){
		$type_change_sql=",rare=".$seed["rare"].", `type`='".$seed["type"]."', type_cn='".$seed["type_cn"]."', quantity='".$seed["quantity"]."', unitprice='".$seed["unitprice"]."', ori_quantity='".$seed["quantity"]."'";
		$plant["rare"]=$seed["rare"];
		$plant["type"]=$seed["type"];
		$plant["type_cn"]=$seed["type_cn"];
		$plant["quantity"]=$seed["quantity"];
		$plant["unitprice"]=$seed["unitprice"];
	}

	$sql="update `".getTablePrefix()."_plants` set vrare=".$plant["vrare"].$type_change_sql." where `id`=".$plant["id"]." LIMIT 1";
}else{
	exitJson(2,"没有此肥料");
}
$db=getDb();
mysql_query($sql, $db) or die(mysql_error());

$plant["vgain"]=intval($plant["vgain"])*(1-intval($plant["vworm"])/100)*(1-intval($plant["vthirsty"])/100);

exitJson(0,"",array("plant"=>$plant,"buff"=>$buff));


?>
