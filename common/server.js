// 项目后端配置
// 示例：var HOST="https://yourdomain.com/amazpot";
var HOST = "http://localhost/amazpot"; 

// API 地址，通常为 HOST + "/php-api"
// 注意：原项目中目录名为 "▓┐╩≡╦∙╨Φ╬─╝■/php-api"，部署时建议改为 "php-api"
var API_HOST = HOST + "/php-api/"; 

// 静态资源 CDN 地址 (如果图片存放在 CDN，则填写 CDN 域名；如果放在 static，则填空字符串或 HOST)
var CDN_HOST = "http://localhost/amazpot/cdn-images/"; 

var session_id;

function request_url(url,method,data,callbacks){
	if(!callbacks)callbacks={};
	if(!data)data={};
	uni.request({
		url:url,
		method:method,
		data:data,
		header: {
			'Cookie': session_id?'PHPSESSID=' + session_id:''
		},
		success:function(res){
			uni.hideLoading();
			if(res.data.err==0){
				if(res.data.result&&res.data.result.session_id)session_id=res.data.result.session_id;
				if(callbacks.success)callbacks.success(res.data);
			}else if(res.data.err==1001){
				uni.redirectTo({
					url: '/pages/login/login'
				});
			}else{
				if(callbacks.fail)callbacks.fail(res.data);
			}
		},
		fail:function(err){
			uni.hideLoading();
			if(callbacks.fail)callbacks.fail(err);
		},
		complete:function(){
			uni.hideLoading();
			if(callbacks.complete)callbacks.complete();
		}
	})
}
function request_api(api_name,method,data,callbacks){
	request_url(API_HOST+api_name+".php",method,data,callbacks);
}
function get_api(api_name,data,callbacks){
	request_api(api_name,"get",data,callbacks);
}
function post_api(api_name,data,callbacks){
	request_api(api_name,"post",data,callbacks);
}
function get_res(res_url)
{
	if(!res_url) return "";
	if(res_url.indexOf("http")>=0){
		return res_url;
	}else{
		return CDN_HOST+res_url;
	}
}
function cdn2host(cdnurl){
	if(!cdnurl) return "";
	var prefix_index=cdnurl.indexOf(CDN_HOST);
	if(prefix_index>=0){
		return HOST+cdnurl.substring(CDN_HOST.length,cdnurl.length);
	}
	return cdnurl;
}

export default {
	HOST:HOST,
	API_HOST:API_HOST,
	CDN_HOST:CDN_HOST,
	request_url:request_url,
	get_api:get_api,
	post_api:post_api,
	get_res:get_res,
	cdn2host
}