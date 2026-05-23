<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';


function isDitributionMode($buildVersion){
	$buildVersion=intval($buildVersion);
	if($buildVersion>0){
		return $buildVersion<=1008;
	}else{
		return true;
	}
}

function textFilter($text){
	$replace = array(
	'共产党', '国民党','习近平','温家宝','江泽民','毛泽东','华国锋','邓小平','政府','上访','信访','法轮功','地下党','李克强','赵紫阳','朱镕基','薄熙来','逼','鸡巴','操你','操他','操她','操它','干你','港独','藏独','台独','独立','胡锦涛');
	return str_replace($replace, '**', $text);
}

//统计所有用户数量
function getTotalMember(){
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_members";
	$res=mysql_query($sql,$db) or die(mysql_error());

	return mysql_num_rows($res);
}

function getTodayActive(){
	$today_zero = strtotime(date("Y-m-d"),time());
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_members where lastlogin>$today_zero";
	$res=mysql_query($sql,$db) or die(mysql_error());

	return mysql_num_rows($res);
}

//存入小猪存钱罐
function addCoinToBank($openid,$add_coin){
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_bank where ownerid = '$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	if(mysql_num_rows($res)<=0){
		$sql = "insert into ".getTablePrefix()."_bank (ownerid) values('$openid')";
		mysql_query($sql,$db) or die(mysql_error());
	}

	$coin=0;
	$capacity=200;

	$row = mysql_fetch_assoc($res);
	if($row){
		$coin=intval($row['coin']);
		$capacity=intval($row['capacity']);
	}

	$coin=$coin+$add_coin;
	if($coin>$capacity){
		$coin=$capacity;
	}else if($coin<0){
		$coin=0;
	}

	$sql="update ".getTablePrefix()."_bank set coin=$coin where ownerid = '$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());
}

//检查商品是否买过
function shopItemIsGot($openid,$shopid){
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_shoppinglog where ownerid = '$openid' and `shopid`='$shopid' order by createtime desc LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());
	$row = mysql_fetch_assoc($res);

	if($row){
		return true;
	}else{
		return false;
	}
}

//获得小猪存钱罐信息
function getBankInfo($openid){
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_bank where ownerid = '$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	if(mysql_num_rows($res)<=0){
		$sql = "insert into ".getTablePrefix()."_bank (ownerid) values('$openid')";
		mysql_query($sql,$db) or die(mysql_error());

		$sql = "select * from ".getTablePrefix()."_bank where ownerid = '$openid' LIMIT 1";
		$res=mysql_query($sql,$db) or die(mysql_error());
	}

	$row = mysql_fetch_assoc($res);
	return $row;
}

//获得盆子信息
function getPotInfo($pottype)
{
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_shop where category=1 and `type` = '$pottype' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$row = mysql_fetch_assoc($res);
	return $row;
}

//添加好友互动记录
function addVisitLog($openid,$userid,$actiontype="",$msg=""){
	$now = time();
	$db = getDb();

	$owner_info=getUserSimpleInfo($openid);

	$sql = "insert into ".getTablePrefix()."_visitlog (ownerid,headimg,nickname,userid,actiontype,msg,createtime) values('$openid','".$owner_info["headimg"]."','".$owner_info["nickname"]."','$userid','$actiontype','$msg','$now')";
	mysql_query($sql,$db) or die(mysql_error());
}
//是否为新的访客
function isNewVisitor($openid,$userid){
	if($openid==$userid)return false;
	$now=time();
	$db=getDb();

	$sql = "select id from ".getTablePrefix()."_visitlog where ownerid='$openid' and userid='$userid' LIMIT 1";
	$res = mysql_query($sql,$db) or die(mysql_error());
	$row = mysql_fetch_assoc($res);

	if($row){
		return false;
	}else{
		return true;
	}
}
//是否为今日新访客
function isDailyNewVisitor($openid,$userid){
	$today_zero = strtotime(date("Y-m-d"),time());
	$db=getDb();

	$sql = "select id from ".getTablePrefix()."_visitlog where ownerid='$openid' and userid='$userid' and createtime>$today_zero LIMIT 1";
	$res = mysql_query($sql,$db) or die(mysql_error());
	$row = mysql_fetch_assoc($res);

	if($row){
		return false;
	}else{
		return true;
	}
}

