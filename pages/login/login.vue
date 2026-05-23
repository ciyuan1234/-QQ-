<template>
	<view>
		<ezlogin @login_success="login_success"></ezlogin>
	</view>
</template>

<script>
	import ezlogin from "../../components/ezlogin.vue"
	
	export default {
		components:{
			ezlogin
		},
		data() {
			return {
				
			}
		},
		methods: {
			login_success:function(){
				uni.redirectTo({
					url: '../index/index'
				});
			}
		}
	}
</script>

<style>

</style>
