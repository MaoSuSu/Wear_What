<template>
	<view>
		<cu-custom bgColor="bg-gradual-red" :isBack="true">
			<block slot="backText"><text class="text-bold text-xl">返回</text></block>
			<block slot="content"><text class="text-bold text-xl">录入</text></block>
		</cu-custom>
		<!-- 选择照片 -->
		<view class="cu-form-group">
			<view class="grid col-4 grid-square flex-sub margin-top-sm">
				<view class="bg-img" v-for="(item,index) in imgList" :key="index" @tap="ViewImage" :data-url="imgList[index]">
					<image :src="imgList[index]" mode="aspectFill"></image>
					<!-- 删除照片按钮 -->
					<view class="cu-tag bg-red" @tap.stop="DelImg" :data-index="index">
						<text class='cuIcon-close'></text>
					</view>
				</view>
				<!-- 限制只上传一张照片 -->
				<view class="solids" @tap="AddImage" v-if="imgList.length<1">
					<text class='cuIcon-cameraadd'></text>
				</view>
			</view>
		</view>
		<!-- 填写衣服名称 -->
		<view class="cu-form-group">
			<text class='cuIcon-tag text-red'></text>
			<input type="text" v-model="name" placeholder="   填写衣服名称(1-5)"></input>
		</view>
		<!-- 选择衣服的类型 -->
		<view class="cu-form-group margin-top">
			<text class='cuIcon-list text-red'></text>
			<view class="">
				<picker mode="selector" :range="type" @change="typeChange">
					<view>{{type[currentTypeIndex]}}</view>
				</picker>
			</view>
		</view>
		<!-- 选择衣服的颜色 -->
		<view class="cu-form-group">
			<text class='cuIcon-skin text-red'></text>
			<view class="">
				<picker mode="selector" :range="color" @change="colorChange">
					<view>{{color[currentColorIndex]}}</view>
				</picker>
			</view>
		</view>
		<!-- 选择衣服的适用季节 -->
		<view class="cu-form-group">
			<text class='cuIcon-global text-red'></text>
			<view class="">
				<picker mode="selector" :range="season" @change="seasonChange">
					<view>{{season[currentSeasonIndex]}}</view>
				</picker>
			</view>
		</view>
		<!-- 提交按钮 -->
		<view class="submitNow padding flex flex-direction margin-top-xl">
			<button class="cu-btn round bg-gradual-red shadow-blur lg margin-top-xl" @tap="submitNow">提交</button>
		</view>
	</view>
</template>

