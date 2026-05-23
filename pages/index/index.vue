<template>
	<view style="width:100%;height:100%;position: absolute;overflow: hidden;">
		<poster 
			:postersData="postersData"
			@success="onSuccessCreatePosters"
			@error="onPostersError">
		</poster>
		<ezguide :show="guide_show" :guides="guides_cfg" @finished="guide_finished" @arrow_clicked="guide_arrow_clicked" @login_success="guide_login_success"></ezguide>
		<ezlogin @login_success="login_success"></ezlogin>
		<ezwindow :title="ezwindow_title" :show="ezwindow_show" :show_close_button="ezwindow_show_close_button" @close="ezwindow_show=false">
			<view slot="content">
				<view v-if="ezwindow_content_type=='rare_rolling'" style="padding:10px;width:100%;display: flex;flex-wrap: wrap;">
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==0?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(0)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(0)?'':'brightness(0)',opacity:rolling_item_gotit(0)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==1?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(1)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(1)?'':'brightness(0)',opacity:rolling_item_gotit(1)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==2?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(2)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(2)?'':'brightness(0)',opacity:rolling_item_gotit(2)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==7?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(7)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(7)?'':'brightness(0)',opacity:rolling_item_gotit(7)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" style="font-size: 12px;text-align: center;padding-top:40px;">
						<view>当前稀有度加成</view>
						<view>{{plant_rare}}%</view>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==3?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(3)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(3)?'':'brightness(0)',opacity:rolling_item_gotit(3)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==6?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(6)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(6)?'':'brightness(0)',opacity:rolling_item_gotit(6)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==5?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(5)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(5)?'':'brightness(0)',opacity:rolling_item_gotit(5)?'1':'.5'}"></image>
					</view>
					<view class="rare_rolling_item" :style="{border:rare_rolling_result==4?'2px solid #fed43d':'none'}">
						<image :src="rolling_item_image(4)" mode="widthFix" style="width:100%;" :style="{filter:rolling_item_gotit(4)?'':'brightness(0)',opacity:rolling_item_gotit(4)?'1':'.5'}"></image>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='rank'">
					<view style="width:100%;height:50px;display: flex;font-size: 16px;">
						<view v-if="rank_window_tab==0" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;">全球排名</view>
						<view @click="rank_window_tab_clicked(0)" v-else style="background: #fedba0;width:100px;height:40px;margin-top:10px;line-height: 40px;text-align: center;border-top-left-radius: 10px;color:#999;">全球排名</view>
						<view v-if="rank_window_tab==1" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;border-top-left-radius: 10px;">好友排名</view>
						<view @click="rank_window_tab_clicked(1)" v-else style="background: #fedba0;width:100px;height:40px;line-height: 40px;margin-top:10px;text-align: center;border-top-right-radius: 10px;color:#999;">好友排名</view>
						<text @click="share_menu_show=true" style="font-size: 14px;color:#fff;position: absolute;top:25px;right:10px;">→ 邀请好友</text>
					</view>
					<!-- <view style="background: #fff;border-top-right-radius: 10px;padding:0px 10px;display:flex;font-size: 12px;justify-content: space-between;">
						<view style="background: #f2f2f2;flex:1;text-align: center;padding:5px 0px;">
							<view>现金榜</view>
						</view>
						<view style="background: #f2f2f2;flex:1;text-align: center;padding:5px 0px;">
							<view>收入榜</view>
						</view>
						<view style="background: #f2f2f2;flex:1;text-align: center;padding:5px 0px;">
							<view>消费榜</view>
						</view>
					</view> -->
					<view style="min-height: 100px;max-height: 300px;overflow-y: scroll;padding:0px 10px;background: #fff;border-top-right-radius: 10px;">
						<view v-for="(item,index) in ranklist" style="width:100%;height:50px;display: flex;margin-top: 10px;border-radius: 6px;background: #f2f2f2;">
							<view style="text-align: center;font-size: 14px;width:50px;line-height: 50px;">
								<image v-if="index<3" :src="'../../static/body_'+['g','s','c'][index]+'.png'" mode="widthFix" style="width:28px;"></image><text v-else>{{index+1}}</text>
							</view>
							<view>
								<image :src="item.headimg" mode="aspectFill" style="width:30px;height:30px;margin-top: 10px;"></image>
							</view>
							<view style="flex:1;margin-left: 5px;margin-right: 5px;line-height: 50px;text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-size: 14px;">
								{{item.nickname}}
							</view>
							<view style="text-align: right;color:#333;font-size: 14px;padding-top:5px;">
								<view>{{item.coin}}<image src="../../static/icon_coin.png" mode="widthFix" style="width: 14px;margin-left: 5px;"></image></view>
								<view style="color: #999;">声望: {{item.prestige}}</view>
							</view>
							<view>
								<image :src="pot_image(item.pottype)" mode="aspectFill" style="width:40px;height:40px;margin-top: 5px;"></image>
							</view>
						</view>
					</view>
					<view style="background: #fff;padding:10px 10px 0px 10px;">
						<view style="width:100%;height:40px;display: flex;border-radius: 6px;background: #f6daa3;overflow: hidden;">
							<view style="text-align: center;font-size: 14px;width:50px;line-height: 40px;">
								{{myrank}}
							</view>
							<view>
								<image :src="owner_info.headimg" mode="aspectFill" style="width:30px;height:30px;margin-top: 5px;"></image>
							</view>
							<view style="flex:1;margin-left: 5px;margin-right: 5px;line-height: 40px;text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-size: 14px;">
								{{owner_info.nickname}}
							</view>
							<view style="text-align: right;color:#333;line-height: 40px;font-size: 14px;padding-right: 20px;">
								{{owner_info.coin}}<image src="../../static/icon_coin.png" mode="widthFix" style="width: 14px;margin-left: 5px;"></image>
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='promot'">
					<view v-if="app_info.activities.length<=0" style="width: 100%;text-align: center;color:#999;padding:20px 0px;">没有新的活动</view>
					<view v-else style="width:100%;max-height: 350px;overflow-y: scroll;">
						<view v-for="item,index in app_info.activities" @click="goto_url(item.url)" style="width:100%;margin-bottom: 5px;">
							<image :src="item.image" mode="widthFix" style="width:100%;"></image>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='seed_detail'" style="padding:10px 10px 0px 10px;">
					<view style="display: flex;">
						<view style="width:150px;">
							<image :src="get_res('plants/'+detail_seed.type+'.png')" mode="widthFix" style="width:100%;" :style="{filter:detail_seed.gotit?'':'brightness(0)'}"></image>
						</view>
						<view style="font-size: 12px;padding-top: 30px;padding-left:20px;color: #666;">
							<view>成熟时间：{{detail_seed.growduration/3600}}小时</view>
							<view>标准产量：{{detail_seed.quantity}}</view>
							<view>出售单价：{{detail_seed.unitprice}}</view>
							<view>预期总收入：{{detail_seed.quantity*detail_seed.unitprice}}</view>
							<view>稀有等级：{{["普通","稀有","极稀有"][detail_seed.rare]}}</view>
						</view>
					</view>
					<view style="font-size: 14px;text-align: center;">生长阶段</view>
					<view style="width:100%;display:flex;">
						<view v-for="(item,index) in [1,2,3,4,5]" style="flex:1;">
							<image :src="get_res('plants/'+detail_seed.type+item+'.png')" mode="widthFix" style="width:100%;" :style="{filter:detail_seed.gotit?'':'brightness(0)'}"></image>
							<view style="width: 100%;font-size: 12px;text-align: center;">{{["发芽","幼苗","小苗","开花","结果"][index]}}</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='gallery'" style="padding:0px 10px;display: flex;flex-wrap: wrap;margin-top: 10px;max-height: 350px;overflow-y: scroll;">
					<view v-for="(item,index) in gallerylist" @click="gallery_item_clicked(item)" style="margin-bottom: 10px;width:25%;padding:5px;">
						<view style="width:100%;border-radius:6px;border:2px solid #fed43d;">
							<image :src="get_res('plants/'+item.type+'.png')" mode="widthFix" style="width:100%;" :style="{filter:item.gotit?'':'brightness(0)'}"></image>
							<view style="width:100%;text-align: center;font-size: 12px;margin-top: -10px;margin-bottom: 10px;" :style="{color:['#333','#fed43d','#ff3366'][item.rare]}">{{item.gotit?item.type_cn:['普通','未解锁','未解锁'][item.rare]}}</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='fertilizer'" style="padding:0px 10px;">
					<view v-for="(item,index) in fertilizers" style="display: flex;border-radius:6px;border:2px solid #fed43d;margin-top: 10px;">
						<view style="width:60px;">
							<image :src="get_res('fertilizers/'+item.type+'.png')" mode="aspectFit" style="width:100%;height: 100%;"></image>
						</view>
						<view style="flex:1;padding-top:12px;margin-left: 10px;">
							<view style="font-size: 16px;">{{item.type_cn}}</view>
							<view style="font-size: 12px;color:#666;">{{item.effect}}</view>
						</view>
						<view @click="fertilizer_clicked(item)" style="width:80px;background:#fed43d;text-align: center;color:#333;line-height: 64px;">
							<image src="../../static/icon_coin.png" mode="widthFix" style="width: 16px;margin-right: 5px;"></image><text style="font-size: 20px;">{{item.price}}</text>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='speedup'" style="padding:0px 10px;">
					<view style="height:70px;display: flex;border-radius:6px;border:2px solid #fed43d;margin-top: 10px;">
						<view>
							<image :src="get_res('fertilizers/04.png')" mode="widthFix" style="width:40px;margin-left: 5px;margin-top: 15px;"></image>
						</view>
						<view style="flex:1;padding-top:12px;margin-left: 5px;">
							<view style="font-size: 16px;">加速30分钟</view>
							<view style="font-size: 12px;color:#666;">愿意看广告的人先致富</view>
						</view>
						<view @click="speedup_ad_clicked" style="width:100px;background:#fed43d;text-align: center;color:#333;padding-top:10px;">
							<view>立即加速</view>
							<view style="font-size: 12px;color:#333;">需观看视频广告</view>
						</view>
					</view>
					<view style="height:70px;display: flex;border-radius:6px;border:2px solid #fed43d;margin-top: 10px;">
						<view>
							<image :src="get_res('fertilizers/05.png')" mode="widthFix" style="width:40px;margin-left: 5px;margin-top: 15px;"></image>
						</view>
						<view style="flex:1;padding-top:12px;margin-left: 5px;">
							<view style="font-size: 16px;">加速60分钟</view>
							<view style="font-size: 12px;color:#666;">俗话说朋友多了路好走</view>
						</view>
						<view @click="speedup_invite_clicked" style="width:100px;background:#fed43d;text-align: center;color:#333;line-height: 66px;">
							邀请好友
						</view>
					</view>
					<view style="height:70px;display: flex;border-radius:6px;border:2px solid #fed43d;margin-top: 10px;">
						<view>
							<image :src="get_res('fertilizers/06.png')" mode="widthFix" style="width:40px;margin-left: 5px;margin-top: 15px;"></image>
						</view>
						<view style="flex:1;padding-top:12px;margin-left: 5px;">
							<view style="font-size: 16px;">加速5小时</view>
							<view style="font-size: 12px;color:#666;">有钱就是可以为所欲为</view>
						</view>
						<view @click="speedup_coin_clicked" style="width:100px;background:#fed43d;text-align: center;color:#333;padding-top:10px;">
							<view>立即购买</view>
							<view style="font-size: 12px;color:#333;"><image src="../../static/icon_coin.png" mode="widthFix" style="width: 12px;margin-right: 5px;"></image><text style="font-size: 12px;">2000</text></view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='bank'" style="padding:0px 10px;">
					<image class="bounce" :src="get_res('pigs/pig_01.png')" mode="widthFix" style="width:100%;transform-origin: 50% 100%;"></image>
					<view style="width:100%;text-align: center;margin-top: -50px;">瓷猪储蓄罐 <text v-if="bank.nextcapacity>bank.capacity" style="margin-left:10px;color: #3366ff;">升级</text></view>
					<view style="width:100%;text-align: center;font-size: 12px;color: #666;">最大存储{{bank.capacity}}，每30分钟+{{bank.growcoin}}</view>
					<view style="display: flex;margin-top: 10px;justify-content: space-between;">
						<view @click="bankout_clicked" style="flex:1;padding-right: 5px;">
							<view class="btn-subtitle">
								<view>余额提出</view>
								<view>{{bank.coin}}阳光币</view>
							</view>
						</view>
						<view @click="bankout_ad_clicked" style="flex:1;padding-left: 5px;">
							<view class="btn-subtitle">
								<view>随机加倍提</view>
								<view>需观看视频广告</view>
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='visitors'">
					<view style="width:100%;height:50px;display: flex;font-size: 16px;">
						<view v-if="visitor_window_tab==0" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;">最近来访</view>
						<view @click="visitor_window_tab_clicked(0)" v-else style="background: #fedba0;width:100px;height:40px;margin-top:10px;line-height: 40px;text-align: center;border-top-left-radius: 10px;color:#999;">最近来访</view>
						<view v-if="visitor_window_tab==1" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;border-top-left-radius: 10px;">访问过的</view>
						<view @click="visitor_window_tab_clicked(1)" v-else style="background: #fedba0;width:100px;height:40px;line-height: 40px;margin-top:10px;text-align: center;border-top-right-radius: 10px;color:#999;">访问过的</view>
						<text @click="share_menu_show=true" style="font-size: 14px;color:#fff;position: absolute;top:25px;right:10px;">→ 邀请好友</text>
					</view>
					<view v-if="visitor_window_tab==0" style="min-height: 100px;max-height: 350px;overflow-y: scroll;padding:0px 10px;background: #fff;border-top-right-radius: 10px;">
						<view v-if="visitors.length<=0" style="width: 100%;text-align: center;color:#999;padding:20px 0px;">空空如也</view>
						<view v-for="(item,index) in visitors" style="width:100%;display: flex;border:2px solid #fed43d;margin-top: 10px;border-radius: 6px;">
							<view style="width:60px;height:60px;background: #f2f2f2;overflow: hidden;">
								<image :src="item.headimg" mode="aspectFill" style="width:60px;height:60px;vertical-align: middle;"></image>
							</view>
							<view style="flex:1;margin-left: 10px;padding-top:6px;">
								<view style="font-size: 14px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{item.nickname}}</view>
								<view style="font-size: 12px;color:#666;overflow: hidden;line-height: 12px;">{{time_distance(item.createtime)}}来过{{item.msg!=""?("，"+item.msg):""}}</view>
							</view>
							<view @click="visitor_clicked(item);" style="width:80px;background:#fed43d;text-align: center;color:#333;line-height: 60px;">
								访问
							</view>
						</view>
					</view>
					<view v-else style="min-height: 100px;max-height: 350px;overflow-y: scroll;padding:0px 10px;background: #fff;border-top-right-radius: 10px;">
						<view v-if="myvisits.length<=0" style="width: 100%;text-align: center;color:#999;padding:20px 0px;">空空如也</view>
						<view v-for="(item,index) in myvisits" style="width:100%;display: flex;border:2px solid #fed43d;margin-top: 10px;border-radius: 6px;">
							<view style="width:60px;height:60px;background: #f2f2f2;overflow: hidden;">
								<image :src="item.headimg" mode="aspectFill" style="width:60px;height:60px;vertical-align: middle;"></image>
							</view>
							<view style="flex:1;margin-left: 10px;padding-top:6px;">
								<view style="font-size: 14px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{item.nickname}}</view>
								<view style="font-size: 12px;color:#666;overflow: hidden;line-height: 12px;">{{time_distance(item.createtime)}}去过{{item.msg!=""?("，"+item.msg):""}}</view>
							</view>
							<view @click="visitor_clicked(item);" style="width:80px;background:#fed43d;text-align: center;color:#333;line-height: 60px;">
								访问
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='shop'">
					<view style="width:100%;height:50px;display: flex;font-size: 16px;">
						<view v-if="shop_window_tab==0" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;">展柜</view>
						<view @click="shop_window_tab_clicked(0)" v-else style="background: #fedba0;width:100px;height:40px;margin-top:10px;line-height: 40px;text-align: center;border-top-left-radius: 10px;color:#999;">展柜</view>
						<view v-if="shop_window_tab==2" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;border-top-left-radius: 10px;">汽车</view>
						<view @click="shop_window_tab_clicked(2)" v-else style="background: #fedba0;width:100px;height:40px;line-height: 40px;margin-top:10px;text-align: center;color:#999;">汽车</view>
						<view v-if="shop_window_tab==1" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;border-top-left-radius: 10px;">其它</view>
						<view @click="shop_window_tab_clicked(1)" v-else style="background: #fedba0;width:100px;height:40px;line-height: 40px;margin-top:10px;text-align: center;border-top-right-radius: 10px;color:#999;">其它</view>
					</view>
					<view style="min-height: 100px;max-height: 350px;overflow-y: scroll;padding:0px 10px;background: #fff;border-top-right-radius: 10px;">
						<view v-if="shoplist.length<=0" style="width: 100%;text-align: center;color:#999;padding:20px 0px;">空空如也</view>
						<view style="display: flex;flex-wrap: wrap;padding-top: 10px;">
							<view v-for="(item,index) in shoplist" @click="shop_item_clicked(item)" style="width:33.33%;padding:5px;align-items: stretch;">
								<view style="width:100%;border:2px solid #fed43d;border-radius: 6px;padding-bottom: 5px;">
									<view v-if="item.inuse" style="position: absolute;width:100%;text-align: center;top:-5px;left:0px;z-index:2;">
										<text style="background: #fed43d;color:#333;border-bottom-left-radius: 6px;border-bottom-right-radius: 6px;padding:2px 8px;font-size: 12px;">使用中</text>
									</view>
									<view style="padding:10px;">
										<image :src="item.image" mode="aspectFit" style="width:100%;height:70px;"></image>
									</view>
									<view style="height:16px;color:#999;font-size: 12px;text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;margin-top: -20px;">
										{{item.effect=="无"?"":item.effect}}
									</view>
									<view style="font-size: 12px;text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;margin-top: 0px;">
										{{item.name}}
									</view>
									<view v-if="item.price==0||item.gotit" style="font-size: 12px;text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;vertical-align: middle;color:#999;">
										已拥有
									</view>
									<view v-else style="font-size: 12px;text-align: center;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;vertical-align: middle;">
										<image mode="widthFix" style="width:12px;height:12px;margin-right: 5px;" :src="item.unit_type=='0'?'/static/icon_coin.png':get_res('plants/'+item.unit_type+'.png')"></image>{{item.price}}
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='bindcup'" style="padding: 0px 10px;">
					<view style="display: flex;border: 2px solid #fed43d;border-radius: 6px;margin-top: 10px;">
						<view style="width:40px;text-align: center;vertical-align: middle;height:40px;margin-right: 5px;">
							<image :src="get_icon('yonghu')" mode="widthFix" style='width:20px;margin-top: 10px;'></image>
						</view>
						<view style="flex:1;">
							<input placeholder="Gululu账户名" type="text" style="width:100%;width:100%;height:40px;border: none;line-height: 40px;"/>
						</view>
					</view>
					<view style="display: flex;border: 2px solid #fed43d;border-radius: 6px;margin-top: 5px;">
						<view style="width:40px;text-align: center;vertical-align: middle;height:40px;margin-right: 5px;">
							<image :src="get_icon('jiesuo')" mode="widthFix" style='width:20px;margin-top: 10px;'></image>
						</view>
						<view style="flex:1;">
							<input placeholder="Gululu账户密码" password="true" type="text" style="width:100%;width:100%;height:40px;border: none;line-height: 40px;"/>
						</view>
					</view>
					<view style="display: flex;margin-top: 10px;justify-content: space-between;">
						<view style="flex:1;padding-right: 5px;">
							<view class="btn-subtitle">
								<view>优惠购买</view>
								<view>领取200元优惠券</view>
							</view>
						</view>
						<view style="flex:1;padding-left: 5px;">
							<view class="btn-subtitle">
								<view>立即登录</view>
								<view>已有账户可直接登录</view>
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='warehouse'">
					<view style="width:100%;height:50px;display: flex;font-size: 16px;">
						<view v-if="warehouse_window_tab==0" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;border-top-left-radius: 10px;">仓库</view>
						<view @click="warehouse_window_tab_clicked(0)" v-else style="background: #fedba0;width:100px;height:40px;margin-top:10px;line-height: 40px;text-align: center;border-top-left-radius: 10px;color:#999;">仓库</view>
						<view v-if="warehouse_window_tab==1" style="background: #fed43d;width:100px;height:50px;line-height: 50px;text-align: center;border-top-right-radius: 10px;border-top-left-radius: 10px;">汽车</view>
						<view @click="warehouse_window_tab_clicked(1)" v-else style="background: #fedba0;width:100px;height:40px;line-height: 40px;margin-top:10px;text-align: center;color:#999;border-top-right-radius: 10px;">汽车</view>
					</view>
					<view v-if="warehouse_window_tab==0" style="background: #fff;border-top-right-radius: 10px;padding:0px 10px;">
						<view v-if="quotation" style="color:#333;font-size: 14px;padding-top:5px;text-align: center;">今日市场涨跌幅：{{quotation>0?'+'+quotation+'%':quotation+'%'}}</view>
						<view style="max-height: 350px;overflow-y:scroll;">
							<view v-if="warehouselist.length<=0" style="width: 100%;text-align: center;color:#999;padding:20px 0px;">空空如也</view>
							<view v-for="(item,index) in warehouselist" style="width:100%;display:flex;border-radius: 6px;border:2px solid #fed43d;margin-top: 10px;">
								<view style="width:50px;height: 70px;">
									<image :src="get_res('plants/'+item.type+'.png')" style="width:100%;margin-top: 0px;" mode="widthFix"></image>
									<view style="width:100%;text-align: center;font-size: 12px;margin-top: -6px;">{{item.type_cn}}</view>
								</view>
								<view style="flex:1;margin-right: 20px;">
									<view style="font-size: 12px;margin-top: 8px;display: flex;">
										<view>单价：{{item.unitprice}}</view>
										<view style="flex:1;text-align: right;">库存：{{item.quantity}}</view>
									</view>
									<view style="width: 100%;height: 30px;margin-top: 5px;">
										<view style="flex:1;display: flex;border-radius: 6px;border:1px solid #ccc;line-height: 28px;">
											<view @click="sale_decrease_clicked(item)" style="width:40px;height: 100%;text-align: center;border-right: 1px solid #ccc;">-</view>
											<view style="flex:1;height: 100%;text-align: center;">
												{{item.sale_quantity?item.sale_quantity:0}}
											</view>
											<view @click="sale_increase_clicked(item)" style="width:40px;height: 100%;text-align: center;border-left: 1px solid #ccc;">+</view>
										</view>
									</view>
								</view>
								<view @click="sale_clicked(item)" style="width:60px;height:70px;background:#fed43d;text-align: center;line-height: 70px;">
									出售
								</view>
							</view>
						</view>
					</view>
					<view v-else style="border-top-right-radius: 10px;background: #fff;padding:0px 10px;">
						<view style="width:100%;text-align: center;padding:20px 0px;color:#ccc;font-size: 14px;">
							<view style="color:#ccc;">小汽车可向好友仓库运送水果</view>
						</view>
						<view v-if="owner_cartype==''" style="width: 100%;text-align: center;font-size: 14px;">
							<view>请前往商店购买</view>
						</view>
						<view v-else style="width:100%;text-align: center;margin-top: -50px;">
							<image :src="get_res('cars/'+owner_cartype+'.png')" mode="widthFix" style="width:250px;"></image>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='transfer'">
					<view style="display: flex;width:100%;height:100px;">
						<view style="text-align: center;">
							<image :src="user_info.headimg" mode="widthFix" style="width:30px;position: absolute;left:50%;margin-left: -15px;top:-40px;border-radius:15px;overflow: hidden;"></image>
							<image v-if="user_cartype!=''" :src="get_res('cars/'+user_cartype+'.png')" mode="widthFix" style="width:100px;"></image>
							<view v-else style="width:100px;height:100px;font-size: 12px;color:#ccc;padding-top:40px;text-align: center;"><text>还没有
							小汽车</text></view>
						</view>
						<view style="flex:1;">
							
						</view>
						<view>
							<image :src="owner_info.headimg" mode="widthFix" style="width:30px;position: absolute;left:50%;margin-left: -15px;top:-40px;border-radius:15px;overflow: hidden;"></image>
							<image v-if="owner_cartype!=''" :src="get_res('cars/'+owner_cartype+'.png')" mode="widthFix" style="width:100px;"></image>
							<view v-else style="width:100px;height:100px;font-size: 12px;color:#ccc;padding-top:40px;text-align: center;"><text>还没有
							小汽车</text></view>
						</view>
					</view>
					<view style="width:100%;color:#333;font-size: 14px;padding-top:5px;text-align: center;">将自己仓库中的收获转送给好友</view>
					<view style="max-height: 250px;overflow-y:scroll;padding:0px 10px;">
						<view v-if="warehouselist.length<=0" style="width: 100%;text-align: center;color:#999;padding:20px 0px;">空空如也</view>
						<view v-for="(item,index) in warehouselist" style="width:100%;display:flex;border-radius: 6px;border:2px solid #fed43d;margin-top: 10px;">
							<view style="width:50px;height: 70px;">
								<image :src="get_res('plants/'+item.type+'.png')" style="width:100%;margin-top: 0px;" mode="widthFix"></image>
								<view style="width:100%;text-align: center;font-size: 12px;margin-top: -6px;">{{item.type_cn}}</view>
							</view>
							<view style="flex:1;margin-right: 20px;">
								<view style="font-size: 12px;margin-top: 8px;display: flex;">
									<view>单价：{{item.unitprice}}</view>
									<view style="flex:1;text-align: right;">库存：{{item.quantity}}</view>
								</view>
								<view style="width: 100%;height: 30px;margin-top: 5px;">
									<view style="flex:1;display: flex;border-radius: 6px;border:1px solid #ccc;line-height: 28px;">
										<view @click="sale_decrease_clicked(item)" style="width:40px;height: 100%;text-align: center;border-right: 1px solid #ccc;">-</view>
										<view style="flex:1;height: 100%;text-align: center;">
											{{item.sale_quantity?item.sale_quantity:0}}
										</view>
										<view @click="sale_increase_clicked(item)" style="width:40px;height: 100%;text-align: center;border-left: 1px solid #ccc;">+</view>
									</view>
								</view>
							</view>
							<view @click="transfer_clicked(item)" style="width:60px;height:70px;background:#fed43d;text-align: center;line-height: 70px;">
								送出
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='congrats_new_plant'" style="width: 100%;overflow: hidden;position: relative;height:200px;">
					<image src="../../static/congrats_light.png" class="rotate-loop" mode="aspectFill" style="width: 500px;height:500px;position: absolute;top:50%;left:50%;margin-left: -250px;margin-top: -250px;"></image>
					<image src="../../static/congrats_light.png" class="rotate-loop" mode="aspectFill" style="animation-duration:2s;width: 500px;height:500px;position: absolute;top:50%;left:50%;margin-left: -250px;margin-top: -250px;"></image>
					<image :src="congrats_new_plant_image" mode="aspectFit" style="width:100%;height:200px;position: absolute;z-index: 3;"></image>
				</view>
				<view v-else-if="ezwindow_content_type=='diary'" style="padding:0px 10px;">
					<view style="display: flex;margin-top: 10px;">
						<view style="flex:1;">{{diary.date_fmt}}</view>
						<view style="text-align: right;">第{{diary.daycount}}天</view>
					</view>
					<view style="min-height: 100px;max-height: 300px;overflow-y: scroll;">
						<view style="margin-top: 10px;">
							<view style="font-size: 14px;">今天的总收入为{{diary.totalincome}}，总支出为{{diary.totalspend}}。<text v-if="diary.visitcount>0">访问了{{diary.visitcount}}名好友。</text><text v-else>有点犯懒，没有访问过任何好友。</text><text v-if="diary.bevisitcount>0">有{{diary.bevisitcount}}名好友来访问了我。</text><text v-else>没有好友来看我，感觉有点冷清。</text></view>
						</view>
						<view style="margin-top: 10px;">
							<view style="font-size: 12px;color: #666;">访客记录</view>
							<view v-for="(item,index) in diary.visitlogs" style="font-size: 12px;color:#666;">
								{{time_format(item.createtime)}} {{item.nickname}} {{item.msg}}
							</view>
						</view>
						<view style="margin-top: 10px;">
							<view style="font-size: 12px;color: #666;">消费记录</view>
							<view v-for="(item,index) in diary.spends" style="font-size: 12px;color:#666;">
								{{time_format(item.createdate)}} {{item.msg}} {{item.value}}
							</view>
						</view>
						<view style="margin-top: 10px;">
							<view style="font-size: 12px;color: #666;">收入记录</view>
							<view v-for="(item,index) in diary.incomes" style="font-size: 12px;color:#666;">
								{{time_format(item.createdate)}} {{item.msg}} +{{item.value}}
							</view>
						</view>
					</view>
					<view style="display: flex;margin-top: 10px;justify-content: space-between;">
						<view @click="diary_prev" style="flex:1;padding-right: 5px;">
							<view :style="{opacity:diary.hasprev?'1':'0.5'}" style="width:100%;height:40px;line-height: 40px;text-align: center;background: #f2f2f2;border-radius: 6px;font-size: 14px;">
								向前一天
							</view>
						</view>
						<view @click="diary_next" style="flex:1;padding-left: 5px;">
							<view :style="{opacity:diary.hasnext?'1':'0.5'}" style="width:100%;height:40px;line-height: 40px;text-align: center;background: #f2f2f2;border-radius: 6px;font-size: 14px;">
								向后一天
							</view>
						</view>
					</view>
				</view>
				<view v-else-if="ezwindow_content_type=='rolling_buff'" style="width: 100%;background: #fff;border-radius: 6px;padding:10px;">
					<image v-if="rolling_buff.showcongrats" src="../../static/congrats_light.png" class="rotate-loop" mode="aspectFill" style="width: 500px;height:500px;position: absolute;top:50%;left:50%;margin-left: -250px;margin-top: -250px;"></image>
					<image v-if="rolling_buff.showcongrats" src="../../static/congrats_light.png" class="rotate-loop" mode="aspectFill" style="animation-duration:2s;width: 500px;height:500px;position: absolute;top:50%;left:50%;margin-left: -250px;margin-top: -250px;"></image>
					<view style="padding: 10px;text-align: center;color: #666;">{{rolling_buff.title}}</view>
					<view style="display: flex;height:80px;">
						<view style="font-size: 30px;line-height: 70px;text-align: right;width:80px;">+</view>
						<view style="font-size: 60px;line-height: 70px;flex:1;text-align: center;">{{rolling_buff.current}}%</view>
						<view style="font-size: 30px;line-height: 70px;text-align: right;width:40px;"></view>
					</view>
					<view @click="stop_rolling_buff" class="btn-jelly" hover-class="btn-jelly_hover" style="width: 100%;height:44px;border-radius: 6px;background: #fed43d;color:#333;text-align: center;line-height: 44px;">停止</view>
				</view>
				<view v-else-if="ezwindow_content_type=='openad'" style="width: 100%;">
					<image @click="goto_url(app_info.openad[0].url)" :src="app_info.openad[0].image" mode="widthFix" style="width: 100%;"></image>
				</view>
			</view>
		</ezwindow>
		<ezshare v-if="share_menu_show" @dismiss="share_menu_show=false" @selected="share_menu_selected"></ezshare>
		<view class="left_panel" :class="left_panel_show?'show':''">
			<view class="content">
				<view style="width:100%;height: 50px;" :style="{marginTop:(safeArea.top+30)+'px'}">
					
				</view>
				<!-- <view @click="bindcup_clicked" hover-class="menu_cell_hover" class="menu_cell subtitle">
					<view><image :src="get_icon('cup')"></image></view>
					<view>
						<view>{{owner_info.cupinfo?'Gululu水杯已绑定':'绑定Gululu智能水杯'}}</view>
						<view>绑定后可每天自动浇水除虫，稀有度和丰收度基数增加20%</view>
					</view>
				</view> -->
				<view @click="diary_clicked" hover-class="menu_cell_hover" class="menu_cell subtitle last">
					<view><image :src="get_icon('naozhong')"></image></view>
					<view>
						<view>时光手账</view>
						<view>谢谢你来到这里，这本手账记录着和你有关的一切</view>
					</view>
				</view>
				<view @click="share_menu_show=true;left_panel_show=false;" hover-class="menu_cell_hover" class="menu_cell subtitle first">
					<view><image :src="get_icon('gongneng')"></image></view>
					<view>
						<view>邀请好友</view>
						<view>每一位来看你的好友都将为你增加声望值和阳光币</view>
					</view>
				</view>
				<view @click="extmenu_clicked(item)" v-for="(item,index) in app_info.extmenus"  hover-class="menu_cell_hover" class="menu_cell subtitle" :class="(index==app_info.extmenus.length-1)?'last':''">
					<view><image :src="get_icon(item.icon)"></image></view>
					<view>
						<view>{{item.title}}</view>
						<view>{{item.subtitle}}</view>
					</view>
				</view>
				<button open-type="contact" hover-class="menu_cell_hover" class="clear_button menu_cell first">
					<view><image :src="get_icon('yonghu')"></image></view>
					<view>
						MOJO FAMILY 官方群
					</view>
				</button>
				<button open-type="feedback" hover-class="menu_cell_hover" class="clear_button menu_cell first">
					<view><image :src="get_icon('youjian')"></image></view>
					<view>
						意见反馈
					</view>
				</button>
				<button open-type="openSetting" hover-class="menu_cell_hover" class="clear_button menu_cell">
					<view><image :src="get_icon('shezhi')"></image></view>
					<view>
						设置
					</view>
				</button>
				<view style="width:100%;text-align: center;color:#ccc;font-size: 12px;padding:10px 0px;">版本：{{app_version}}</view>
				<view style="display: flex;font-size: 12px;color:#ccc;padding:0px 10px;">
					<view style="text-align: left;flex:1;color:#ccc;">总计：{{app_info.total_member}}人</view><view style="text-align: right;flex:1;color:#ccc;">在线：{{app_info.today_active}}人</view>
				</view>
			</view>
			<view class="background" @click="hide_left_panel"></view>
		</view>
		<view class="background_section">
			<image src="/static/bg.jpg" mode="scaleToFill" style="width:100%;height: 100%;"></image>
		</view>
		
		<!-- clickable particle item area -->
		<view v-if="user_is_owner" v-for="(item,index) in clickable_effects.items" @click="clickable_effects_item_clicked(item,index)" class="clickable_effects_item" :class="item.aniname" :style="{marginTop:item.y+'px',marginLeft:item.x+'px',animationDelay:item.anidelay,transitionDelay:item.anidelay,transform:item.target_transform}">
			<image mode="scaleToFill" :src="clickable_effects.image" style="width:100%;height:100%;"></image>
		</view>
		<!-- end -->
		<!-- <view v-if="owner_info.headimg!=''" class="foreground_item" style="width:210px;right: 16px;" :style="{top:(safeArea.top+40)+'px'}">
			<image src="/static/tips_addmy.png" mode="widthFix" style="width:210px;"></image>
		</view> -->
		<view v-if="gain_money_progress>0" class="foreground_item" style="width:76px;height:14px;border-radius: 10px;overflow: hidden;top:90px;left: 243px;">
			<view style="width:100%;height:100%;position: absolute;background: #999;"></view>
			<view :style="{width:gain_money_progress+'%'}" style="transition: width .3s;height:100%;position: absolute;background: #fff;"></view>
		</view>
		<form class="foreground_item" style="width:76px;height: 68px;top:114px;left: 243px;" @submit="sun_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image src="/static/sun.png" mode="scaleToFill" style="width:76px;height: 68px;"></image>
			</button>
		</form>
		
		<form v-if="owner_info.headimg!=''&&user_is_owner" class="foreground_item" style="z-index:10;width:50px;height: 50px;top:140px;left: 0px;" @submit="promot_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image src="/static/icon_promot.png" mode="scaleToFill" style="width:50px;height: 50px;"></image>
			</button>
			<view style="font-size: 12px;width:100%;text-align: center;margin-top: -6px;">活动</view>
		</form>
		<form v-if="user_is_owner" class="foreground_item" style="z-index:10;width:50px;height: 50px;top:210px;left: 0px;" @submit="gallery_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image src="/static/icon_gallery.png" mode="scaleToFill" style="width:50px;height: 50px;"></image>
			</button>
			<view style="font-size: 12px;width:100%;text-align: center;margin-top: -6px;">品种</view>
		</form>
		<form class="foreground_item" style="z-index:10;width:50px;height: 50px;top:280px;left: 0px;" @submit="warehouse_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image src="/static/icon_warehouse.png" mode="scaleToFill" style="width:50px;height: 50px;"></image>
			</button>
			<view style="font-size: 12px;width:100%;text-align: center;margin-top: -6px;">仓库</view>
		</form>
		<form  v-if="user_is_owner" class="foreground_item" :class="pig_ani" style="z-index:10;width:50px;height: 50px;top:350px;left: 0px;" @submit="pig_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image :src="get_res('pigs/pig_01.png')" mode="scaleToFill" style="width:50px;height: 50px;"></image>
			</button>
			<view style="font-size: 12px;width:100%;text-align: center;margin-top: -10px;">{{bank.coin}}</view>
		</form>
		<form v-if="user_is_owner" class="foreground_item" style="z-index:10;width:50px;height: 50px;top:410px;left: 0px;" @submit="shop_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image src="/static/icon_shop.png" mode="scaleToFill" style="width:50px;height: 50px;"></image>
			</button>
			<view style="font-size: 12px;width:100%;text-align: center;margin-top: -10px;">商店</view>
		</form>
		<form v-if="owner_info.headimg!=''&&user_is_owner" class="foreground_item" style="z-index:10;width:50px;height: 50px;top:470px;left: 0px;" @submit="rank_clicked" report-submit="true">
			<button form-type="submit" class="clear_button btn-jelly" hover-class="btn-jelly_hover">
				<image src="/static/icon_friends.png" mode="scaleToFill" style="width:50px;height: 50px;"></image>
			</button>
			<view style="font-size: 12px;width:100%;text-align: center;margin-top: -10px;">排名</view>
		</form>
		
		<view v-if="owner_info.headimg!=''" class="user_tl_info" @click="show_left_panel" :style="{top:(safeArea.top+10)+'px'}">
			<view class="user_headimg">
				<image style="width: 100%;height:100%;" mode="aspectFill" :src="owner_info.headimg"></image>
				<image v-if="owner_info.cupinfo" :src="get_icon('cup')" style="width:16px;height: 16px;position: absolute;right: -4px;bottom:0px;"></image>
			</view>
			<view class="user_textinfo">
				<view>{{owner_info.nickname}}</view>
				<view><image src="/static/icon_coin.png" mode="widthFix" style="width: 14px;vertical-align: middle;margin-right: 5px;"></image><text style="padding-top: 2px;">{{owner_info.coin}}</text></view>
				<view><text style="padding-top: 2px;">声望 {{owner_info.prestige}}<text style="color: #666;">{{"（播种稀有加成"+owner_info.prestige/100+"%）"}}</text></text></view>
			</view>
		</view>
		<view v-else-if="!guide_show&&user_is_owner" class="user_tl_info" :style="{top:(safeArea.top+10)+'px'}">
			<button @getuserinfo="get_and_post_user_info" open-type="getUserInfo">授权后领取启动资金</button>
		</view>
		<view v-if="!user_is_owner" class="user_tl_info" style="bottom:200px;">
			<image @click="backhome_clicked" src="../../static/back_board.png" style="width:100px;" mode="widthFix"></image>
		</view>
		<view v-if="effects_cfg" class="effect-section">
			<view class="tips" :class="effects_cfg.tips_class" :style="{top:effects_cfg.options.origin_pos.y+'px',left:effects_cfg.options.origin_pos.x+'px'}">
				<view style="width:20px;height:20px;"><image :src="effects_cfg.image" mode="scaleToFill" style="width:100%;height:100%;"></image></view>
				<view style="height:20px;font-size: 14px;margin-left: 5px;"> {{effects_cfg.name}}</view>
				<view style="height:20px;font-size: 14px;margin-left: 5px;"> x{{effects_cfg.quantity}}</view>
			</view>
			<view v-for="(item,index) in effects_cfg.items" class="item" :class="item.aniname" :style="{marginTop:item.y+'px',marginLeft:item.x+'px',animationDelay:item.anidelay,transitionDelay:item.anidelay,transform:item.target_transform}">
				<image mode="scaleToFill" :src="effects_cfg.image" style="width:100%;height:100%;"></image>
			</view>
		</view>
		<view class="action_section">
			<view v-if="action_ani!=''" class="actionani">
				<image :src="'/static/actions/'+action_ani+'.png'" mode="aspectFit" style="width:100%;height: 100%;"></image>
			</view>
			<view class="actions">
				<view @click="action_clicked(5)" v-if="(!plant||plant.level==-1)&&user_is_owner" class="action_seed btn-jelly" hover-class="btn-jelly_hover">
					<view>
						<image src="/static/actions/action_5.png" mode="widthFix" style="width: 100%;"></image>
					</view>
					<view>播种</view>
				</view>
				<view @click="action_clicked(3)" v-if="plant.level==0&&user_is_owner" class="action_fertilizer btn-jelly" hover-class="btn-jelly_hover">
					<view>
						<image src="/static/actions/action_3.png" mode="widthFix" style="width: 100%;"></image>
					</view>
					<view>施肥</view>
				</view>
				<view @click="action_clicked(1)" v-if="plant.level>0&&plant.level<5&&plant.vworm>0" class="action_deworm btn-jelly" hover-class="btn-jelly_hover">
					<view>
						<image src="/static/actions/action_1.png" mode="widthFix" style="width: 100%;"></image>
					</view>
					<view>除虫</view>
				</view>
				<view @click="action_clicked(2)" v-if="plant.level>0&&plant.level<5&&plant.vthirsty>0" class="action_dethirsty btn-jelly" hover-class="btn-jelly_hover">
					<view>
						<image src="/static/actions/action_2.png" mode="widthFix" style="width: 100%;"></image>
					</view>
					<view>浇水</view>
				</view>
				<view @click="action_clicked(4)" v-if="plant.level>=5" class="action_gain btn-jelly" hover-class="btn-jelly_hover">
					<view>
						<image src="/static/actions/action_4.png" mode="widthFix" style="width: 100%;"></image>
					</view>
					<view>采摘</view>
				</view>
			</view>
			<view v-if="plant.level>=0" class="plant" :class="plant_ani" @click="plant_clicked">
				<image :src="plant_image" mode="scaleToFill" style="width:100%;height: 100%;"></image>
			</view>
			<image v-if="plant.level>=0&&plant.vworm>0" src="../../static/worm.png" mode="widthFix" style="width:50px;position: absolute;left:50%;margin-left: -50px;top:250px;z-index:3;"></image>
			<view v-if="plant.level>=0 && ezwindow_content_type!='rare_rolling'" class="status">
				<view style="color:#999"><text >{{plant.type_cn}}</text></view>
				<view style="color:#999">预计收成：<text >{{plant_calc_vgain}}%</text></view>
				<view style="color:#999"><text v-if="plant.vthirsty>0">缺水</text><text v-if="plant.vworm>0">有虫</text></view>
			</view>
			<!-- <view class="log">
				<view v-for="item,index in grow_log">{{item.time}}{{item.msg}}</view>
			</view> -->
			<view class="pot">
				<image :src="pot_image(pot.type)" mode="scaleToFill" style="width:100%;height: 100%;"></image>
			</view>
			<view v-if="user_is_owner" @click="pot_clicked" class="pot_hitarea"></view>
			<view v-if="plant.level>=0&&plant.level<5" class="progress">
				<view style="width:100%;height:20px;position: relative;overflow: hidden;border-radius: 10px;">
					<view style="width:100%;height:20px;background: #ccc;position: absolute;z-index: 1;">
					</view>
					<view :style="{width:gain_plant_progress*100+'%'}" style="height:20px;background: #5bbd21;position: absolute;z-index: 2;">
					</view>
				</view>
				<view style="display:flex; font-size: 12px;color:#999;">
					<view style="flex:1;">距收获{{gain_plant_distance_cn}}</view>
					<view @click="speedup_clicked" style="color: #3366ff;text-align: right;">加速</view>
				</view>
			</view>
		</view>
		<view v-if="owner_info.headimg!=''" class="visitors_section" :style="{bottom:safeArea?'20px':'0px'}">
			<view style="width:100%;font-size: 12px;text-align: center;">最近访客<text v-if="user_is_owner" @click="clicked_morevisitors" style="color: #3366ff;margin-left: 5px;">查看更多</text></view>
			<view style="width:100%;font-size: 12px;text-align: center;color:#999;">新访客将增加阳光币和声望</view>
			<view style="display: flex;justify-content: center;">
				<view @click="share_menu_show=true" class="visitor_item first">
					<view>
						<image src="../../static/icon_invite.jpg" mode="aspectFill" style="width: 50px;height: 50px;"></image>
					</view>
					<view>立即邀请</view>
					<view></view>
				</view>
				<view v-for="(item,index) in visitors" v-if="index<5" @click="visitor_clicked(item)" class="visitor_item">
					<view>
						<image :src="item.headimg" mode="aspectFill" style="width: 50px;height: 50px;"></image>
					</view>
					<view>{{item.nickname}}</view>
					<view>
						<image :src="'/static/actions/action_'+item.actiontype+'.png'" mode="aspectFill" style="width:100%;height:100%;position: absolute;"></image>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import ezshare from "../../components/ezshare"
	import ezwindow from "../../components/ezwindow"
	import ezlogin from "../../components/ezlogin"
	import poster from "../../components/posters-layer/index"
	import ezguide from "../../components/ezguide"
	
	import utils from "../../common/utils.js"
	import server from "../../common/server.js"
	import api from "../../common/api.js"
	
	var clock_timer;
	var temp_timer;
	var now_time=0;
	var sysinfo=uni.getSystemInfoSync();
	var in_gain_animation=false;
	var scene="";
	var rare_rolling_list=[];
	export default {
		components:{
			ezshare,
			ezwindow,
			ezguide,
			ezlogin,
			poster
		},
		data() {
			return {
				safeArea:{top:0},
				user_is_owner:true,
				guide_show:false,
				guides_cfg:[],
				postersData:{views:[]},
				ezwindow_title:"",
				ezwindow_content_type:"",
				ezwindow_show_close_button:true,
				rare_rolling_result:0,
				ezwindow_show:false,
				left_panel_show:false,
				share_menu_show:false,
				rolling_buff:{
					current:0,
					final:10,
					extra:{}
				},
				effects_cfg:null,
				clickable_effects:null,
				pig_ani:"",
				action_ani:"",
				plant_ani:"bounce",
				gain_money_progress:0,
				gain_plant_distance_cn:"",
				gain_plant_progress:0,
				congrats_new_plant_image:"",
				diary:{
					visitlogs:[]
				},
				detail_seed:{
					type:"pingguo"
				},
				app_info:{
					server_time:0,
					extmenus:[],
					openad:[],
					shareconfigs:[]
				},
				quotation:0,
				warehouselist:[],
				fertilizers:[],
				gallerylist:[],
				bank:{
					coin:0,
					capacity:100,
					growcoin:1
				},
				plant:{
					level:-1
				},
				owner_info:null,
				user_info:null,
				pot:{
					type:"01",
					vrare:"",
					vgain:"",
				},
				car:{
					type:""
				},
				potlist:[],
				visitors:[],
				myvisits:[],
				visitor_window_tab:0,
				myrank:0,
				ranklist:[],
				rank_window_tab:0,
				shoplist:[],
				shop_window_tab:0,
				warehouse_window_tab:0
			}
		},
		computed:{
			owner_cartype:function(){
				if(!this.owner_info)return "";
				return this.owner_info.cartype;
			},
			user_cartype:function(){
				if(!this.user_info)return "";
				return this.user_info.cartype;
			},
			plant_calc_vgain:function(){
				if(!this.plant)return 0;
				var calc_vgain=this.plant.vgain*(1-(this.plant.vthirsty/100))*(1-(this.plant.vworm/100));
				return calc_vgain.toFixed(2);
			},
			plant_rare:function(){
				var prestige=this.owner_info?this.owner_info.prestige:0;
				var vrare=this.plant?this.plant.vrare:0;
				return parseFloat(vrare)+(parseInt(prestige)/100);
			},
			plant_image:function(){
				if(!this.plant)return null;
				return this.plant.level==0?server.get_res('images/plants/seed.png'):server.get_res('images/plants/'+this.plant.type+this.plant.level+'.png');
			},
			app_version:function(){
				return "0.3.5";
			}
		},
		onLoad(options) {
			utils.check_update();
			this.safeArea=sysinfo.safeArea;
			this.statusBarHeight=sysinfo.statusBarHeight;
			
			this.refresh_app_init();
			
			if(options.scene)scene=options.scene;
			else if(options.url){
				utils.navigate_to(options.url);
			}
			
			if(global.user_info){
				this.login_success();
			}
		},
		onShow(){
			this.sync_server_time();
		},
		onShareAppMessage(res) {
			if (res.from === 'button') {// 来自页面内分享按钮
			  
			}
			var share_config=this.app_info.shareconfigs[Math.floor(Math.random()*this.app_info.shareconfigs.length)];
			return {
				title: share_config.title,
				imageUrl:share_config.image,
				content:"",
				desc:"",
				path: '/pages/index/index?scene=mid_'+this.owner_info.id
			}
		},
		methods: {
			pot_image:function(pot_type){
				return server.get_res('images/pots/'+pot_type+'.png');
			},
			login_success:function(){
				var that=this;
				this.owner_info=global.user_info;
				
				if(scene!=""&&!global.is_first){
					var scene_params=utils.get_scene_params(scene);
					var mid=scene_params.mid;
					server.post_api(api.MID2USERID,{openid:global.user_info.openid,mid:mid},{
						success:function(res){
							that.get_user_info(res.result.userid);
						}
					});
				}else{
					this.get_user_info(global.user_info.openid);
				}
			},
			sync_server_time:function(){
				var that=this;
				server.post_api(api.GET_SERVER_TIME,{openid:global.user_info?global.user_info.openid:""},{
					success:function(res){
						that.app_info.server_time=res.result.server_time;
						that.start_timer();
						if(that.owner_info&&!global.is_first)that.get_user_info(that.owner_info.openid);
					},
					fail:function(res){
						uni.reLaunch({
							url:"/pages/index/index"
						})
					}
				});
			},
			refresh_app_init:function(){
				var that=this;
				server.post_api(api.APP_INIT,{},{
					success:function(res){
						that.app_info.server_time=res.result.server_time;
						that.app_info.extmenus=res.result.extmenus;
						that.app_info.shareconfigs=res.result.shareconfigs;
						that.app_info.qrcode_ok=res.result.qrcode_ok;
						that.app_info.openad=res.result.openad;
						that.app_info.activities=res.result.activities;
						that.app_info.total_member=res.result.total_member;
						that.app_info.today_active=res.result.today_active;
						
						that.start_timer();
					}
				});
			},
			guide_login_success:function(){
				this.owner_info=global.user_info;
			},
			guide_arrow_clicked:function(item,arrow_id){
				var that=this;
				if(arrow_id=="speed_up"){
					this.buy_fast_huafei();
				}else if(arrow_id=="action_drink_plant"){
					this.action_clicked(2);
				}else if(arrow_id=="gain_plant"){
					this.gain_plant();
				}else if(arrow_id=="show_warehouse"){
					this.show_warehouse();
				}else if(arrow_id=="sale_warehouse"){
					this.sale(this.warehouselist[0]);
					this.ezwindow_show=false;
				}else if(arrow_id=="sow_plant"){
					this.sow_plant();
					setTimeout(function(){
						that.ezwindow_show=false;
					},6000);
				}else if(arrow_id=="show_bank"){
					this.show_bank();
				}else if(arrow_id=="bank_out"){
					this.bank_out("ad");
				}else if(arrow_id=="stop_rolling"){
					this.stop_rolling_buff();
				}
			},
			guide_finished:function(){
				this.guide_show=false;
			},
			show_guide:function(){
				var that=this;
				var guides_cfg=[
					{
						text:"你好，我是 MOJO FAMILY 的主理人，很高兴认识你。我将会高价回收你收集到的 MOJO 系列公仔，希望你尽可能多收集一些稀有款式。"
					},
					{
						text:"我刚刚送了你一棵"+that.plant.type_cn+"，不过，它现在有点缺水，请你给它浇点水吧。"
					},
					{
						arrow:{
							x:sysinfo.screenWidth/2+58,
							y:sysinfo.screenHeight-410,
							rotate:-35,
							id:"action_drink_plant"
						}
					},
					{
						text:"谢谢你，它现在水分充足了。在植物的生长过程中，请常回来看看它是否健康吧。请你也要多多喝水，人类每天要喝约1600ml的水才健康哦。",
						delay:1000
					},
					{
						text:"它还需要一点时间才能成熟，不过，我们可以加速它的生长周期~",
						arrow:{
							x:sysinfo.screenWidth/2+38,
							y:sysinfo.screenHeight-240,
							rotate:35,
							id:"speed_up"
						}
					},
					{
						text:"怎么样，加速是不是很厉害？现在我们可以采摘收集成果了哦~",
						delay:1000
					},
					{
						arrow:{
							x:sysinfo.screenWidth/2+58,
							y:sysinfo.screenHeight-410,
							rotate:-35,
							id:"gain_plant"
						}
					},
					{
						text:"哇喔，第一次就收获了好多呢，你真的很有天赋！现在点击仓库，看看我们的成果吧~",
						delay:2000
					},
					{
						arrow:{
							x:10,
							y:230,
							rotate:35,
							id:"show_warehouse"
						}
					},
					{
						arrow:{
							x:sysinfo.screenWidth-80,
							y:this.safeArea.top+80,
							rotate:-35,
							id:"sale_warehouse"
						}
					},
					{
						text:"太棒了！你的珍稀公仔一经推出，就火爆市场，大家迫不及待的等你种出下一种水果呢，请尽快播种一颗新的种子吧。"
					},
					{
						arrow:{
							x:sysinfo.screenWidth/2+58,
							y:sysinfo.screenHeight-410,
							rotate:-35,
							id:"sow_plant"
						}
					},
					{
						text:"你是个天才！这就是MOJO FAMILY 农场，用一样的种子可以种出各种不同的水果。",
						delay:6000
					},
					{
						text:"植物生长在过程中会通过光合作用不断的产生阳光币，请及时收取至储蓄罐里哦。"
					},
					{
						arrow:{
							x:10,
							y:300,
							rotate:35,
							id:"show_bank"
						}
					},
					{
						text:"现在我们来加倍提取储蓄罐里的阳光币吧！"
					},
					{
						arrow:{
							x:sysinfo.screenWidth/2+50,
							y:400,
							rotate:-35,
							id:"bank_out"
						}
					},
					{
						arrow:{
							x:sysinfo.screenWidth/2,
							y:200,
							rotate:135,
							id:"stop_rolling"
						}
					},
					{
						text:"你真是太棒了！对了，我还不知道你的名字。点击继续，选择[允许]就告诉我你的名字，我会再送给你一些启动资金哦。",
						delay:3000,
						login:true
					},
					{
						text:"接下来这个小展柜就交给你了，我等你的好消息哦！",
					}
				];
				
				that.guides_cfg=guides_cfg;
				that.guide_show=true;
			},
			get_user_info:function(userid){
				var that=this;
				
				uni.showLoading({mask:true});
				server.post_api(api.GET_USER_INFO,{openid:global.user_info.openid,userid:userid},{
					success:function(res){
						that.pot=res.result.pot;
						that.owner_info=res.result.user_info;
						that.bank=res.result.bank;
						that.plant=res.result.plant;
						
						that.visitor_window_tab=0;
						that.myvisits=[];
						that.visitors=res.result.visitors;
						
						that.user_is_owner=that.owner_info.openid==global.user_info.openid;
						if(that.user_is_owner){
							global.user_info=that.owner_info;
							that.user_info=global.user_info;
						}
						if(global.is_first){
							that.show_guide();
						}else if(!that.guide_show&&that.user_is_owner&&that.app_info.openad&&that.app_info.openad.length>0&&!that.app_info.openad_wasshow){
							that.app_info.openad_wasshow=true;
							that.show_ezwindow("活动","openad");
						}
						
						that.calc_gain_plant_distance_cn();
						that.calc_gain_plant_progress();
					}
				});
			},
			show_ezwindow:function(title,content_type,show_close_button=true){
				this.ezwindow_show=true;
				this.ezwindow_title=title;
				this.ezwindow_content_type=content_type;
				this.ezwindow_show_close_button=show_close_button;
			},
			onSuccessCreatePosters:function(res){
				uni.hideLoading();
				
				// uni.saveImageToPhotosAlbum({
				// 	filePath:res.path
				// });
				uni.previewImage({
					urls:[res.path],
					current:res.path
				})
			},
			onPostersError:function(){
				uni.hideLoading();
			},
			makePoster:function(){
				uni.showLoading({
					title:"稍等片刻哦"
				});
				var postersData={
					timestamp:utils.get_time(),
					width: 750,
					height: 1334,
					background: '#ffffff',
					views:[
						{
							type:"image",
							width:750,
							height:1334,
							left:0,
							top:0,
							url:"/static/bg.jpg"
						},
						{
							type:"image",
							width:152,
							height:136,
							left:486,
							top:228,
							url:"/static/sun.png"
						},
						{
							type:"image",
							width:400,
							height:400,
							left:175,
							top:670,
							url:server.cdn2host(this.get_res("pots/"+this.pot.type+".png"))
						}
					]
				}
				
				if(this.plant&&this.plant.level>=0){
					postersData.views.push({
							type:"image",
							width:700,
							height:700,
							left:-25,
							top:228,
							url:server.cdn2host(this.plant_image)
						});
				}
				
				postersData.views.push({
						type:"image",
						width:750,
						height:1334,
						left:0,
						top:0,
						url:"/static/posters/frame.png"
					},
					{
							type:"image",
							width:750,
							height:1334,
							left:0,
							top:0,
							url:"/static/posters/text1.png"
						},
					{
							type:"text",
							width:750,
							left:710,
							top:30,
							textAlign:"right",
							fontSize:36,
							color:"#333",
							content:this.owner_info.nickname+"的MOJO FAMILY 农场"
						},
						{
							type:"image",
							width:200,
							height:200,
							left:40,
							top:1111,
							url:server.API_HOST+"/get_qrcode.php?page=pages/index/index&scene=mid_"+this.owner_info.id
						});
						
				this.postersData=postersData;
			},
			share_menu_selected:function(index){
				if(index==1){
					this.makePoster();
				}
			},
			goto_url:function(url){
				utils.navigate_to(url);
			},
			get_res:function(path){
				return server.get_res("images/"+path);
			},
			get_icon:function(icon_name){
				return this.get_res("icons/"+icon_name+".png");
			},
			calc_gain_plant_progress:function(){
				var that=this;
				if(!that.plant||that.plant.level<0||that.plant.level>=5)return;
				
				var time_dis=(this.plant.seedtime+this.plant.growduration)-(this.app_info.server_time+now_time);
				if(time_dis>this.plant.growduration)time_dis=this.plant.growduration;
				
				this.gain_plant_progress=(this.plant.growduration-time_dis)/this.plant.growduration;
				this.plant.level=Math.min(5,Math.floor((this.plant.growduration-time_dis)/this.plant.stepduration));
			},
			calc_gain_plant_distance_cn:function(){
				var that=this;
				if(!that.plant||that.plant.level<0||that.plant.level>=5)return;
				
				var time_dis=(this.plant.seedtime+this.plant.growduration)-(this.app_info.server_time+now_time);
				if(time_dis>this.plant.growduration)time_dis=this.plant.growduration;
				
				var hour=Math.floor(time_dis/3600);
				var hour_str="";
				if(hour>=10)hour_str=hour;
				else hour_str="0"+hour;
				
				var minute=Math.floor((time_dis%3600)/60);
				var minute_str="";
				if(minute>=10)minute_str=minute;
				else minute_str="0"+minute;
				
				var second=Math.floor((time_dis%3600%60));
				var second_str="";
				if(second>=10)second_str=second;
				else second_str="0"+second;
				
				this.gain_plant_distance_cn=hour_str+":"+minute_str+":"+second_str;
			},
			time_format:function(time){
				return utils.date_format(new Date(parseInt(time)*1000),"hh:mm:ss");
			},
			time_distance:function(time){
				return utils.get_time_distance(utils.date_format(new Date(parseInt(time)*1000),"yyyy-MM-dd hh:mm:ss"));
			},
			start_timer:function(){
				var that=this;
				if(clock_timer)clearInterval(clock_timer);
				now_time=0;
				function countdown(){
					if(!that.plant||that.plant.level<0||that.plant.level>=5)return;
					if(now_time%2==0&&Math.random()*10000/10000<.2)that.add_clickable_coin();
					// console.log("tick:",new Date().getTime());
					that.calc_gain_plant_distance_cn();
					that.calc_gain_plant_progress();
					now_time+=1;
				}
				countdown();
				clock_timer=setInterval(countdown,1000);
			},
			show_left_panel:function(){
				this.left_panel_show=true;
			},
			hide_left_panel:function(){
				this.left_panel_show=false;
			},
			sun_clicked:function(e){
				var that=this;
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				// if(!this.user_is_owner)return console.log("只有在自己家有效");
				// if(!that.plant||that.plant.level<0||that.plant.level>=5)return console.log("不在植物生长区间");
				// 
				// that.gain_money_progress+=100/8;
				// if(that.gain_money_progress>=100){
				// 	that.gain_money_progress=0;
				// 	that.add_clickable_coin();
				// }
			},
			gain_money:function(){
				var that=this;
				
				if(parseInt(that.bank.coin)>=parseInt(that.bank.capacity))return utils.show_alert("储蓄罐已满，存不进去了哦~");
				uni.showLoading({mask:true});
				server.post_api(api.GAIN_COIN,{openid:global.user_info.openid,coin:that.clickable_effects.items.length,from:"sun"},{
					success:function(res){
						function hide_effect(){
							that.clickable_effects=null;
							in_gain_animation=false;
						}
						//小猪效果
						that.pig_ani="pig_jelly";
						setTimeout(function(){
							that.pig_ani="";
							hide_effect();
						},1000);
						utils.number_grow(that.bank.coin,parseInt(that.bank.coin)+parseInt(res.result.coin),function(value){
							that.bank.coin=value;
							if(that.bank.coin>that.bank.capacity)that.bank.coin=that.bank.capacity;
						})
						//金币聚拢效果
						var options=that.clickable_effects.options;
						that.clickable_effects.tips_class="fadeout";
						var length=that.clickable_effects.items.length;
						for(var i=0;i<length;i++){
							var item=that.clickable_effects.items[i];
							item.x=options.target_pos.x;
							item.y=options.target_pos.y;
							item.target_transform=options.target_transform;
							utils.play_sfx("bubble",parseFloat(item.anidelay)*1000);
						}
						in_gain_animation=true;
					}
				})
			},
			clickable_effects_item_clicked:function(item,index){
				var that=this;
				
				that.gain_money();
			},
			add_clickable_coin:function(){
				var that=this;
				if(in_gain_animation)return console.log("收集动画中无法生成阳光币");
				if(this.guide_show)return console.log("新手引导中无法生成阳光币");
				if(!that.clickable_effects||that.clickable_effects.items.length<5){
					utils.play_sfx("sun");
					that.show_gain_effect("/static/icon_coin.png","阳光",1,{clickable:true,origin_pos:{x:sysinfo.screenWidth/2,y:sysinfo.screenHeight/3},random_radius:100,target_pos:{x:10,y:350}});
				}else{
					console.log("画面里的阳光币已经够多了");
				}
			},
			show_gain_effect:function(image,name,quantity,options){
				if(!options)options={};
				
				if(!options.origin_pos)options.origin_pos={x:sysinfo.screenWidth/2,y:sysinfo.screenHeight/2};
				if(!options.random_radius)options.random_radius=100;
				if(!options.target_pos)options.target_pos={x:10,y:10};
				if(!options.target_transform)options.target_transform="scale(0)";
				if(!options.clickable)options.clickable=false;
				
				var that=this;
				var effects_cfg={
					tips_class:"fadein",
					name:name,
					image:image,
					quantity:quantity,
					items:[],
					options:options
					};
				
				//特效不要大于10个item
				var length=Math.min(quantity,10);
				for(var i=0;i<length;i++){
					effects_cfg.items.push({
									x:options.origin_pos.x+utils.get_wave()*Math.random()*options.random_radius,
									y:options.origin_pos.y+utils.get_wave()*Math.random()*options.random_radius,
									aniname:"in",
									anidelay:Math.random()*5/10+"s"
								});
				}
				
				function hide_effect(){
					that.effects_cfg=null;
					if(options.complete)options.complete();
				}
				function show_collect_effect(){
					effects_cfg.tips_class="fadeout";
					for(var i=0;i<effects_cfg.items.length;i++){
						var item=effects_cfg.items[i];
						item.x=options.target_pos.x;
						item.y=options.target_pos.y;
						item.target_transform=options.target_transform;
					}
					setTimeout(hide_effect,1500);
				}
				
				if(!options.clickable){
					this.effects_cfg=effects_cfg;
					setTimeout(show_collect_effect,1500);
				}
				else {
					if(this.clickable_effects&&this.clickable_effects.items.length>0)effects_cfg.items=this.clickable_effects.items.concat(effects_cfg.items);
					effects_cfg.items=effects_cfg.items.splice(0,5);
					this.clickable_effects=effects_cfg;
				}
			},
			congrats_new_plant:function(){
				this.congrats_new_plant_image=this.get_res("plants/"+this.plant.type+".png");
				this.show_ezwindow("本次播种："+this.plant.type_cn,"congrats_new_plant");
			},
			bindcup_clicked:function(){
				this.hide_left_panel();
				this.show_ezwindow("绑定Gululu智能水杯","bindcup");
			},
			diary_clicked:function(){
				var that=this;
				that.hide_left_panel();
				
				that.show_ezwindow("时光手账","diary");
				that.get_diary(utils.date_format(new Date(),"yyyy-MM-dd"));
			},
			diary_prev:function(){
				var that=this;
				if(!that.diary.hasprev)return;
				that.get_diary(utils.date_format(new Date((that.diary.date-86400)*1000),"yyyy-MM-dd"));
			},
			diary_next:function(){
				var that=this;
				if(!that.diary.hasnext)return;
				that.get_diary(utils.date_format(new Date((that.diary.date+86400)*1000),"yyyy-MM-dd"));
			},
			get_diary:function(date_str){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.GET_DIARY,{openid:global.user_info.openid,date:date_str},{
					success:function(res){
						that.diary=res.result.diary;
					}
				});
			},
			extmenu_clicked:function(menu_item){
				this.hide_left_panel();
				utils.navigate_to(menu_item.url);
			},
			pot_clicked:function(e){
				this.show_shop(0);
			},
			pig_clicked:function(e){
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				this.show_bank();
			},
			show_bank:function(){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.GET_BANK_INFO,{openid:global.user_info.openid},{
					success:function(res){
						uni.hideLoading();
						that.bank=res.result.bank_info;
						that.show_ezwindow("小猪储蓄罐","bank");
					}
				});
			},
			get_rank:function(isglobal){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.GET_RANK,{openid:global.user_info.openid,isglobal:isglobal},{
					success:function(res){
						uni.hideLoading();
						that.myrank=res.result.myrank;
						that.ranklist=res.result.ranklist;
					}
				});
			},
			rank_window_tab_clicked:function(index){
				var that=this;
				that.rank_window_tab=index;
				
				that.get_rank(index?false:true);
			},
			rank_clicked:function(e){
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				var that=this;
				that.get_rank(that.rank_window_tab?false:true);
				that.show_ezwindow("","rank");
			},
			promot_clicked:function(e){
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				this.show_ezwindow("活动","promot");
			},
			gallery_item_clicked:function(item){
				var that=this;
				
				that.detail_seed=item;
				that.show_ezwindow(item.gotit?item.type_cn:"未解锁","seed_detail");
			},
			gallery_clicked:function(e){
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.GET_GALLERY,{openid:global.user_info.openid},{
					success:function(res){
						uni.hideLoading();
						that.gallerylist=res.result.seeds;
						that.show_ezwindow("品种","gallery");
					}
				});
			},
			show_warehouse:function(){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.GET_WAREHOUSE,{openid:global.user_info.openid},{
					success:function(res){
						uni.hideLoading();
						
						var warehouselist=res.result.warehouse;
						var length=warehouselist.length;
						for(var i=0;i<length;i++){
							warehouselist[i].sale_quantity=warehouselist[i].quantity;
						}
						
						that.warehouselist=warehouselist;
						that.quotation=res.result.quotation;
						that.show_ezwindow("","warehouse");
						that.$forceUpdate();
					}
				});
			},
			warehouse_window_tab_clicked:function(tab_index){
				var that=this;
				that.warehouse_window_tab=tab_index;
			},
			warehouse_clicked:function(e){
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				if(this.user_is_owner){
					this.show_warehouse();
				}else{
					this.show_transfer();
				}
			},
			show_transfer:function(){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.GET_WAREHOUSE,{openid:global.user_info.openid},{
					success:function(res){
						uni.hideLoading();
						
						var warehouselist=res.result.warehouse;
						var length=warehouselist.length;
						for(var i=0;i<length;i++){
							warehouselist[i].sale_quantity=0;
						}
						
						that.warehouselist=warehouselist;
						that.show_ezwindow(">> 仓库转运 >>","transfer");
						that.$forceUpdate();
					}
				});
			},
			transfer_clicked:function(item){
				var that=this;
				if(!item.sale_quantity)return utils.show_tips("未选择数量");
				uni.showModal({
					title:"确定送出吗？",
					content:"送给 "+that.owner_info.nickname+" "+item.type_cn+"x"+item.sale_quantity,
					success:function(res){
						if (res.confirm) {
							that.transfer(item);
						}
					}
				})
			},
			transfer:function(item){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.TRANSFER,{openid:global.user_info.openid,userid:that.owner_info.openid,type:item.type,quantity:item.sale_quantity},{
					success:function(res){
						uni.showToast({title:res.msg});
						item.quantity=item.quantity-item.sale_quantity;
						item.sale_quantity=0;
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			shop_item_clicked:function(item){
				var that=this;
				if(item.gotit){
					if(item.category==1&&item.type!=that.pot.type){
						uni.showModal({
							content:"确定使用它吗？",
							success:function(res){
								if(res.confirm){
									that.change_pot(item.type);
									that.ezwindow_show=false;
								}
							}
						})
					}else if(item.category==2&&item.type!=that.owner_info.cartype){
						uni.showModal({
							content:"确定使用它吗？",
							success:function(res){
								if(res.confirm){
									that.change_car(item.type);
									that.ezwindow_show=false;
								}
							}
						})
					}
				}else{
					uni.showModal({
						content:"确定购买此商品吗？",
						success:function(res){
							if(res.confirm){
								that.shopping(item);
							}
						}
					})
				}
			},
			shopping:function(shopitem){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.SHOPPING,{openid:global.user_info.openid,shopid:shopitem.id},{
					success:function(res){
						uni.showToast({title:"已购买"});
						that.show_shop(that.shop_window_tab);
						
						utils.number_grow(that.owner_info.coin,parseInt(that.owner_info.coin)+parseInt(res.result.coin),function(value){
							that.owner_info.coin=value;
						})
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			show_shop:function(tab_index){
				var that=this;
				
				that.shop_window_tab=tab_index;
				var category=0;
				if(tab_index==0){
					category=1;
				}else if(tab_index==2){
					category=2;
				};
				
				uni.showLoading({mask:true});
				server.post_api(api.GET_SHOP,{openid:global.user_info.openid,category:category},{
					success:function(res){
						var shoplist=res.result.shop;
						var length=shoplist.length;
						for(var i=0;i<length;i++){
							var shop_item=shoplist[i];
							if(shop_item.category==1){
								shop_item.image=that.get_res("/pots/"+shop_item.type+".png");
								if(that.pot&&that.pot.type==shop_item.type)shop_item.inuse=true;
							}else if(shop_item.category==2){
								shop_item.image=that.get_res("/cars/"+shop_item.type+".png");
								if(that.owner_info.cartype==shop_item.type)shop_item.inuse=true;
							}
							if(shop_item.price==0)shop_item.gotit=true;
						}
						
						that.shoplist=shoplist;
						that.show_ezwindow("","shop");
					}
				});
			},
			shop_clicked:function(e){
				if(e.detail.formId)utils.report_formid(e.detail.formId);
				
				this.show_shop(this.shop_window_tab);
			},
			plant_clicked:function(){
				var that=this;
				if(this.plant.level>=5){
					this.gain_plant();
				}else{
					if(this.plant_ani=="bounce"){
						this.plant_ani="swing";
						setTimeout(function(){
							that.plant_ani="bounce";
						},1000);
						this.get_user_info(this.owner_info.openid);
					}
				}
			},
			backhome_clicked:function(){
				this.get_user_info(global.user_info.openid);
			},
			action_clicked:function(action_type){
				var that=this;
				if(action_type==1){//除虫
					utils.play_sfx("action_worm");
					this.action_ani="action_1_ani";
					setTimeout(function(){
						that.action_ani="";
					},1000);
					uni.showLoading({mask:true});
					server.post_api(api.ACTION_TO_PLANT,{openid:global.user_info.openid,userid:that.owner_info.openid,actiontype:1},{
						success:function(res){
							that.plant=res.result.plant;
						},
						fail:function(res){
							utils.show_alert(res.msg);
						}
					});
				}else if(action_type==2){//浇水
					utils.play_sfx("action_thirsty");
					this.action_ani="action_2_ani";
					setTimeout(function(){
						that.action_ani="";
					},1000);
					uni.showLoading({mask:true});
					server.post_api(api.ACTION_TO_PLANT,{openid:global.user_info.openid,userid:that.owner_info.openid,actiontype:2},{
						success:function(res){
							that.plant=res.result.plant;
						},
						fail:function(res){
							utils.show_alert(res.msg);
						}
					});
				}else if(action_type==3){//施肥
					uni.showLoading({mask:true});
					server.post_api(api.GET_FERTILIZERS,{openid:global.user_info.openid},{
						success:function(res){
							that.fertilizers=res.result.fertilizers;
							that.show_ezwindow("添加肥料","fertilizer");
						}
					});
				}else if(action_type==4){//收获
					this.gain_plant();
				}else if(action_type==5){//播种
					this.sow_plant();
				}
			},
			sow_plant:function(){
				var that=this;
				if(this.owner_info.openid==global.user_info.openid){
					uni.showLoading({mask:true});
					server.post_api(api.SOW,{openid:global.user_info.openid},{
						success:function(res){
							that.plant=res.result.plant;
							that.sync_server_time();
							that.show_rare_rolling_window();
						}
					});
				}
			},
			speedup_clicked:function(){
				var that=this;
				if(that.user_is_owner){
					this.show_ezwindow("加速","speedup");
				}else{
					uni.showModal({
						content:"确定帮"+this.owner_info.nickname+"加速吗？每天可帮好友加速一次。",
						success:function(res){
							if(res.confirm){
								that.buy_fast_huafei();
							}
						}
					})
				}
			},
			speedup_ad_clicked:function(){
				this.ezwindow_show=false;
				this.buy_fast_huafei();
			},
			speedup_invite_clicked:function(){
				this.share_menu_show=true;
			},
			speedup_coin_clicked:function(){
				this.buy_fast_huafei(true);
			},
			buy_fast_huafei:function(isbuy=false){
				var that=this;
				// this.plant.seedtime-=1800;
				uni.showLoading({mask:true});
				server.post_api(api.SPEEDUP,{openid:global.user_info.openid,userid:this.owner_info.openid,isbuy:isbuy},{
					success:function(res){
						uni.showToast({title:"加速成功"});
						
						uni.hideLoading();
						that.plant.seedtime=res.result.seedtime;
						
						that.calc_gain_plant_distance_cn();
						that.calc_gain_plant_progress();
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			clicked_morevisitors:function(){
				this.show_ezwindow("","visitors");
			},
			shop_window_tab_clicked:function(index){
				var that=this;
				
				this.show_shop(index);
			},
			visitor_window_tab_clicked:function(index){
				var that=this;
				this.visitor_window_tab=index;
				
				function get_visitors(page=0){
					uni.showLoading({mask:true});
					server.post_api(api.GET_VISITORS,{openid:that.owner_info.openid,action:index?"myvisits":"",page:page},{
						success:function(res){
							if(index){
								that.myvisits=res.result.myvisits;
							}else{
								that.visitors=res.result.visitors;
							}
						}
					});
				}
				
				if(index){
					if(that.myvisits.length<=0)get_visitors();
				}else{
					if(that.visitors.length<=0)get_visitors();
				}
			},
			visitor_clicked:function(item){
				var that=this;
				if(item.ownerid==global.user_info.openid){
					that.ezwindow_show=false;
					return this.backhome_clicked();
				}
				uni.showModal({
					title:"温馨提醒",
					content:"确定访问"+item.nickname+"吗？",
					success:function(res){
						if(res.confirm){
							that.ezwindow_show=false;
							that.get_user_info(item.ownerid);
						}
					}
				});
			},
			stop_rolling_buff:function(){
				clearInterval(temp_timer);
				var that=this;
				if(that.rolling_buff.showcongrats)return;
				utils.play_sfx("sow_seed");
				that.rolling_buff.current=that.rolling_buff.final;
				that.rolling_buff.showcongrats=true;
				
				if(that.rolling_buff.fincall)that[that.rolling_buff.fincall]();
			},
			show_rolling_buff:function(finbuff,title="",fincall=null){
				var that=this;
				
				that.show_ezwindow("","rolling_buff",false);
				
				var rolling_buff={
					title:title,
					current:100,
					final:finbuff,
					fincall:fincall,
					showcongrats:false
				};
				clearInterval(temp_timer);
				function startRolling(){
					temp_timer=setInterval(function(){
							utils.play_sfx("bubble");
							rolling_buff.current=Math.floor(Math.random()*95+5);
							that.rolling_buff=rolling_buff;
						},100);
				}
				startRolling();
			},
			bankout_clicked:function(){
				var that=this;
				var bankout_coin=parseInt(this.bank.coin);
				if(bankout_coin<=0)return;
				
				this.bank_out();
			},
			bankout_ad_clicked:function(){
				var that=this;
				var bankout_coin=parseInt(this.bank.coin);
				if(bankout_coin<=0)return;
				
				this.bank_out("ad");
			},
			bank_out:function(from=""){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.BANK_OUT,{openid:global.user_info.openid,from:from},{
					success:function(res){
						if(from=="ad")that.show_rolling_buff(res.result.buff,"提出增加","bank_out_buff_fin_call");
						else {
							that.bank_draw_money(res.result.coin);
							setTimeout(function(){
								that.ezwindow_show=false;
							},1000);
						}
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			bank_out_buff_fin_call:function(){
				var that=this;
				var draw_coin=Math.ceil(parseInt(that.bank.coin)*(1+that.rolling_buff.final/100));
				that.bank_draw_money(draw_coin);
				setTimeout(function(){
					that.ezwindow_show=false;
				},4000);
			},
			bank_draw_money:function(money){
				var that=this;
				utils.play_sfx("gain_coin");
				that.show_gain_effect("/static/icon_coin.png","阳光",money,{origin_pos:{x:sysinfo.screenWidth/2,y:250},random_radius:50,target_pos:{x:10,y:10}});
				utils.number_grow(money,0,function(value){
					that.bank.coin=value;
				})
				setTimeout(function(){
					utils.number_grow(that.owner_info.coin,parseInt(that.owner_info.coin)+parseInt(money),function(value){
						that.owner_info.coin=value;
					});
				},1800);
			},
			sale_decrease_clicked:function(item){
				if(item.sale_quantity>0){
					item.sale_quantity-=1;
				}else{
					item.sale_quantity=item.quantity;
				}
				this.$forceUpdate();
			},
			sale_increase_clicked:function(item){
				if(item.sale_quantity<item.quantity){
					item.sale_quantity+=1;
				}else{
					item.sale_quantity=0;
				}
				this.$forceUpdate();
			},
			sale_clicked:function(item){
				var that=this;
				if(!item.sale_quantity)return;
				uni.showModal({
					title:"确定售出吗？",
					content:item.type_cn+"x"+item.sale_quantity+"，出售总价为"+Math.ceil(item.sale_quantity*item.unitprice*(1+this.quotation/100))+"阳光币",
					success:function(res){
						if (res.confirm) {
							that.sale(item);
						}
					}
				})
			},
			sale:function(item){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.SALE,{openid:global.user_info.openid,type:item.type,quantity:item.sale_quantity},{
					success:function(res){
						uni.showToast({title:res.msg});
						item.quantity=item.quantity-item.sale_quantity;
						item.sale_quantity=0;
						
						utils.play_sfx("gain_coin");
						
						that.show_gain_effect("/static/icon_coin.png","阳光",res.result.coin,{origin_pos:{x:sysinfo.screenWidth/2,y:250},random_radius:50,target_pos:{x:10,y:10}});
						setTimeout(function(){
							utils.number_grow(that.owner_info.coin,parseInt(that.owner_info.coin)+parseInt(res.result.coin),function(value){
								that.owner_info.coin=value;
							});
						},1800);
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			gain_plant:function(){
				var that=this;
				
				uni.showLoading({mask:true});
				server.post_api(api.GAIN_PLANT,{openid:global.user_info.openid,userid:that.owner_info.openid},{
					success:function(res){
						utils.play_sfx("gain_plant");
						that.show_gain_effect(server.get_res("/images/plants/"+that.plant.type+".png"),that.plant.type_cn,res.result.gain_quantity);
						
						that.plant=res.result.plant;
						if(res.result.plant.quantity<=0)that.plant.level=-1;
					},
					fail:function(res){
						that.get_user_info(that.owner_info.openid);
						utils.show_alert(res.msg);
					}
				});
			},
			fertilizer_clicked:function(item){
				var that=this;
				uni.showModal({
					title:"确定购买吗？",
					content:item.type_cn+"，需要消费"+item.price+"阳光币",
					success:function(res){
						if (res.confirm) {
							that.buy_fertilizer(item);
						}
					}
				})
			},
			buy_fertilizer:function(item){
				var that=this;
				
				uni.showLoading({mask:true});
				server.post_api(api.BUY_FERTILIZER,{openid:global.user_info.openid,type:item.type},{
					success:function(res){
						that.ezwindow_show=false;
						
						if(item.type=="03"){
							that.plant=res.result.plant;
							that.show_rare_rolling_window();
						}else if(item.type=="01"){
							that.show_rolling_buff(res.result.buff,"产量增加","buy_fertilizer_buff_fin_call");
						}
						utils.number_grow(that.owner_info.coin,parseInt(that.owner_info.coin)-parseInt(item.price),function(value){
							that.owner_info.coin=value;
						});
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			buy_fertilizer_buff_fin_call:function(){
				var that=this;
				that.plant.vgain=parseFloat(that.plant.vgain)+that.rolling_buff.final;
				setTimeout(function(){
					that.ezwindow_show=false;
				},4000);
			},
			rolling_item_gotit:function(index){
				var that=this;
				if(rare_rolling_list[index])return rare_rolling_list[index].gotit;
				else return false;
			},
			rolling_item_image:function(index){
				var that=this;
				if(rare_rolling_list[index])return that.get_res("plants/"+rare_rolling_list[index].type+".png");
				return "";
			},
			show_rare_rolling_window:function(){
				var that=this;
				this.show_ezwindow("...种子转转转...","rare_rolling",false);
				
				var seeds=[];
				
				function getRandomSeeds(count,rare){
					var target_seeds=[];
					var length=that.gallerylist.length;
					for(var i=0;i<length;i++){
						if(that.gallerylist[i].rare==rare)target_seeds.push(that.gallerylist[i]);
					}
					
					var results=[];
					for(var i=0;i<count;i++){
						var item=target_seeds.splice(Math.floor(Math.random()*target_seeds.length),1);
						results.push(item[0]);
					}
					
					return results;
				}
				function checkTargetSeed(type,arr){
					var target_seed;
					var length=that.gallerylist.length;
					for(var i=0;i<length;i++){
						if(that.gallerylist[i].type==type){
							target_seed=that.gallerylist[i];
							break;
						}
					}
					
					var alread_in=-1;
					var arr_length=arr.length;
					for(var i=0;i<arr_length;i++){
						if(arr[i].type==target_seed.type){
							alread_in=i;
							break;
						}
					}
					
					if(alread_in>=0){
						return alread_in;
					}else{
						var t_index=Math.floor(Math.random()*arr.length);
						arr[t_index]=target_seed;
						return t_index;
					}
				}
				function gallerylist_ready(){
					var rare=Math.min(that.plant.vrare/100,1);
					var normal_count=Math.min(Math.floor(8*(1-rare)),5);
					var rare_count=Math.max(Math.ceil(8*rare),3);
					
					var normal_seeds=getRandomSeeds(normal_count,0);
					var rare_seeds=getRandomSeeds(rare_count,1);
					
					var _rare_rolling_list=normal_seeds.concat(rare_seeds);
					_rare_rolling_list.sort(function() {
					    return .5 - Math.random();
					});
					
					var target_index=checkTargetSeed(that.plant.type,_rare_rolling_list);
					
					rare_rolling_list=_rare_rolling_list;
					
					var time_count=32+target_index;
					var time_index=0;
					clearInterval(temp_timer);
					temp_timer=setInterval(function(){
						utils.play_sfx("bubble");
						time_index+=1;
						that.rare_rolling_result=time_index%8;
						if(time_index>=time_count){
							clearInterval(temp_timer);
							utils.play_sfx("sow_seed");
							setTimeout(that.congrats_new_plant,1000);
						}
					},100);
				}
				
				if(that.gallerylist.length>0){
					gallerylist_ready();
				}else{
					server.post_api(api.GET_GALLERY,{openid:global.user_info.openid},{
						success:function(res){
							that.gallerylist=res.result.seeds;
							gallerylist_ready();
						}
					});
				}
			},
			change_car:function(cartype){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.CHANGE_CAR,{openid:global.user_info.openid,cartype:cartype},{
					success:function(res){
						that.owner_info.cartype=cartype;
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			change_pot:function(pottype){
				var that=this;
				uni.showLoading({mask:true});
				server.post_api(api.CHANGE_POT,{openid:global.user_info.openid,pottype:pottype},{
					success:function(res){
						that.pot=res.result.pot;
					},
					fail:function(res){
						utils.show_alert(res.msg);
					}
				});
			},
			get_and_post_user_info:function(e){
				var that=this;
				var user_info=e.detail.userInfo;
				if(user_info){
					user_info.openid=global.user_info.openid;
					server.post_api(api.POST_USER_INFO,user_info,{
						success:function(res){
							if(res.err==0){
								if(res.result.user_info){
									global.is_first=false;
									global.user_info=res.result.user_info;
									that.get_user_info(global.user_info.openid);
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
					utils.show_alert("授权已取消");
				}
			}
		}
	}
</script>

<style>
	@import url("../../styles/keyframes.css");
	
	.rotate-loop
	{
		animation:ani-rotate 4s infinite;
		animation-fill-mode: both;
		animation-timing-function: linear;
	}
	.background_section
	{
		position: absolute;
		z-index: 1;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
	}
	.foreground_item
	{
		position: absolute;
		z-index: 8;
	}
	.user_tl_info
	{
		position: absolute;
		z-index: 11;
		left: 10px;
		display: flex;
	}
	.user_textinfo>view:nth-child(1)
	{
		font-size: 14px;
	}
	.user_textinfo>view:nth-child(2)
	{
		font-size: 12px;
		line-height: 20px;
		margin-top: -2px;
	}
	.user_textinfo>view:nth-child(3)
	{
		font-size: 12px;
		line-height: 20px;
		margin-top: -5px;
	}
	.user_headimg
	{
		width:50px;
		height:50px;
		background: #f2f2f2;
		margin-right: 5px;
		position: relative;
	}
	.left_panel
	{
		width:100%;
		height: 100%;
		position: absolute;
		z-index:99;
		top:0px;
		left:-100%;
		transition: left .6s ease-in-out;
	}
	.left_panel.show
	{
		left:0px;
		transition: left .6s ease-in-out;
	}
	.left_panel>.content
	{
		width:260px;
		height:100%;
		overflow: hidden;
		background: #f2f2f2;
		box-shadow: 0px 0px 10px #ccc;
		position: absolute;
		left: 0;
		top: 0;
		z-index: 2;
	}
	.left_panel>.content>.menu_cell
	{
		display: flex;
		height: 70px;
		width:100%;
		background: #fff;
		border-bottom:1px solid #f2f2f2;
		text-align: left;
	}
	
	
	.left_panel>.content>.menu_cell_hover
	{
		background: #f2f2f2;
	}
	
	.left_panel>.content>.menu_cell.last
	{
		border:none;
	}
	.left_panel>.content>.menu_cell.first
	{
		margin-top:10px !important;
	}
	.left_panel>.content>.menu_cell>view:nth-child(1)
	{
		width:70px;
		height:70px;
		padding: 15px;
	}
	.left_panel>.content>.menu_cell>view:nth-child(1)>image
	{
		width:40px;
		height:40px;
	}
	.left_panel>.content>.menu_cell>view:nth-child(2)
	{
		flex:1;
		font-size: 14px;
		line-height: 70px;
	}
	.left_panel>.content>.menu_cell.subtitle>view:nth-child(2)
	{
		flex:1;
		font-size: 14px;
		line-height:16px;
		padding-top:10px;
	}
	.left_panel>.content>.menu_cell.subtitle>view:nth-child(2)>view:nth-child(2)
	{
		font-size: 12px;
		color: #999;
	}
	
	.left_panel>.background
	{
		width:100%;
		height:100%;
		position: absolute;
		left: 0;
		top: 0;
		z-index: 1;
	}
	
	.effect-section
	{
		width: 100%;
		height: 100%;
		position: absolute;
		top:0;
		left: 0;
		z-index: 999999;
	}
	.clickable_effects_item
	{
		width: 40px;
		height: 40px;
		position: absolute;
		transition: all .3s ease-in;
		z-index: 98;
	}
	.clickable_effects_item.in
	{
		animation: ani-fade-in .3s;
		animation-fill-mode:backwards;
	}
	.effect-section>.item
	{
		width: 50px;
		height: 50px;
		position: absolute;
		transition: all .3s ease-in;
	}
	.effect-section>.tips
	{
		width:100%;
		display: flex;
		position: absolute;
		justify-content: center;
		margin-left: -50%;
		z-index: 9;
	}
	.effect-section>.tips.fadein
	{
		animation: ani-fade-in .3s;
		animation-fill-mode:backwards;
		animation-timing-function: ease-out;
	}
	.effect-section>.tips.fadeout
	{
		animation: ani-fade-out .3s;
		animation-fill-mode:forwards;
		animation-timing-function: ease-in;
	}
	.effect-section>.item.in
	{
		animation: ani-bubble-in .3s;
		animation-fill-mode:backwards;
	}
	
	.action_section
	{
		width:100%;
		height: 450px;
		position: absolute;
		bottom: 120px;
		z-index: 2;
	}
	.action_section>.actionani
	{
		width:200px;
		height: 200px;
		position: absolute;
		z-index: 9;
		left:50%;
		margin-left: -50px;
		top:100px;
	}
	.action_section>.status
	{
		position: absolute;
		z-index: 3;
		width:200px;
		top: 300px;
		left:50%;
		margin-left: 70px;
		font-size: 12px;
		color: #999;
	}
	.action_section>.log
	{
		position: absolute;
		z-index: 3;
		width:200px;
		top: 280px;
		right:50%;
		margin-right: 70px;
		font-size: 12px;
		color: #999;
		text-align: right;
	}
	.action_section>.plant
	{
		width:350px;
		height:350px;
		position: absolute;
		z-index: 2;
		left: 50%;
		top:0px;
		margin-left: -200px;
		transform-origin: 200px 290px;
	}
	.bounce
	{
		animation: ani-bounce 3s infinite;
		animation-timing-function: linear;
		animation-fill-mode: both;
	}
	.action_section>.plant.bounce
	{
		animation: ani-bounce 3s infinite;
		animation-timing-function: linear;
		animation-fill-mode: both;
	}
	.action_section>.plant.swing
	{
		animation: ani-swing 1s;
		animation-timing-function: ease-in-out;
		animation-fill-mode: both;
	}
	.pig_jelly
	{
		animation: ani-jelly .3s infinite;
		animation-fill-mode: both;
	}
	
	.action_section>.pot
	{
		width:200px;
		height:200px;
		position: absolute;
		z-index: 1;
		top: 221px;
		left:50%;
		margin-left: -100px;
	}
	.action_section>.pot_hitarea
	{
		width:100px;
		height:100px;
		position: absolute;
		z-index: 3;
		top: 271px;
		left:50%;
		margin-left: -50px;
	}
	
	.action_section>.progress
	{
		width:140px;
		left: 50%;
		margin-left: -70px;
		position: absolute;
		z-index: 3;
		top:381px;
	}
	
	.visitors_section
	{
		width:100%;
		height: 120px;
		position: absolute;
		z-index: 2;
	}
	.visitor_item
	{
		margin-top: 10px;
		margin-left: 5px;
		width:50px;
		position: relative;
	}
	.visitor_item.first
	{
		margin-left: 0px;
	}
	.visitor_item>view:nth-child(1)
	{
		width:50px;
		height:50px;
		background: #ccc;
	}
	.visitor_item>view:nth-child(2)
	{
		width:50px;
		font-size: 12px;
		text-align: center;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.visitor_item>view:nth-child(3)
	{
		width:25px;
		height: 25px;
		position: absolute;
		right: 0;
		top: 0;
	}
	.btn-jelly_hover
	{
		animation: ani-jelly .3s;
		animation-fill-mode: backwards;
	}
	.btn-subtitle.yellow{
		background: #fed43d;
		color: #fff;
	}
	.btn-subtitle.red{
		background: #ff3366;
		color: #fff;
	}
	.btn-subtitle
	{
		width:100%;
		height:60px;
		text-align: center;
		background: #f2f2f2;
		padding-top:10px;
		border-radius: 6px;
	}
	.btn-subtitle>view:nth-child(1)
	{
		font-size: 16px;
	}
	.btn-subtitle>view:nth-child(2)
	{
		font-size: 12px;
		color: #666;
	}
	
	.actions
	{
		display: flex;
		justify-content: left;
		position:absolute;
		z-index:4;
		top: 220px;
		left:50%;
		margin-left: 70px;
	}
	.actions>view
	{
		width:40px;
		height:40px;
	}
	.actions>view>view:nth-child(1)
	{
		width:40px;
		height:40px;
		border-radius: 20px;
	}
	.actions>view>view:nth-child(2)
	{
		width:100%;
		font-size: 12px;
		text-align: center;
	}
	
	.rare_rolling_item
	{
		width:33.33%;
		max-height:100px;
		padding: 5px;
	}
</style>
