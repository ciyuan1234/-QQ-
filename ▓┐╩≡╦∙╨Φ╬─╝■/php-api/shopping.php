<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$shopid=$jsondata->shopid;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}

if($shopid==""){
	exitJson(1,"缺少必要的参数");
}

$now=time();
$db=getDb();
$sql="select * from ".getTablePrefix()."_shop where id='$shopid' LIMIT 1";
$res=mysql_query($sql,$db) or die(mysql_error());

$shopitem = mysql_fetch_assoc($res);
if(!$shopitem){
	exitJson(2,"没有找到此条记录");
}


if($shopitem["onlyonce"]==1){
	$sql="select * from ".getTablePrefix()."_shoppinglog where shopid='$shopid' and ownerid='$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());
	if(mysql_fetch_assoc($res)){
		exitJson(3,"此商品不能重复购买");
	}
}

if($shopitem["unit_type"]==0){
	if(!addCoinHistory($openid,-$shopitem["price"],"购买".$shopitem["name"])){
		exitJson(4,"购买失败，阳光币不足");
	}
}

$sql="insert into ".getTablePrefix()."_shoppinglog (ownerid,shopid,category,createtime) values('$openid','$shopid','".$shopitem["category"]."','$now')";
$res=mysql_query($sql,$db) or die(mysql_error());

exitJson(0,"购买成功",array("coin"=>-$shopitem["price"]));

?>