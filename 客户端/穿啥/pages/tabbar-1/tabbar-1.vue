<template>
	<view>
		<view>
			<cu-custom bgColor="bg-gradual-red">
				<block slot="content"><text class="text-bold text-xl">我的衣柜</text></block>
			</cu-custom>
		</view>
		<image src="../../static/img/welcome.png" mode="widthFix" class="response"></image>
		<view class="VerticalBox">
			<scroll-view class="VerticalNav nav" scroll-y scroll-with-animation :scroll-top="verticalNavTop" style="height:calc(100vh - 375upx)">
				<view class="cu-item" :class="index==tabCur?'text-red cur':''" v-for="(item,index) in list" :key="index" @tap="TabSelect"
				 :data-id="index">
					{{item.name}}
				</view>
			</scroll-view>
			<scroll-view class="VerticalMain" scroll-y scroll-with-animation style="height:calc(100vh - 375upx)"
			 :scroll-into-view="'main-'+mainCur" @scroll="VerticalMain">
				<view class="padding-top padding-lr" v-for="(item,index) in list" :key="index" :id="'main-'+index">
					<view class="cu-bar solid-bottom bg-white">
						<view class="action">
							<text class="cuIcon-title text-red"></text> 
								{{item.name}}
						</view>
					</view>
					<view class="cu-list menu-avatar">
						<view class="cu-item" v-for="(clodata,i) in All_clothes_data" :key="clodata.clo_id" v-if="All_clothes_data[i].clo_type == item.name">
							<!-- 照片，点击照片弹出定位窗口 -->
							<view class="padding justify-start margin-right-xl">
								<img class="cu-avatar radius xl margin-right-xl" :src="All_clothes_data[i].clo_image" @tap="showModal" data-target="Modal" :data-ms="All_clothes_data[i].clo_name" :data-id="All_clothes_data[i].clo_dingwei"/>
							</view>
							<view class="content">
								<view class="text-red">
									<text class="text-cut margin-left-sm">
										{{All_clothes_data[i].clo_name}}
									</text>
								</view>
							</view>
							<view class="action">
								<!-- 颜色 -->
								<view v-if="All_clothes_data[i].clo_color == '红色'" class="cu-tag round bg-red sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '橙色'" class="cu-tag round bg-orange sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '黄色'" class="cu-tag round bg-yellow sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '绿色'" class="cu-tag round bg-green sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '青色'" class="cu-tag round bg-cyan sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '蓝色'" class="cu-tag round bg-blue sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '紫色'" class="cu-tag round bg-purple sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '灰色'" class="cu-tag round bg-grey sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '粉色'" class="cu-tag round bg-pink sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '黑色'" class="cu-tag round bg-black sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '白色'" class="cu-tag round bg-white sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<view v-if="All_clothes_data[i].clo_color == '棕色'" class="cu-tag round bg-brown sm">
									{{All_clothes_data[i].clo_color}}
								</view>
								<!-- 季节 -->
								<view v-if="All_clothes_data[i].clo_season == '春季'" class="cu-tag round bg-gradual-green sm">
									{{All_clothes_data[i].clo_season}}
								</view>
								<view v-if="All_clothes_data[i].clo_season == '夏季'" class="cu-tag round bg-gradual-red sm">
									{{All_clothes_data[i].clo_season}}
								</view>
								<view v-if="All_clothes_data[i].clo_season == '秋季'" class="cu-tag round bg-gradual-orange sm">
									{{All_clothes_data[i].clo_season}}
								</view>
								<view v-if="All_clothes_data[i].clo_season == '冬季'" class="cu-tag round bg-gradual-blue sm">
									{{All_clothes_data[i].clo_season}}
								</view>
							</view>
							<!-- 喜欢/不喜欢按钮 -->
							<view v-if="All_clothes_data[i].clo_iflike == 0">
								<button class="cu-btn cuIcon">
									<text class="cuIcon-like" @tap="iflike(All_clothes_data[i].clo_name,All_clothes_data[i].clo_iflike)"></text>
								</button>
							</view>
							<view v-if="All_clothes_data[i].clo_iflike == 1">
								<button class="cu-btn cuIcon">
									<text class="cuIcon-likefill text-red" @tap="iflike(All_clothes_data[i].clo_name,All_clothes_data[i].clo_iflike)"></text>
								</button>
							</view>
							<!-- 删除按钮 -->
							<button class="cu-btn cuIcon margin-left-xs">
								<text class="cuIcon-close" @tap="remove(All_clothes_data[i].clo_name)"></text>
							</button>
						</view>
					</view>
				</view>
			</scroll-view>
		</view>
		<!-- 定位窗口弹出 -->
		<view class="cu-modal" :class="modalName=='Modal'?'show':''" @tap="hideModal">
			<view class="cu-dialog">
				<view class="cu-bar bg-white justify-end">
					<view class="content">衣服定位设置</view>
					<view class="action" @tap="hideModal">
						<text class="cuIcon-close text-red"></text>
					</view>
				</view>
				<view class="padding-xl">
					为<text class="text-red">{{nowname}}</text>设置一个定位衣架
				</view>
				<!-- <img class="bg-img" :src="All_clothes_data[i].clo_image"/> -->
				<view class="cu-dialog" @tap.stop="">
					<radio-group class="block" @change="RadioChange">
						<view class="cu-list menu text-left">
							<view class="cu-item" v-for="(item,index) in 4" :key="index">
								<label class="flex justify-between align-center flex-sub">
									<view class="flex-sub">定位衣架 {{index +1}}</view>
									<radio class="round" :class="radio==index?'checked':''" :checked="radio==index?true:false" :value="index"></radio>
								</label>
							</view>
						</view>
					</radio-group>
				</view>
				<view class="cu-bar bg-white">
					<view class="action margin-0 flex-sub cuIcon-creative text-red solid-left" @tap="clight">定位</view>
					<view class="action margin-0 flex-sub solid-left" @tap="setlightid">确定</view>
				</view>
			</view>
		</view>
		
	</view>
