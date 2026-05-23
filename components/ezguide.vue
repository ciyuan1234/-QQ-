<template>
	<view v-if="show" @touchend="touch_end" style="width: 100%;height: 100%;position: fixed;z-index:9999999;top:0;left:0;background: rgba(0,0,0,.3);">
		<view v-if="guides[guide_index].arrow&&delay_done" :style="{left:guides[guide_index].arrow.x+'px',top:guides[guide_index].arrow.y+'px',transform:'rotate('+guides[guide_index].arrow.rotate+'deg)'}" style="transform-origin: 50% 100%;position: absolute;width:46px;height: 65px;z-index: 3;">
			<image class="arrow-jump" src="../static/guides/arrow.png" mode="widthFix" style="width: 46px;"></image>
		</view>
		<view v-if="guides.length>0" style="width: 100%;height: 100%;">
			<!-- guide_role.png -->
			<view v-if="guides[guide_index].text&&delay_done" style="width: 324px;height: 257px;position: absolute;z-index: 1;left:50%;bottom:200px;margin-left: -162px;">
				<form @submit="next_clicked" report-submit="true" style="width:100%;height: 100%;position: absolute;top:0px;left:0px;z-index: 9;">
					<button v-if="guides[guide_index].login" @getuserinfo="get_user_info" open-type="getUserInfo" class="clear_button" style="font-size: 16px;color:#fff;width:100%;height: 100%;">
						<view style="position: absolute;bottom: 80px;left:15px;color:#fff;font-size: 14px;">点击继续</view>
					</button>
					<button v-else-if="!guides[guide_index].arrow||guides[guide_index].arrow.withcontinue" form-type="submit" class="clear_button" style="font-size: 16px;color:#fff;width:100%;height: 100%;">
						<view style="position: absolute;bottom: 80px;left:15px;color:#fff;font-size: 14px;">点击继续</view>
					</button>
				</form>
				<image src="/static/guides/guide_role.png" mode="widthFix" style="width:100%;z-index: 1;"></image>
				<view style="position: absolute;z-index: 2;color:#fff;top:25px;left: 15px;width:220px;font-size: 16px;">
					{{guides[guide_index].text}}
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import utils from "../common/utils.js"
	import server from "../common/server.js"
	import api from "../common/api.js"
	
	export default {
		props:{
			show:{
				type:Boolean,
				default:false
			},
			guides:{
				type:Array,
				default(){
					return [];
				}
			}
		},
		data() {
			return {
				guide_index:0,
				delay_done:true
			};
		},
		methods:{
			touch_end:function(e){
				//click
				var touch=e.changedTouches[0];
				var guide_item=this.guides[this.guide_index];
				var arrow=guide_item.arrow;
				if(arrow){
					var distance=utils.get2PointDistance(touch.clientX,touch.clientY,arrow.x+23,arrow.y+65);
					if(arrow.id&&distance<=44){
						this.$emit("arrow_clicked",guide_item,arrow.id);
						this.next_guide();
					}
				}
			},
			next_clicked:function(e){
				if(e&&e.detail&&e.detail.formId)utils.report_formid(e.detail.formId);
				this.next_guide();
			},
			next_guide:function(){
				var that=this;
				if(this.guide_index<this.guides.length-1){
					this.guide_index+=1;
					var item=this.guides[this.guide_index];
					if(item.delay){
						that.delay_done=false;
						setTimeout(function(){
							that.delay_done=true;
						},item.delay);
					}else{
						that.delay_done=true;
					}
				}else{
					this.$emit("finished");
				}
			},
			get_user_info:function(e){
				var that=this;
				var user_info=e.detail.userInfo;
				if(user_info){
					user_info.openid=global.user_info.openid;
					server.post_api(api.POST_USER_INFO,user_info,{
						success:function(res){
							if(res.err==0){
								if(res.result.user_info){
									global.user_info=res.result.user_info;
									that.next_guide();
									that.$emit("login_success");
								}else{
									utils.show_alert("未知错误");
								}
							}
						},
						fail:function(res){
							utils.show_alert(res.msg);
						}
					});
				}else{
					that.next_guide();
				}
			}
		}
	}
</script>

<style>
	@keyframes ani-jump{
		0%{
			transform: translateY(0px);
		}
		50%{
			transform: translateY(-10px);
		}
		100%{
			transform: translateY(0px);
		}
	}
	.arrow-jump
	{
		animation: ani-jump 2s infinite;
	}
</style>
