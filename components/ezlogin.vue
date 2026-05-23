<template>
	<view v-if="!is_login" style="width:100%;height:100%;position: fixed;z-index:99999999;background: #ffffff;">
		<!-- <button style="margin-top: 200px;" @getuserinfo="get_user_info" open-type="getUserInfo">登录</button> -->
	</view>
</template>

<script>
	import utils from "../common/utils.js"
	import server from "../common/server.js"
	import api from "../common/api.js"
	
	export default {
		data() {
			return {
				is_login:false
			};
		},
		methods:{
			// get_user_info:function(e){
			// 	var that=this;
			// 	var user_info=e.detail.userInfo;
			// 	if(user_info){
			// 		user_info.openid=this.openid;
			// 		server.post_api(api.POST_USER_INFO,user_info,{
			// 			success:function(res){
			// 				if(res.err==0){
			// 					that.user_info=res.result.user_info;
			// 					if(that.user_info){
			// 						global.user_info=that.user_info;
			// 						
			// 						that.$emit("login_success");
			// 					}
			// 				}
			// 			}
			// 		});
			// 	}else{
			// 		utils.show_alert("请允许我们获得您的公开信息");
			// 	}
			// }
		},
		mounted() {
			var that=this;
			uni.showLoading({mask:true});
			utils.login(function(openid,user_info,is_first){
				uni.hideLoading();
				that.is_login=true;
				global.user_info=user_info;
				global.is_first=is_first;
				that.$emit("login_success");
			});
		}
	}
</script>

<style>

</style>
