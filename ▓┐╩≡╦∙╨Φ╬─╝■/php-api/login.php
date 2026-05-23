<?php
// ini_set('display_errors',1); //错误信息 
// ini_set('display_startup_errors',1); //php启动错误信息 
// error_reporting(-1); 
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';

$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);

$code=$jsondata->code;

if($code!=''){
	$contents = file_get_contents('https://api.weixin.qq.com/sns/jscode2session?appid='.getAppId().'&secret='.getAppSecret().'&js_code='.$code.'&grant_type=authorization_code');
	$jsondecode = json_decode($contents);
    $openid = $jsondecode->openid;//输出openid
    $session_key=$jsondecode->session_key;

    session_start();
	$_SESSION['openid'] = $openid;

	$user_info=getUserSimpleInfo($openid);
	$is_first=false;
	$now=time();
	$db=getDb();
	if($user_info){
		$sql = "update `".getTablePrefix()."_members` set lastlogin='$now' where openid='$openid' LIMIT 1";
		mysql_query($sql, $db) or die(mysql_error());
	}else{
		$sql = "insert into `".getTablePrefix()."_members` (openid, joindate,lastlogin) values('$openid','$now','$now')";
		mysql_query($sql, $db) or die(mysql_error());

		$is_first=true;
		
		addCoinToBank($openid,200);
		sow($openid,4.75,0,10);

		$user_info=getUserSimpleInfo($openid);
	}

    exitJson(0,'',array('openid'=>$openid,'session_id'=>session_id(),'user_info'=>$user_info,'is_first'=>$is_first));
}else{
	exitJson(1,'缺少code参数');
}

?>