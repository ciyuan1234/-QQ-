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

//先拿到自己拥有过的水果list
$sql="select * from ".getTablePrefix()."_warehouse where ownerid='$openid'";
$res=mysql_query($sql,$db) or die(mysql_error());

$ihave = array();
while ($row = mysql_fetch_assoc($res)) {
	$ihave[]=$row;
}


//拿全部seed
$sql="select * from ".getTablePrefix()."_seeds";
$res=mysql_query($sql,$db) or die(mysql_error());

$seeds = array();
while ($row = mysql_fetch_assoc($res)) {
	$seeds[]=$row;
}


for ($i=0; $i < count($seeds); $i++) { 
	for ($j=0; $j < count($ihave); $j++) { 
		if($seeds[$i]["type"]==$ihave[$j]["type"]){
			$seeds[$i]["gotit"]=true;
		}
	}
}

exitJson(0,"",array("seeds"=>$seeds));


?>