//添加声望值
function addPrestige($openid,$add_prestige=1){
	$db=getDb();
	$sql="update ".getTablePrefix()."_members set prestige=prestige+$add_prestige where openid='$openid' LIMIT 1";
	mysql_query($sql,$db) or die(mysql_error());
}

//获得指定openid被访问好友列表
function getVisitors($userid,$page=0){
	$db=getDb();
	//LIMIT 10000 很重要，必须加
	$sql="select * from(select * from (SELECT actiontype,createtime,msg,nickname,id,ownerid,headimg,userid FROM `".getTablePrefix()."_visitlog` WHERE userid='$userid' and nickname!='该用户尚未授权' order by actiontype desc, createtime desc limit 10000) t1 group by ownerid) t2 order by createtime desc limit 20";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$list = array();
	while ($row = mysql_fetch_assoc($res)) {
		$list[]=$row;
	}

	return $list;
}

//获得指定openid访问过的好友
function getMyVisits($openid,$page=0){
	$db=getDb();
	//LIMIT 10000 很重要，必须加
	$sql="select * from(select * from (SELECT actiontype,createtime,msg,nickname,id,ownerid,headimg,userid FROM `".getTablePrefix()."_visitlog` WHERE ownerid='$openid' order by actiontype desc, createtime desc limit 10000) t1 group by userid) t2 order by createtime desc limit 20";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$list = array();
	while ($row = mysql_fetch_assoc($res)) {
		$user_info=getUserSimpleInfo($row["userid"]);
		$row["headimg"]=$user_info["headimg"];
		$row["nickname"]=$user_info["nickname"];
		$row["ownerid"]=$user_info["openid"];
		$list[]=$row;
	}

	return $list;
}


//获得指定openid某一天的被访问好友列表
function getVisitorsByDate($userid,$time){
	$db=getDb();
	$sql="select * from ".getTablePrefix()."_visitlog where userid='$userid' and createtime>$time and createtime<$time+86400 order by createtime desc LIMIT 100";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$list = array();
	while ($row = mysql_fetch_assoc($res)) {
		$list[]=$row;
	}

	return $list;
}
//获得指定openid某一天被几个好友访问过
function getVisitorsCountByDate($userid,$time){
	$db=getDb();
	$sql="select count(0) from (select `ownerid` from (select `ownerid` from ".getTablePrefix()."_visitlog where userid='$userid' and createtime>$time and createtime<$time+86400 order by createtime desc) as t_visits group by ownerid) as a";
	$res=mysql_query($sql,$db) or die(mysql_error());

	return mysql_fetch_array($res)[0];
}
//获得指定openid，某一天访问过几个好友
function getVisitCountByDate($ownerid,$time){
	$db=getDb();
	$sql="select count(0) from (select userid from (select userid from ".getTablePrefix()."_visitlog where ownerid='$ownerid' and createtime>$time and createtime<$time+86400 order by createtime desc) as t_visits group by userid) as a";
	$res=mysql_query($sql,$db) or die(mysql_error());

	return mysql_fetch_array($res)[0];
}
//今天是不是对用户有过指定动作
function getVisitLogToday($userid,$openid,$actiontype){
	$today_zero = strtotime(date("Y-m-d"),time());
	$db=getDb();
	$sql="select * from ".getTablePrefix()."_visitlog where userid='$userid' and ownerid='$openid' and createtime>$today_zero and actiontype=$actiontype order by createtime desc LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$row = mysql_fetch_assoc($res);

	return $row;
}

//种下一个植物