<script>
	var loginRes, _self;
	var signModel = require('../../commons/sign.js');
	export default {
		data() {
			return {
				name: '',
				clothes_image: '',
				imgList: [],
				needUploadImg: [],
				uploadIndex: 0,
				//分类
				type: ['选择衣服的类型'],
				color: ['选择衣服的颜色'],
				season: ['选择衣服的适用季节'],
				currentTypeIndex: 0,
				currentColorIndex: 0,
				currentSeasonIndex: 0,
				typeFromApi: [],
				colorFromApi: [],
				seasonFromApi: [],
				// 记录真实选择的api接口数据的分类id
				sedTypeIndex: 0,
				sedColorIndex: 0,
				sedSeasonIndex: 0
			};
		},
		onLoad: function() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-3-input/tabbar-3-input', '2');
			if (!loginRes) {
				return false;
			}
			signModel.sign(_self.apiServer);
			//检查更新
			_self.check_update();
			// 加载衣服类型
			uni.request({
				url: this.apiServer + 'clothestype&m=index',
				method: 'GET',
				success: res => {
					if (res.data.status == 'ok') {
						// 把数据格式整理为 picker 支持的格式 ['分类名', ...]
						var types = res.data.data;
						for (var k in types) {
							_self.type.push(types[k]);
						}
						// 记录分类信息
						_self.typeFromApi = types;
					}
				}
			});
			// 加载衣服颜色
			uni.request({
				url: this.apiServer + 'clothescolor&m=index',
				method: 'GET',
				success: res => {
					if (res.data.status == 'ok') {
						// 把数据格式整理为 picker 支持的格式 ['分类名', ...]
						var colors = res.data.data;
						for (var k in colors) {
							_self.color.push(colors[k]);
						}
						// 记录分类信息
						_self.colorFromApi = colors;
					}
				}
			});
			// 加载衣服季节
			uni.request({
				url: this.apiServer + 'clothesseason&m=index',
				method: 'GET',
				success: res => {
					if (res.data.status == 'ok') {
						// 把数据格式整理为 picker 支持的格式 ['分类名', ...]
						var seasons = res.data.data;
						for (var k in seasons) {
							_self.season.push(seasons[k]);
						}
						// 记录分类信息
						_self.seasonFromApi = seasons;
					}
				}
			});
		},
		onShow: function() {
			loginRes = this.checkLogin('../tabbar-3-input/tabbar-3-input', '2');
			if (!loginRes) {
				return false;
			}
			// 重新请签名
			signModel.sign(_self.apiServer);
		},
		methods: {
			typeChange: function(e) {
				var sedIndex = e.detail.value;
				this.currentTypeIndex = sedIndex;
				// 获取选择的分类名称
				if (sedIndex < 1) {
					return;
				}
				var typeName = this.type[sedIndex];
				for (var k in this.typeFromApi) {
					if (typeName == this.typeFromApi[k]) {
						this.sedTypeIndex = k;
						break;
					}
				}
				this.currentTypeIndex = sedIndex;
			},
			colorChange: function(e) {
				var sedIndex = e.detail.value;
				this.currentColorIndex = sedIndex;
				// 获取选择的分类名称
				if (sedIndex < 1) {
					return;
				}
				var colorName = this.color[sedIndex];
				for (var k in this.colorFromApi) {
					if (colorName == this.colorFromApi[k]) {
						this.sedColorIndex = k;
						break;
					}
				}
				this.currentColorIndex = sedIndex;
			},
			seasonChange: function(e) {
				var sedIndex = e.detail.value;
				this.currentCateIndex = sedIndex;
				// 获取选择的分类名称
				if (sedIndex < 1) {
					return;
				}
				var seasonName = this.season[sedIndex];
				for (var k in this.seasonFromApi) {
					if (seasonName == this.seasonFromApi[k]) {
						this.sedSeasonIndex = k;
						break;
					}
				}
				this.currentSeasonIndex = sedIndex;
			},
			AddImage() {
				uni.chooseImage({
					//默认9
					count: 1,
					sizeType: ['compressed'],
					sourceType: ['album'], //从相册选择
					success: (res) => {
						if (this.imgList.length != 0) {
							this.imgList = this.imgList.concat(res.tempFilePaths)
						} else {
							this.imgList = res.tempFilePaths
						}
						_self.clothes_image = res.tempFilePaths[0]
					}
				});
			},
			ViewImage(e) {
				uni.previewImage({
					urls: this.imgList,
					current: e.currentTarget.dataset.url
				});
			},
			DelImg(e) {
				this.imgList.splice(e.currentTarget.dataset.index, 1)
			},
			submitNow: function() {
				// 数据验证
				if (this.imgList.length == 0) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '为衣服添加一张照片吧！'
					});
					return;
				}
				if (this.name.length < 1) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '请输入衣服名称'
					});
					return;
				}
				if (this.name.length > 5) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '衣服名称过长!(1-5)'
					});
					return;
				}
				if (this.sedTypeIndex < 1) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '请选择衣服的类型'
					});
					return;
				}
				if (this.sedColorIndex < 1) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '请选择衣服的颜色'
					});
					return;
				}
				if (this.sedSeasonIndex < 1) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '请选择衣服的适用季节'
					});
					return;
				}
				// 上传图片 一次一个多次上传 [ 递归函数 ]
				// 上传完成后整体提交数据
				// 首先整理一下需要上传的图片
				// this.needUploadImg = [{tmpurl : 临时地址, index : 数据索引}]
				this.needUploadImg = [];
				this.needUploadImg.push({
					"tmpurl": this.clothes_image,
					"indexID": 0
				});
				console.log(this.needUploadImg);
				this.uploadImg();
			},
			uploadImg: function() {
				// 如果没有选择图片 或者已经上传完成 则执行提交
				if (this.needUploadImg.length < 1 || this.uploadIndex >= this.needUploadImg.length) {
					//
					uni.showLoading({
						title: "正在提交"
					});
					var sign = uni.getStorageSync('sign');
					uni.request({
						url: this.apiServer + 'clothesdata&m=addData',
						method: 'POST',
						header: {
							'content-type': "application/x-www-form-urlencoded"
						},
						data: {
							name: _self.name,
							image: _self.clothes_image,
							u_name: loginRes[0],
							t: _self.sedTypeIndex,
							c: _self.sedColorIndex,
							s: _self.sedSeasonIndex,
							sign: sign
						},
						success: function(res) {
							if (res.data.status == 'ok') {
								uni.showToast({
									icon: 'none',
									position: 'bottom',
									title: "提交成功"
								});
								_self.clothes_image = '';
								// 清空数据
								signModel.sign(_self.apiServer);
								// 防止数据缓存
								_self.currentTypeIndex = 0;
								_self.currentColorIndex = 0;
								_self.currentSeasonIndex = 0;
								_self.sedTypeIndex = 0;
								_self.sedColorIndex = 0;
								_self.sedSeasonIndex = 0;
								_self.needUploadImg = [];
								_self.imgList = [];
								_self.name = '';
								setTimeout(function() {
									uni.switchTab({
										url: '../tabbar-3/tabbar-3'
									})
								}, 1000);
							} else {
								console.log(JSON.stringify(res));
								uni.showToast({	
									icon: 'none',
									position: 'bottom',
									title: '提交失败'
								});
								setTimeout(function() {
									uni.switchTab({
										url: '../tabbar-3/tabbar-3'
									})
								}, 1000);
							}
						}
					});

				} else {
					// 上传图片
					uni.showLoading({
						title: "上传图片"
					});
					var uploader = uni.uploadFile({
						url: _self.apiServer + 'uploadimg',
						filePath: _self.needUploadImg[_self.uploadIndex].tmpurl,
						name: 'file',
						success: (uploadFileRes) => {
							console.log(uploadFileRes);
							uploadFileRes = JSON.parse(uploadFileRes.data);
							if (uploadFileRes.status != 'ok') {
								uni.showToast({
									title: "上传图片失败,请重试!",
									icon: "none"
								});
								return false;
							}
							// 将已经上传的文件地址赋值给文章数据
							var index = _self.needUploadImg[_self.uploadIndex].indexID;
							_self.clothes_image = _self.staticServer + uploadFileRes.data;
							_self.uploadIndex++;
							// 递归上传
							setTimeout(function() {
								_self.uploadImg();
							}, 1000);
						},
						fail: (e) => {
							console.log(e);
							uni.showToast({
								title: "上传图片失败,请重试!",
								icon: "none"
							});
						}
					})
				}
			}
		}
	}
</script>

<style>
	.cu-form-group {
		min-width: calc(4em + 15px);
	}
</style>
