<?php
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';


$now=time();


//侧边栏菜单
$extmenus=array();
// $extmenus[]=array( 
// 	"title"=>"每日签到",
// 	"subtitle"=>"签到可获得100阳光币，连续7天加送声望值",
// 	"icon"=>"liwu",
// 	"url"=>""
// 				);
// $extmenus[]=array( 
// 	"title"=>"水果机抽奖",
// 	"subtitle"=>"尽情的投入阳光币，你会不会是那个幸运儿呢",
// 	"icon"=>"dianzan",
// 	"url"=>""
// 				);

//OnAppShare分享配置
$shareconfigs=array();
$shareconfigs[]=array( 
	"title"=>"开局给你一个神奇的花盆，能不能走上人生巅峰就靠你自己了",
	"image"=>"http://jnsii.com/amazpot/images/share_image.jpg"
				);
$shareconfigs[]=array( 
	"title"=>"给你一个花盆，你会用它种什么呢？",
	"image"=>"http://jnsii.com/amazpot/images/share_image.jpg"
				);
$shareconfigs[]=array( 
	"title"=>"在微信里领养一个盆栽，每天给它浇水，它会长出什么来呢？",
	"image"=>"http://jnsii.com/amazpot/images/share_image.jpg"
				);

//开屏广告
$openad=array();
$openad[]=array(
	"image"=>"http://file.jnsii.com/amazpot/images/openad_bottle.jpg",
	"url"=>"https://jnsii.com/amazpot/h5/bottlegift/"
);


$activities=array();
$activities[]=array(
	"image"=>"http://file.jnsii.com/amazpot/images/banner_cargift.jpg",
	"url"=>"https://jnsii.com/amazpot/h5/cargift/"
				);
$activities[]=array(
	"image"=>"http://file.jnsii.com/amazpot/images/banner_bottlegift.jpg",
	"url"=>"https://jnsii.com/amazpot/h5/bottlegift/"
				);

$total_member=getTotalMember();
$today_active=getTodayActive();


exitJson(0,"",array('server_time' => $now,'total_member'=>$total_member,'today_active'=>$today_active, 'extmenus'=>$extmenus,'shareconfigs'=>$shareconfigs,'openad'=>$openad,'activities'=>$activities));


?>