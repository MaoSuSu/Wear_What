<template>
	<view>
		<cu-custom bgColor="bg-gradual-red">
			<block slot="content"><text class="text-bold text-xl">我的</text></block>
		</cu-custom>

		<view class="bg-white myface">
			<image src="../../static/user_face/user_face.png" mode="widthFix"></image>
		</view>
		<view style="text-align:center; margin:10px 0;">
			{{user_name}}
		</view>
		
		<view class="grace-box-banner" style="margin:10rpx 0;">
			<view class="garce-items">
				<view class="line1">{{user.clotheCount}}</text></view>
				<view class="line2">衣服总数</view>
			</view>
			<view class="garce-items">
				<view class="line1">{{user.clotheLike}}</text></view>
				<view class="line2">喜欢的衣服数量</view>
			</view>
		</view>

		<view class="box">
			<view class="cu-bar bg-white">
				<view class="action">
					<text class="cuIcon-titles text-red"></text>
					<text class="text">账户管理</text>
				</view>
			</view>
			<view class="cu-bar bg-white">
				<view class="action" @click="goToPage('/pages/tabbar-5-about/tabbar-5-about')">
					<text class="cuIcon-titles text-red"></text>
					<text class="text">关于我们</text>
				</view>
			</view>
			<view class="box text-center radius margin-top-xl">
				<button class="cu-btn round shadow-blur bg-gradual-red margin-top-xl" @tap="logoff">退出账号</button>
			</view>

		</view>
	</view>
</template>

<script>
	var loginRes, _self;
	export default {
		data() {
			return {
				user_name:'',
				user: {}
			}
		},
		onLoad: function() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-5/tabbar-5', '2');
			if (!loginRes) {
				return;
			}
			//检查更新
			_self.check_update();
		},
		onShow: function() {
			loginRes = this.checkLogin('../tabbar-5/tabbar-5', '2');
			if (!loginRes) {
				return false;
			}
			// 加载用户信息
			this.user_name = loginRes[0];
			uni.request({
				url: this.apiServer + 'my&m=info',
				method: 'POST',
				header: {
					'content-type': "application/x-www-form-urlencoded"
				},
				data: {
					uname: loginRes[0]
				},
				success: res => {
					console.log(JSON.stringify(res));
					if (res.data.status == 'ok') {
						this.user = res.data.data;
					}
				}
			});
		},
		methods: {
			goToPage(url) {
				if (!url) return;
				uni.navigateTo({
					url
				});
			},
			logoff: function() {
				uni.removeStorageSync('SNAME');
				uni.removeStorageSync('SPASS');
				uni.showToast({
					icon: 'none',
					position: 'bottom',
					title: "您已经退出"
				});
				setTimeout(function() {
					uni.switchTab({
						url: '../tabbar-1/tabbar-1'
					});
				}, 1000);
			}
		}
	}
</script>

<style>
	.box {
		margin: 20upx 0;
	}

	.box view.cu-bar {
		margin-top: 20upx;
	}

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

	.myface {
		width: 100px;
		height: 100px;
		border: 5px solid #F1F2F3;
		border-radius: 100%;
		margin: 15px auto;
	}

	.myface image {
		width: 100%;
		border-radius: 100%;
	}

	.myart-list {
		width: 100%;
		margin: 8px 0;
		overflow: hidden;
		border-bottom: 1px dashed #D7D8D9;
	}

	.myart-list .title {
		line-height: 2em;
		width: 100%;
	}

	.myart-list .btns {
		line-height: 2em;
		width: 100%;
	}

	.myart-list .btns view {
		float: right;
		padding: 0 6px;
		margin: 0 5px;
		color: #00B26A;
	}

	.myart-list .btns view:last-child {
		color: #F76260;
	}

	.loadMore {
		width: 100%;
		padding: 8px 0;
		text-align: center;
		color: #CCCCCC;
	}
</style>
