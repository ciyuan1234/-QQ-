import server from "common/server.js"
import api from "common/api.js"

function login(cb)
{
	uni.login({
		provider:"weixin",
		success:function(res){
			server.post_api(api.LOGIN,{code:res.code},{
				success:function(res){
					// set_storage("openid",res.result.openid,getTime()+7200000);
					if(cb)cb(res.result.openid,res.result.user_info,res.result.is_first);
				}
			});
		}
	})
}

function get_storage(key){
	var data=uni.getStorageSync(key);
	if(data.expire_time==-1){
		return data.value;
	}else{
		var now=getTime();
		if(now>data.expire_time){
			return null;
		}else{
			return data.value;
		}
	}
}
function set_storage(key,value,expire_time=-1)
{
	uni.setStorageSync(key,{
		value:value,
		expire_time:expire_time
	});
}

function get_time(){
	return new Date().getTime()/1000;
}
function get_wave(){
	return Math.random()>.5?1:-1;
}
function trim(str) {  var s = str.replace(/(^\s*)|(\s*$)/g, "");  return s;}
function number_grow(value,to_value,update,interval=100)
{
	var fin_value=parseFloat(value);
	var to_value=parseFloat(to_value);
	var number_grow_timer=setInterval(function(){
		fin_value+=Math.ceil((to_value-value)/10);
		if(fin_value>=to_value){
			fin_value=to_value;
			clearInterval(number_grow_timer);
		}
		if(update)update(fin_value);
	},interval);
}
function navigate_to(url){
	var type="internal";
	if (url.indexOf("http") >= 0) type = "url";
	else if (url.indexOf("xcx") >= 0) type = "xcx";
	
	if (type == "url") {
		wx.navigateTo({
			url: '/pages/webview/webview?url=' + escape(url),
		})
	} else if (type == "xcx") {
		var url_sub=url.substr(6, url.length);
		var splitIndex=url_sub.indexOf("/");
		if(splitIndex<0)splitIndex=url_sub.length;
		var xcxid=url_sub.substr(0,splitIndex);
		var xcxpath=url_sub.substr(splitIndex,url_sub.length-splitIndex);
		console.log(xcxid);
		console.log(xcxpath);
		wx.navigateToMiniProgram({
			appId: xcxid,
			path: xcxpath
		});
	} else {
		wx.navigateTo({
			url: url,
		})
	}
}

function get2PointDistance(x1, y1, x2, y2) { 
	var _x = Math.abs(x1 - x2); 
	var _y = Math.abs(y1 - y2); 
	return Math.sqrt(_x * _x + _y * _y); 
}

function show_alert(content,confirm_text='知道了'){
	uni.showModal({
		confirmText:confirm_text,
		showCancel:false,
		content:content
	});
}

function show_tips(text){
	uni.showToast({
		title:text,
		icon:"none"
	})
}

function date_format(date,fmt){
  var o = {
      "M+" : date.getMonth()+1,                 //月份
      "d+" : date.getDate(),                    //日
      "h+" : date.getHours(),                   //小时
      "m+" : date.getMinutes(),                 //分
      "s+" : date.getSeconds(),                 //秒
      "q+" : Math.floor((date.getMonth()+3)/3), //季度
      "S"  : date.getMilliseconds()             //毫秒
  };
  if(/(y+)/.test(fmt))
      fmt=fmt.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));
  for(var k in o)
      if(new RegExp("("+ k +")").test(fmt))
          fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
  return fmt;
}

function report_formid(formid){
	server.post_api(api.POST_FORMID,{formid:formid});
}

function get_scene_params(scene) {
	var params_arr=scene.split("&");
	var params={};
	
	for(var i=0;i<params_arr.length;i++){
		var param=params_arr[i].split("_");
		params[param[0]]=param[1];
	}
	
	return params;
}

function get_time_distance(str) {
  //2014-10-29 18:00:00
  var ymd = str.split(" ")[0];
  var ymd_arr = ymd.split("-");
  var hms = str.split(" ")[1];
  var hms_arr = hms.split(":");

  var date1 = new Date(ymd_arr[0], ymd_arr[1] - 1, ymd_arr[2], hms_arr[0], hms_arr[1], hms_arr[2]);
  var date2 = new Date();    //结束时间  
  var date3 = date2.getTime() - date1.getTime();  //时间差的毫秒数  
  var days = Math.floor(date3 / (24 * 3600 * 1000));

  var leave1 = date3 % (24 * 3600 * 1000);    //计算天数后剩余的毫秒数  
  var hours = Math.abs(Math.floor(leave1 / (3600 * 1000)));
  var leave2 = leave1 % (3600 * 1000);        //计算小时数后剩余的毫秒数  
  var minutes = Math.floor(leave2 / (60 * 1000))
  var leave3 = leave2 % (60 * 1000);      //计算分钟数后剩余的毫秒数  
  var seconds = Math.round(leave3 / 1000);

  if (days > 0) {
    if (days / 365 >= 1) {
      return Math.floor(days / 365) + "年前";
    } else {
      return days + "天前";
    }
  } else {
    if (hours > 0) {
      return hours + "小时前";
    } else {
      if (minutes <= 3) {
        return "刚刚";
      } else {
        return minutes + "分钟前";
      }
    }
  }

  return "刚刚";
}

function play_mp3(url){
	var audio=uni.createInnerAudioContext();
	audio.src=url;
	audio.play();
	
	return audio;
}
function play_sfx(sfx_name,delay=0){
	function do_sfx(){
		var sfx=play_mp3("static/sounds/"+sfx_name+".mp3");
		sfx.volume=.5;
	}
	
	if(delay>0){
		setTimeout(do_sfx,delay);
	}else{
		do_sfx();
	}
}

function check_update(){
	var updateManager = wx.getUpdateManager();
	updateManager.onCheckForUpdate(function (res) {
	  // 请求完新版本信息的回调
	  console.log(res.hasUpdate)
	})
	updateManager.onUpdateReady(function () {
	  updateManager.applyUpdate();
	})
	updateManager.onUpdateFailed(function () {
	  // 新版本下载失败
	})
}

module.exports = {
	login:login,
	trim:trim,
	check_update:check_update,
	get2PointDistance:get2PointDistance,
	play_mp3:play_mp3,
	play_sfx:play_sfx,
	report_formid:report_formid,
	date_format:date_format,
	get_time_distance:get_time_distance,
	get_time:get_time,
	get_scene_params:get_scene_params,
	get_wave:get_wave,
	number_grow:number_grow,
	navigate_to:navigate_to,
	show_alert:show_alert,
	show_tips:show_tips
}