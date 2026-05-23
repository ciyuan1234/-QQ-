<template>
	<view>
		<web-view :src="srcUrl"></web-view>
	</view>
</template>

<script>
	import utils from "../../common/utils.js"
	export default {
		data() {
			return {
				srcUrl:"",
				originUrl:""
			};
		},
		onLoad(options) {
			var that=this;
			var url=unescape(options.url);
			
			var srcUrl=url;
			if (srcUrl.lastIndexOf("&") >= 0) srcUrl += "&openid=" + global.user_info.openid;
			else srcUrl += "?openid=" + global.user_info.openid;
			that.originUrl=url;
			that.srcUrl=srcUrl;
			console.log(srcUrl);
		},
		onShareAppMessage(res) {
			if (res.from === 'button') {// 来自页面内分享按钮
			  
			}
			return {
			  title: "",
			  content:"",
			  desc:"",
			  path: '/pages/index/index?url='+this.originUrl
			}
		  }
	}
</script>

<style>

</style>
