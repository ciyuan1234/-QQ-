<template>
	<view v-if="show" style="width: 100%;height: 100%;position: fixed;z-index:999;top:0;left:0;background: rgba(0,0,0,.3);padding:80px 20px 20px 20px;" :style="{paddingTop:(safeArea.top+70)+'px'}">
		<view style="width: 100%;max-width: 374px;margin: 0 auto;border-radius: 10px;overflow: hidden;" :style="{background:title!=''?'#fff':'none'}">
			<view v-if="title!=''" style="background: #fff;width:100%;height:50px;line-height: 50px;text-align: center;background: #fed43d;color:#333;">{{title}}</view>
			<view style="min-height: 0px;background: #fff;" :style="{background:title!=''?'#fff':'none'}">
				<slot name="content"></slot>
			</view>
			<view v-if="show_close_button" style="width: 100%;padding:10px;background: #fff;">
				<view @click="close_clicked" style="width: 100%;text-align: center;color: #333;background: #fed43d;height:40px;line-height: 40px;border-radius: 6px;">关闭</view>
			</view>
		</view>
	</view>
</template>

<script>
	var sysinfo=uni.getSystemInfoSync();
	export default {
		data() {
			return {
				safeArea:null
			};
		},
		props:{
			title:{
				type:String,
				default:""
			},
			close_title:{
				type:String,
				default:"关闭"
			},
			show_close_button:{
				type:Boolean,
				default:true
			},
			show:{
				type:Boolean,
				default:false
			}
		},
		methods:{
			close_clicked:function(){
				this.$emit("close");
			}
		},
		mounted() {
			this.safeArea=sysinfo.safeArea;
		}
	}
</script>

<style>

</style>