function sow($openid,$level=0,$vworm=0,$vthirsty=0)
{
	//先拿到用户的声望值
	//根据声望的稀有度随机从seed表里取得种子信息
	//插入到plants表
	$user_info=getUserSimpleInfo($openid);

	$probability=$user_info["prestige"]/10000;
	if($probability>0.2)$probability=0.2;

	if(mt_rand(0,10000)/10000<$probability){
		$seed=getRandomSeeds(1);
	}else{
		$seed=getRandomSeeds(0);
	}

	$plant=$seed;

	$now=time();

	$plant["vrare"]=$probability*100;
	$plant["vworm"]=$vworm;
	$plant["vgain"]=100;

	if($user_info["pottype"]=="07"){
		$plant["vgain"]=105;
	}else if($user_info["pottype"]=="09"){
		$plant["vgain"]=115;
	}else if($user_info["pottype"]=="08"||$user_info["pottype"]=="11"){
		$plant["vgain"]=110;
	}

	$plant["vthirsty"]=$vthirsty;
	$plant["seedtime"]=$now-$level*7200;
	$plant["createtime"]=$now;
	$plant["level"]=floor($level);

	$db = getDb();
	$sql = "insert into ".getTablePrefix()."_plants (`ownerid`,`type`,`type_cn`,rare,vrare,vworm,vthirsty,vgain,seedtime,createtime,growduration,stepduration,quantity,ori_quantity,unitprice) values('$openid','".$plant["type"]."','".$plant["type_cn"]."','".$plant["rare"]."','".$plant["vrare"]."','".$plant["vworm"]."','".$plant["vthirsty"]."','".$plant["vgain"]."','".$plant["seedtime"]."','".$plant["createtime"]."','".$plant["growduration"]."','".$plant["stepduration"]."','".$plant["quantity"]."','".$plant["quantity"]."','".$plant["unitprice"]."')";
	$res=mysql_query($sql,$db) or die(mysql_error());

	return getPlantInfo($openid);
}

function getRandomSeeds($rare=0,$type=null){
	$db = getDb();
	$sql="select * from ".getTablePrefix()."_seeds where rare=$rare order by rand() LIMIT 1";
	if($type){
		$sql="select * from ".getTablePrefix()."_seeds where rare=$rare and `type`!='$type' order by rand() LIMIT 1";
	}
	$res=mysql_query($sql,$db) or die(mysql_error());

	$row = mysql_fetch_assoc($res);

	return $row;
}

//添加种植日志
function addPlantLog($plantid,$openid,$actiontype,$msg){
	$now=time();
	$db = getDb();
	$sql = "insert into ".getTablePrefix()."_plantlog (plantid,ownerid,actiontype,msg,createtime) values('$plantid','$openid','$actiontype','$msg','$now')";
	mysql_query($sql,$db) or die(mysql_error());
}
//检查是否已对植物有过指定动作
function getPlantLog($plantid,$openid,$actiontype){
	$now=time();
	$db=getDb();
	$sql="select * from ".getTablePrefix()."_plantlog where plantid='$plantid' and ownerid='$openid' and actiontype=$actiontype order by createtime desc LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$row = mysql_fetch_assoc($res);
	return $row;
}

//获得当前植物信息
function getPlantInfo($openid){
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_plants where ownerid = '$openid' and quantity>0 order by createtime desc LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$plant = mysql_fetch_assoc($res);
	if(!$plant)return null;

	$plant["seedtime"]=intval($plant["seedtime"]);
	$plant["createtime"]=intval($plant["createtime"]);
	$plant["growduration"]=intval($plant["growduration"]);
	$plant["stepduration"]=intval($plant["stepduration"]);
	$plant["quantity"]=intval($plant["quantity"]);

	$plant["vgain"]=intval($plant["vgain"]);

	$time_dis=($plant["seedtime"]+$plant["growduration"])-time();

	$plant["level"]=min(5,floor(($plant["growduration"]-$time_dis)/$plant["stepduration"]));
	return $plant;
}

//添加到仓库
function addToWarehouse($openid,$type,$type_cn,$quantity,$unitprice){
	$db=getDb();
	$sql="select * from ".getTablePrefix()."_warehouse where ownerid='$openid' and `type`='$type' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	if(mysql_num_rows($res)<=0){
		$sql = "insert into ".getTablePrefix()."_warehouse (ownerid,`type`,type_cn,quantity,unitprice) values('$openid','$type','$type_cn','$quantity','$unitprice')";
	}else{
		$sql = "update ".getTablePrefix()."_warehouse set quantity=quantity+$quantity where `type`='$type' and ownerid='$openid' LIMIT 1";
	}
	mysql_query($sql,$db) or die(mysql_error());
}

