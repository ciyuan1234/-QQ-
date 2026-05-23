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

$user_info=getUserSimpleInfo($openid);


if(addCoinHistory($openid,-100,"播种")){
	$plant=getPlantInfo($openid);
	if(!$plant){
		$plant=sow($openid);
		addPlantLog($plant["id"],$openid,0,"播种");
		exitJson(0,"",array("plant"=>$plant));
	}else{
		if($plant["quantity"]>0){
			exitJson(2,"播种失败，还有没采摘完的植物");
		}else{
			$plant=sow($openid);
			addPlantLog($plant["id"],$openid,0,"播种");
			exitJson(0,"",array("plant"=>$plant));
		}
	}

}else{
	exitJson(1,"播种失败，阳光币不足");
}


?>