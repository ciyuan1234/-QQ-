<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$category=$jsondata->category;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}


$db=getDb();
$sql="select * from ".getTablePrefix()."_shop where category=$category LIMIT 12";
$res=mysql_query($sql,$db) or die(mysql_error());

$list = array();
while ($row = mysql_fetch_assoc($res)) {
	$row["gotit"]=shopItemIsGot($openid,$row["id"]);
	$list[]=$row;
}

exitJson(0,"",array("shop"=>$list));

?>