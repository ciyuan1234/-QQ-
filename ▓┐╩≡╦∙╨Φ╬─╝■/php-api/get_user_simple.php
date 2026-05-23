<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);
$userid=$jsondata->userid;

if($userid==""){
	exitJson(1,"缺少必要的参数");
}

$user_info=getUserSimpleInfo($userid);

exitJson(0,"",array(
	"user_info"=>$user_info
));


?>