<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$openid=$jsondata->openid;
$date=$jsondata->date;

session_start();
if($openid!=$_SESSION['openid']){
	exitJson(1001,"登录已过期，请重新登录");
}



$arr=array();
$arr[]=array(
	"id"=>"",
	"type"=>"01",
	"type_cn"=>"普通肥料",
	"effect"=>"果实产量增加5~20%",
	"price"=>60
);
$arr[]=array(
	"id"=>"",
	"type"=>"03",
	"type_cn"=>"稀有肥料",
	"effect"=>"提升5~10%稀有率重新播种",
	"price"=>100
);

exitJson(0,"",array("fertilizers"=>$arr));

?>