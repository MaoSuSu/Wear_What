<template>
	<view>
		<view>
			<cu-custom bgColor="bg-gradual-red">
				<block slot="content"><text class="text-bold text-xl">护理</text></block>
			</cu-custom>
		</view>
		<view class="cu-bar margin-top bg-white solid-bottom">
			<view class="action">
				<text class="cuIcon-title text-red"></text>衣柜当前状态
			</view>
		</view>
		<view class="cu-list menu" :class="[menuBorder?'sm-border':'',menuCard?'card-menu margin-top':'']">
			<view class="cu-item" :class="menuArrow?'arrow':''">
				<view class="content">
					<text class="cuIcon-hot text-red"></text>
					<text class="text-grey">温度</text>
				</view>
				<view class="action">
					<text class="text-red text">{{temperature_and_humidity[0].Temperature}}</text>
				</view>
			</view>
			<view class="cu-item" :class="menuArrow?'arrow':''">
				<view class="content">
					<text class="cuIcon-rank text-red"></text>
					<text class="text-grey">湿度</text>
				</view>
				<view class="action">
					<text class="text-red text">{{temperature_and_humidity[0].Humidity}}</text>
				</view>
			</view>
		</view>

		<view class="cu-bar margin-top bg-white solid-bottom">
			<view class="action">
				<text class="cuIcon-title text-red"></text>智能护理
			</view>
		</view>

		<view v-if="All_state[0].intelligent_nursing == 0" class="padding flex flex-wrap bg-white justify-center">
			<button class="cu-btn round shadow bg-red lg" @tap="change_intelligent_nursing_state">一键开启</button>
		</view>
		<view v-if="All_state[0].intelligent_nursing == 1" class="padding flex flex-wrap bg-white justify-center">
			<button class="cu-btn round shadow bg-red lg" @tap="change_intelligent_nursing_state">一键关闭</button>
		</view>

		<view class="cu-bar bg-white solid-bottom margin-top">
			<view class="action">
				<text class="cuIcon-title text-red"></text> 手动护理
			</view>
		</view>
		<view class="cu-list menu" :class="[menuBorder?'sm-border':'',menuCard?'card-menu margin-top':'']">
			<view class="cu-item">
				<view class="content padding-tb-sm">
					<view>
						<text class="cuIcon-clothes text-red margin-right-xs"></text> 消毒
					</view>
				</view>

				<view v-if="All_state[0].ventilator == 0">
					<switch class="margin-right-sm" @tap="change_purple_lamp_state"></switch>
				</view>
				<view v-if="All_state[0].purple_lamp == 1">
					<switch checked class="margin-right-sm" @tap="change_purple_lamp_state"></switch>
				</view>

			</view>
			<view class="cu-item">
				<view class="content padding-tb-sm">
					<view>
						<text class="cuIcon-clothes text-red margin-right-xs"></text> 除异味
					</view>
				</view>

				<view v-if="All_state[0].ventilator == 0">
					<switch class="margin-right-sm" @tap="change_ventilator_state"></switch>
				</view>
				<view v-if="All_state[0].ventilator == 1">
					<switch checked class="margin-right-sm" @tap="change_ventilator_state"></switch>
				</view>

			</view>
		</view>
	</view>
</template>

<script>
	var loginRes, _self;
	var signModel = require('../../commons/sign.js');
	export default {
		data() {
			return {
				All_state: [],
				temperature_and_humidity: []
			}
		},
		onLoad() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-4/tabbar-4', 2);
			if (!loginRes) {
				return;
			}
			signModel.sign(_self.apiServer);
			_self.get_temperature_and_humidity();
			_self.get_nursing_state();
		},
		onShow() {
			loginRes = this.checkLogin('../tabbar-4/tabbar-4', 2);
			if (!loginRes) {
				return false;
			}
			// 重新请签名
			signModel.sign(_self.apiServer);
			_self.get_temperature_and_humidity();
		},
		methods: {
			get_nursing_state() {
				uni.showLoading({
					title: '加载设备信息中...',
					mask: true
				});
				uni.request({
					url: this.apiServer + 'clothes_nursing&m=get_nursing_state',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {

					},
					success: res => {
						console.log(JSON.stringify(res));
						if (res.data.status == 'ok') {
							_self.All_state = res.data.data;
						}
						uni.hideLoading();
					}
				});
			},
			get_temperature_and_humidity() {
				uni.request({
					url: this.apiServer + 'clothes_nursing&m=Temperature_and_Humidity',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {

					},
					success: res => {
						if (res.data.status == 'ok') {
							_self.temperature_and_humidity = res.data.data;
							console.log(JSON.stringify(res));
						}
					}
				});
			},
			change_intelligent_nursing_state() {
				uni.request({
					url: this.apiServer + 'clothes_nursing&m=Intelligent_nursing',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {

					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log(JSON.stringify(res));
						}
						_self.get_nursing_state();
					}
				});
			},
			change_ventilator_state() {
				uni.request({
					url: this.apiServer + 'clothes_nursing&m=Ventilator',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {

					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log(JSON.stringify(res));
						}
						_self.get_nursing_state();
					}
				});
			},
			change_purple_lamp_state() {
				uni.request({
					url: this.apiServer + 'clothes_nursing&m=Purple_lamp',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {

					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log(JSON.stringify(res));
						}
						_self.get_nursing_state();
					}
				});
			}
		}
	}
</script>

<style>
	.page {
		height: 100Vh;
		width: 100vw;
	}

	.page.show {
		overflow: hidden;
	}

	.switch-sex::after {
		content: "\e716";
	}

	.switch-sex::before {
		content: "\e7a9";
	}

	.switch-music::after {
		content: "\e66a";
	}

	.switch-music::before {
		content: "\e6db";
	}
</style>
