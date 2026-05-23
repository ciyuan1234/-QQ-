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


$db=getDb();
$sql="select * from ".getTablePrefix()."_warehouse where ownerid='$openid' and quantity>0";
$res=mysql_query($sql,$db) or die(mysql_error());

$list = array();
while ($row = mysql_fetch_assoc($res)) {
	$row["unitprice"]=intval($row["unitprice"]);
	$row["quantity"]=intval($row["quantity"]);
	$list[]=$row;
}

$data=json_decode(file_get_contents("today.json"));
$quotation=$data->stockpercent;

exitJson(0,"",array("warehouse"=>$list,"quotation"=>0));

?>