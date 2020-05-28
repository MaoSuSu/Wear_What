<template>
	<view>
		<view>
			<cu-custom bgColor="bg-gradual-red">
				<block slot="content"><text class="text-bold text-xl">护理</text></block>
			</cu-custom>
		</view>
		<view class="cu-bar bg-white solid-bottom">
			<view class="action">
				<text class="cuIcon-title text-red"></text>衣柜当前状态
			</view>
		</view>
		<view class="cu-list menu">
			<view class="cu-item">
				<view class="content">
					<text class="cuIcon-hot text-red"></text>
					<text class="text-grey">温度</text>
				</view>
				<view class="action">
					<text v-if="temperature_and_humidity[0]" class="text-red text">{{temperature_and_humidity[0]}}</text>
					<text v-else class="text-red text">加载中</text>
				</view>
			</view>
			<view class="cu-item">
				<view class="content">
					<text class="cuIcon-rank text-red"></text>
					<text class="text-grey">湿度</text>
				</view>
				<view class="action">
					<text v-if="temperature_and_humidity[1]" class="text-red text">{{temperature_and_humidity[1]}}</text>
					<text v-else class="text-red text">加载中</text>
				</view>
			</view>
		</view>

		<view class="cu-bar margin-top bg-white solid-bottom">
			<view class="action">
				<text class="cuIcon-title text-red"></text>智能消毒护理
			</view>
		</view>
		<view class="padding flex flex-wrap bg-white justify-center">
			<button v-if="All_state.intelligent_nursing == 0" class="cu-btn round shadow bg-gradual-red lg" @tap="change_intelligent_nursing_state">一键开启</button>
			<button v-else-if="All_state.intelligent_nursing == 1" class="cu-btn round shadow lines-red lg" @tap="change_intelligent_nursing_state">一键关闭</button>
			<button v-else class="cuIcon-loading2 cu-btn round shadow bg-red lg">加载中</button>
		</view>

		<view class="cu-bar bg-white solid-bottom margin-top">
			<view class="action">
				<text class="cuIcon-title text-red"></text> 护理选项
			</view>
		</view>
		<view class="cu-list menu">
			<view class="cu-item">
				<view class="content padding-tb-sm">
					<view>
						<text class="cuIcon-clothes text-red margin-right-xs"></text>
						<text class="text-red"> 消毒 </text>
					</view>
				</view>
				<view class="content">
					<input type="number" maxlength="2" v-model="xd_min" placeholder="执行分钟数"></input>
				</view>
				<view>
					<switch v-if="All_state.purple_lamp == 0" class="margin-right-sm" @tap="change_purple_lamp_state"></switch>
					<switch v-else-if="All_state.purple_lamp == 1" checked class="margin-right-sm" @tap="change_purple_lamp_state"></switch>
				</view>
			</view>
			
			<view class="cu-item">
				<view class="content padding-tb-sm">
					<view>
						<text class="cuIcon-clothes text-red margin-right-xs"></text> 
						<text class="text-red"> 除异味 </text>
					</view>
				</view>	
				<view class="content">
					<input type="number" maxlength="2" v-model="cyw_min" placeholder="执行分钟数"></input>
				</view>
				<view>
					<switch v-if="All_state.ventilator == 0" class="margin-right-sm" @tap="change_ventilator_state"></switch>
					<switch v-else-if="All_state.ventilator == 1" checked class="margin-right-sm" @tap="change_ventilator_state"></switch>
				</view>
			</view>
			
			<view class="cu-item">
				<view class="content padding-tb-sm">
					<view>
						<text class="cuIcon-clothes text-red margin-right-xs"></text> 
						<text class="text-red"> 烘干衣服 </text>
					</view>
				</view>
				<view class="content">
					<input type="number" maxlength="2" v-model="hgyf_min" placeholder="执行分钟数"></input>
				</view>
				<view>
					<switch v-if="All_state.heater == 0" class="margin-right-sm" @tap="change_heater_state"></switch>
					<switch v-else-if="All_state.heater == 1" checked class="margin-right-sm" @tap="change_heater_state"></switch>
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
				temperature_and_humidity: [],
				xd_min: '',
				cyw_min: '',
				hgyf_min: ''
			}
		},
		onLoad() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-4/tabbar-4', 2);
			if (!loginRes) {
				return;
			}
			signModel.sign(_self.apiServer);
			//检查更新
			_self.check_update();
			_self.get_temperature_and_humidity();
			_self.get_nursing_state();
		},
		onShow() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-4/tabbar-4', 2);
			if (!loginRes) {
				return false;
			}
			// 重新请签名
			signModel.sign(_self.apiServer);
			_self.get_temperature_and_humidity();
			_self.get_nursing_state();
		},
		methods: {
			get_temperature_and_humidity() {
				uni.request({
					url: this.apiServer + 'clothesnursing&m=TemperatureHumidity',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
			
					},
					success: res => {
						if (res.data.status == 'ok') {
							_self.temperature_and_humidity = res.data.data;
							console.log('温度和湿度：'+JSON.stringify(res.data.data));
						}
					}
				});
			},
			get_nursing_state() {
				uni.request({
					url: this.apiServer + 'clothesnursing&m=GetState',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {

					},
					success: res => {
						console.log('智能及各设备状态：'+JSON.stringify(res.data.data));
						if (res.data.status == 'ok') {
							_self.All_state = res.data.data;
						}
					}
				});
			},
			change_intelligent_nursing_state() {
				// 按钮界面变换
				if(_self.All_state.intelligent_nursing == 1){
					_self.All_state.intelligent_nursing = 0;
					_self.All_state.ventilator = 0;
				}else{
					_self.All_state.intelligent_nursing = 1;
					_self.All_state.ventilator = 1;
				}
				uni.request({
					url: this.apiServer + 'clothesnursing&m=IntelligentNursing',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log('智能按钮状态：'+JSON.stringify(res.data.data));
						}
						_self.get_nursing_state();
					}
				});
			},
			change_ventilator_state() {
				uni.request({
					url: this.apiServer + 'clothesnursing&m=Ventilator',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						cyw : _self.cyw_min
					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log('通风机状态：'+JSON.stringify(res.data.data));
						}
						_self.get_nursing_state();
					}
				});
			},
			change_purple_lamp_state() {
				uni.request({
					url: this.apiServer + 'clothesnursing&m=PurpleLamp',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						xd : _self.xd_min
					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log('紫光灯状态：'+JSON.stringify(res.data.data));
						}
						_self.get_nursing_state();
					}
				});
			},
			change_heater_state() {
				uni.request({
					url: this.apiServer + 'clothesnursing&m=Heater',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						hgyf : _self.hgyf_min
					},
					success: res => {
						if (res.data.status == 'ok') {
							console.log('暖风机状态：'+JSON.stringify(res.data.data));
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
