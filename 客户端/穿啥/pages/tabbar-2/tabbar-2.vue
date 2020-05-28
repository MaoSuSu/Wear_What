<template>
	<view class="uni-padding-wrap uni-common-mt">
		<cu-custom bgColor="bg-gradual-red">
			<block slot="content"><text class="text-bold text-xl">推荐</text></block>
		</cu-custom>
		<view class="bg-gradual-red">
			<image class="image response" src="../../static/img/recommended.gif" mode="widthFix"></image>
			<view class="text-center">
				<text class="text-bold text-white text-lg">当地温度：{{temperature[0]}}
				不知道该穿啥？我来告诉你！
				</text>
			</view>
		</view>
		<!-- 选择出席的场合 -->
		<view class="cu-form-group">
			<text class='cuIcon-global text-red'></text>
			<view class="">
				<picker mode="selector" :range="occasion" @change="occasionChange">
					<view>{{occasion[currentOccasionIndex]}}</view>
				</picker>
			</view>
		</view>
		
		<!-- 推荐列表 -->
		<view class="cu-list menu-avatar">
			<view class="text-center bg-gradual-red" v-if="num_re != 0"><text class="text-bold text-white text-lg">推荐列表</text></view>
			<view class="cu-item" v-for="(re,j) in list_re" :key="j" >
				<view v-for="(clodata,i) in All_clothes_data" :key="clodata.clo_id" v-if="All_clothes_data[i].clo_id == list_re[j]">
					<!-- 衣服名 -->
					<view class="padding justify-start margin-right-xl">
						<view class="content">
							<view class="text-red">
								<text class="text-cut margin-left-sm">
									{{All_clothes_data[i].clo_name}}
								</text>
							</view>
						</view>
						<!-- 衣服照片 -->
						<img class="cu-avatar radius xl margin-right-xl" :src="All_clothes_data[i].clo_image" />
					</view>
				</view>
			</view>
		</view>
		
		<!-- 推荐按钮 -->
		<view class="submitNow padding flex flex-direction margin-top-xl">
			<button class="cu-btn round bg-gradual-red shadow-blur lg margin-top-xl" @tap="submitNow">推荐一下</button>
		</view>
		
	</view>
</template>

<script>
	var loginRes, _self;
	export default {
		data() {
			return {
				temperature : 0,
				//推荐选项
				occasion: ['选择出席的场合'],
				currentOccasionIndex: 0,
				occasionFromApi: [],
				// 记录真实选择的api接口数据的分类id
				sedOccasionIndex: 0,
				num_re : 0,
				list_re : [],
				All_clothes_data: []
			}
		},
		onLoad() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-2/tabbar-2', 2);
			if (!loginRes) {
				return;
			}
			//检查更新
			_self.check_update();
			//预载用户衣服信息
			_self.getClothes();
			//加载衣服场合
			_self.getoccasion();
			//加载温度信息
			_self.getTM();
		},
		onShow() {
			loginRes = this.checkLogin('../tabbar-2/tabbar-2', 2);
			if (!loginRes) {
				return false;
			}
			_self.getClothes();
			_self.getTM();
		},
		methods: {
			getoccasion(){
				uni.request({
					url: this.apiServer + 'clothesre&m=reoccasion',
					method: 'GET',
					success: res => {
						if (res.data.status == 'ok') {
							// 把数据格式整理为 picker 支持的格式 ['分类名', ...]
							var occasions = res.data.data;
							for (var k in occasions) {
								_self.occasion.push(occasions[k]);
							}
							// 记录分类信息
							_self.occasionFromApi = occasions;
						}
					}
				});
			},
			occasionChange: function(e) {
				var sedIndex = e.detail.value;
				this.currentCateIndex = sedIndex;
				// 获取选择的分类名称
				if (sedIndex < 1) {
					return;
				}
				var occasionName = this.occasion[sedIndex];
				for (var k in this.occasionFromApi) {
					if (occasionName == this.occasionFromApi[k]) {
						this.sedOccasionIndex = k;
						break;
					}
				}
				this.currentOccasionIndex = sedIndex;
			},
			submitNow: function() {
				// 数据验证
				if (this.sedOccasionIndex < 1) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '请选择出席场合'
					});
					return;
				}
				//上传动作
				uni.showLoading({
					title: 'AI正在推荐中...',
					mask: true
				});
				uni.request({
					url: this.apiServer + 'clothesre&m=re',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						uname: loginRes[0],
						o: _self.sedOccasionIndex
					},
					success: res => {
						console.log(JSON.stringify('推荐信息:'+res.data.data));
						if (res.data.status == 'ok') {
							_self.num_re = res.data.data.length;
							_self.list_re = res.data.data;
							console.log(JSON.stringify(_self.num_re));
							console.log(JSON.stringify(_self.list_re[0]));
							console.log(JSON.stringify(_self.All_clothes_data[0].clo_id));
						}
					},
					complete() {
						uni.hideLoading();
					}
				});
			},
			getClothes() {
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
						if (res.data.status == 'ok') {
							_self.All_clothes_data = res.data.data;
						}
					}
				});
			},
			getTM() {
				// 读取温度信息
				uni.request({
					url: this.apiServer + 'clothesre&m=getT',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
					},
					success: res => {
						console.log(JSON.stringify('温度信息:'+res.data.data));
						if (res.data.status == 'ok') {
							_self.temperature = res.data.data;
						}
					}
				});
			}
		}
	}
</script>

<style>
	.cu-form-group {
		min-width: calc(4em + 15px);
	}
</style>