//添加代币历史
function addCoinHistory($openid,$value,$msg,$type=1){
	$userinfo=getUserSimpleInfo($openid);
	if($userinfo['coin']+$value<0){
		return false;
	}

	$db = getDb();
	$now=time();
	$sql = "insert into ".getTablePrefix()."_coinhistory (`type`,`value`,`msg`,createdate,ownerid) values('$type','$value','$msg','$now','$openid')";
	$res=mysql_query($sql,$db) or die(mysql_error());

	$balance=$userinfo['coin']+$value;
	$sql2 = "update ".getTablePrefix()."_members set coin='$balance' where openid='$openid' LIMIT 1";
	$res=mysql_query($sql2,$db) or die(mysql_error());

	return true;
}

//获得用户信息，简单
function getUserSimpleInfo($openid){
	$db = getDb();
	$sql = "select * from ".getTablePrefix()."_members where openid = '$openid' LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());

	if(mysql_num_rows($res)<=0){
		return null;
	}

	$row = mysql_fetch_assoc($res);
	if($row['nickname']=="")$row['nickname']="该用户尚未授权";
	$row['lastlogin']= $row['lastlogin'];
	$row['joindate']= $row['joindate'];
	
	return $row;
}

function getFormId($uid){
	$db = getDb();
	$time=strtotime('-7 day');
	$sql = "select * from ".getTablePrefix()."_formids where ownerid = '$uid' and used=0 and createdate>$time order by createdate asc LIMIT 1";
	$res=mysql_query($sql,$db) or die(mysql_error());
	if(mysql_num_rows($res)<=0){
		return false;
	}
	$row = mysql_fetch_assoc($res);
	return $row['formid'];
}

function deleteFormId($formid){
	$db = getDb();
	$sql="delete from ".getTablePrefix()."_formids where formid='$formid'";
	$res=mysql_query($sql,$db) or die(mysql_error());
}

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

function getAccessToken(){
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".getAppId()."&secret=".getAppSecret();
      $res = json_decode(httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $fp = fopen("access_token.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
}

//发送浇水提醒模板消息
function sendDrinkRemindNotice($openid,$notice_content){
	$data=array(
		'keyword1' => [
            'value' => $notice_content,
            'color' => '#ff3366',
        ],
        'keyword2' => [
            'value' => date('Y-m-d H:i:s',time()),
            'color' => '#333333',
		]
	);

	sendNotice($openid,"Kz51zWwjqY0aZwnAMNLpJQtRyi3OgajD0st9v1Y423k",$data,"/pages/index/index");
}

//发送互动模板消息
function sendActionNotice($openid,$notice_content){
	//QC_We73A0lUEtb_cnoxF6DvRrQb3KJlkVzkCgsZu9OQ
	$data=array(
		'keyword1' => [
            'value' => $notice_content,
            'color' => '#ff3366',
        ],
        'keyword2' => [
            'value' => date('Y-m-d H:i:s',time()),
            'color' => '#333333',
		]
	);

	sendNotice($openid,"QC_We73A0lUEtb_cnoxF6DvRrQb3KJlkVzkCgsZu9OQ",$data,"/pages/index/index");
}

//发送模板消息：fsockopen模式
function sendNotice($uid,$templateid,$data,$turl,$color='',$emphasis_keyword=''){
    $formid=getFormId($uid);
	if(!$formid){
		return;
	}

	$template = array(
	    'touser' => $uid,
	    'template_id' => $templateid,
	    'page' => $turl,
	    'form_id'=>$formid,
	    'color'=>$color, 
		'data' => $data,
		'emphasis_keyword'=>$emphasis_keyword
	);
	
    $access_token = getAccessToken();
    
    $params = json_encode($template);
    $start_time = microtime(true);

	$fp = fsockopen('api.weixin.qq.com', 80, $error, $errstr, 1);
	$http = "POST /cgi-bin/message/wxopen/template/send?access_token={$access_token} HTTP/1.1\r\nHost: api.weixin.qq.com\r\nContent-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($params) . "\r\nConnection:close\r\n\r\n$params\r\n\r\n";
	fwrite($fp, $http);
	fclose($fp);

	deleteFormId($formid);
}

?>