</template>

<script>
	var _self, loginRes;
	var signModel = require('../../commons/sign.js');
	export default {
		data() {
			return {
				radio: null,
				modalName: null,
				nowname: null,
				list: [],
				tabCur: 0,
				mainCur: 0,
				verticalNavTop: 0,
				load: true,
				All_clothes_data: []
			};
		},
		onLoad() {
			_self = this;
			//检查更新
			_self.check_update();
			//登录检查
			loginRes = this.checkLogin('../tabbar-1/tabbar-1', '2');
			if (!loginRes) {
				return false;
			}
			// 签名
			signModel.sign(_self.apiServer);
			let list = [{}];
			for (let i = 0; i < 15; i++) {
				list[i] = {};
				list[i].id = i;
			}
			list[0].name = '短袖';
			list[1].name = '长袖';
			list[2].name = '外套';
			list[3].name = '短裤';
			list[4].name = '长裤';
			list[5].name = '连衣裤';
			list[6].name = '连衣裙';
			list[7].name = '半裙';
			list[8].name = '长裙';
			list[9].name = '内衣睡衣';
			list[10].name = '鞋子';
			list[11].name = '包包';
			list[12].name = '首饰';
			list[13].name = '帽子';
			list[14].name = '其他';
			this.list = list;
			this.listCur = list[0];
			_self.getClothes();
		},
		onShow() {
			_self.getClothes();
			loginRes = this.checkLogin('../tabbar-1/tabbar-1', '2');
			if (!loginRes) {
				return false;
			}
			// 重新请签名
			signModel.sign(_self.apiServer);
		},
		methods: {
			RadioChange(e) {
				this.radio = e.detail.value
			},
			showModal(e) {
				this.modalName = e.currentTarget.dataset.target;
				this.nowname = e.currentTarget.dataset.ms;
				this.radio = e.currentTarget.dataset.id;
				console.log(JSON.stringify(this.radio));
			},
			hideModal(e) {
				this.modalName = null
			},
			// 定位衣服
			clight(){
				_self.setlightid();
				uni.request({
					url: this.apiServer + 'clothesdata&m=clight',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						id:this.radio
					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log(JSON.stringify(res.data.data));
						}
					}
				});
			},
			// 设置衣服的定位id
			setlightid(){
				uni.request({
					url: this.apiServer + 'clothesdata&m=setlightid',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						cname:this.nowname,
						id:this.radio
					},
					success: res => {
						if (res.data.status == 'ok') {
							this.radio = e.currentTarget.dataset.id;
							console.log(JSON.stringify(res.data.data));
						}
					},
					complete() {
						_self.getClothes();
						_self.hideModal();
					}
				});
			},
			// 是否喜欢函数
			iflike(clo_name, clo_iflike) {
				uni.showLoading({
					title: '加载中...',
					mask: true
				});
				uni.request({
					url: this.apiServer + 'clothesdata&m=iflike',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						iflike_clo_name: clo_name,
						clo_iflike: clo_iflike
					},
					success: res => {
						//console.log(JSON.stringify(res));
						if (res.data.status == 'ok') {
							console.log(JSON.stringify(res.data.data));
						}
					},
					complete() {
						_self.getClothes();
						uni.hideLoading();
					}
				});
			},
			remove(clo_name) {
				uni.showModal({
					title: "提示",
					content: '确定要删除这件衣服吗？',
					cancelText: '否',
					cancelColor: '#0055ff',
					confirmText: '是',
					confirmColor: '#ff0000',
					success: res => {
						if (res.confirm) {
							uni.showLoading({
								title: '删除中...',
								mask: true
							});
							uni.request({
								url: this.apiServer + 'clothesdata&m=remove',
								method: 'POST',
								header: {
									'content-type': "application/x-www-form-urlencoded"
								},
								data: {
									remove_clo_name: clo_name
								},
								success: res => {
									// console.log(JSON.stringify(res));
									if (res.data.status == 'ok') {
										console.log(JSON.stringify(res));
										console.log(JSON.stringify(res.data.data))
									}
								},
								complete() {
									_self.getClothes();
									uni.hideLoading();
								}
							});
						}
					}
				})
			},
			TabSelect(e) {
				this.tabCur = e.currentTarget.dataset.id;
				this.mainCur = e.currentTarget.dataset.id;
				this.verticalNavTop = (e.currentTarget.dataset.id - 1) * 50
			},
			VerticalMain(e) {
				// #ifdef MP-ALIPAY
				return false //支付宝小程序暂时不支持双向联动 
				// #endif
				let that = this;
				let tabHeight = 0;
				if (this.load) {
					for (let i = 0; i < this.list.length; i++) {
						let view = uni.createSelectorQuery().select("#main-" + this.list[i].id);
						view.fields({
							size: true
						}, data => {
							this.list[i].top = tabHeight;
							tabHeight = tabHeight + data.height;
							this.list[i].bottom = tabHeight;
						}).exec();
					}
					this.load = false
				}
				let scrollTop = e.detail.scrollTop + 10;
				for (let i = 0; i < this.list.length; i++) {
					if (scrollTop > this.list[i].top && scrollTop < this.list[i].bottom) {
						this.verticalNavTop = (this.list[i].id - 1) * 50
						this.tabCur = this.list[i].id
						console.log(scrollTop)
						return false
					}
				}
			},
			getClothes() {
				uni.showLoading({
					title: '加载中...',
					mask: true
				});
				// 读取衣服信息
				uni.request({
					url: this.apiServer + 'clothesdata&m=getList',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						u_name: loginRes[0]
					},
					success: res => {
						console.log(JSON.stringify(res.data.data));
						if (res.data.status == 'ok') {
							_self.All_clothes_data = res.data.data;
						}
					},
					complete() {
						uni.hideLoading();
					}
				});
			}
		}
	}
</script>

<style>
	.fixed {
		position: fixed;
		z-index: 99;
	}

	.VerticalNav.nav {
		width: 200upx;
		white-space: initial;
	}

	.VerticalNav.nav .cu-item {
		width: 100%;
		text-align: center;
		background-color: #fff;
		margin: 0;
		border: none;
		height: 50px;
		position: relative;
	}

	.VerticalNav.nav .cu-item.cur {
		background-color: #f1f1f1;
	}

	.VerticalNav.nav .cu-item.cur::after {
		content: "";
		width: 8upx;
		height: 30upx;
		border-radius: 10upx 0 0 10upx;
		position: absolute;
		background-color: currentColor;
		top: 0;
		right: 0upx;
		bottom: 0;
		margin: auto;
	}

	.VerticalBox {
		display: flex;
	}

	.VerticalMain {
		background-color: #f1f1f1;
		flex: 1;
	}
</style>
