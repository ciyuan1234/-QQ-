<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$userid=$jsondata->userid;
$type=$jsondata->type;
$quantity=$jsondata->quantity;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($userid==$openid)exitJson(3,"不能自己给自己转");

if($type==""||$quantity==""||$userid==""){
	exitJson(1,"缺少必要的参数");
}

$my_info=getUserSimpleInfo($openid);
if($my_info["cartype"]==""){
	exitJson(2,"你还没有小汽车，请前往商店购买");
}

$db=getDb();
$sql="select * from ".getTablePrefix()."_warehouse where ownerid='$openid' and `type`='$type' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());

$row = mysql_fetch_assoc($res);
if(!$row){
	exitJson(2,"没有找到此条记录");
}

if($row["quantity"]<=0){
	exitJson(3,"零库存");
}else if($quantity>$row["quantity"]){
	exitJson(4,"超出库存数量");
}

$sql="update ".getTablePrefix()."_warehouse set quantity=quantity-$quantity where ownerid='$openid' and `type`='$type' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());

$sql="select * from ".getTablePrefix()."_warehouse where ownerid='$openid' and `type`='$type' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());
$row = mysql_fetch_assoc($res);

addToWarehouse($userid,$type,$row["type_cn"],$quantity,$row["unitprice"]);

sendActionNotice($userid,$my_info["nickname"]."刚刚送了你".$row["type_cn"]."x".$quantity);

exitJson(0,"已送出");

?>