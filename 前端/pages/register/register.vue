<template>
	<view class="content">
		<view class="login-bg">
			<view class="login-card">
				<view class="login-head">开始注册</view>
				<view class="login-input login-margin-b">
					<input type="text" maxlength="10" v-model="u_name" placeholder="请输入用户名(4-10位)" />
				</view>
				<view class="login-input">
					<input type="password" maxlength="16" v-model="u_pass" placeholder="请输入密码(8-16位)" />
				</view>
				<view class="login-function">
					<view class="login-register" @click="go_login">有账号？立即登录></view>
				</view>
			</view>
		</view>
		<view class="login-btn">
			<button class="landing" type="primary" @click="go_register">提 交</button>
		</view>
	</view>
</template>

<script>
	var _self, pageOptions;
	var sign = require('../../commons/sign.js');
	export default {
		data() {
			return {
				u_name: '',
				u_pass: '',
			}
		},
		onLoad: function(options) {
			sign.sign(this.apiServer);
			pageOptions = options;
			_self = this;
		},
		methods: {
			go_register: function() {
				if (this.u_name.length == "") {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '用户名不能为空'
					});
					return;
				} else if (this.u_name.length < 4 || this.u_name.length > 10) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '用户名长度需要在4-10位之间'
					});
					return;
				}
				if (this.u_pass.length == "") {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '密码不能为空'
					});
					return;
				} else if (this.u_pass.length < 8 || this.u_pass.length > 16) {
					uni.showToast({
						icon: 'none',
						position: 'bottom',
						title: '密码长度需要在8-16位之间'
					});
					return;
				}
				uni.request({
					url: _self.apiServer + 'member&m=register',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						name: _self.u_name,
						pass: _self.u_pass,
						sign: sign
					},
					success: res => {
						console.log(JSON.stringify(res));
						res = res.data;
						if (res.status == 'ok') {
							uni.showToast({
								icon: 'none',
								position: 'bottom',
								title: "注册成功"
							});
							// 跳转登录页面
							uni.navigateTo({
								url: '../login/login'
							});
						} else if (res.status == 'registered') {
							console.log(JSON.stringify(res));
							uni.showToast({
								icon: 'none',
								position: 'bottom',
								title: "该用户名已被占用！"
							});
						} else {
							console.log(JSON.stringify(res));
							uni.showToast({
								icon: 'none',
								position: 'bottom',
								title: res.data
							});
						}
					},
					fail: (res) => {
						console.log(res);
					},
					complete: () => {}
				});
			},
			go_login() {
				uni.navigateTo({
					url: '../login/login'
				})
			}
		}
	}
</script>

<style>
	.landing {
		height: 84upx;
		line-height: 84upx;
		border-radius: 44upx;
		font-size: 32upx;
		background: linear-gradient(left, #ff3340, #ff595c);
	}

	.login-btn {
		padding: 10upx 20upx;
		margin-top: 350upx;
	}

	.login-function {
		overflow: auto;
		padding: 20upx 20upx 30upx 20upx;
	}

	.login-register {
		color: #666;
		float: right;
		font-size: 26upx;

	}

	.login-input input {
		background: #F2F5F6;
		font-size: 28upx;
		padding: 10upx 25upx;
		height: 62upx;
		line-height: 62upx;
		border-radius: 8upx;
	}

	.login-margin-b {
		margin-bottom: 25upx;
	}

	.login-input {
		padding: 10upx 20upx;
	}

	.login-head {
		font-size: 34upx;
		text-align: center;
		padding: 25upx 10upx 55upx 10upx;
	}

	.login-card {
		background: #fff;
		border-radius: 12upx;
		padding: 10upx 25upx;
		box-shadow: 0 6upx 18upx rgba(0, 0, 0, 0.12);
		position: relative;
		margin-top: 120upx;
	}

	.login-bg {
		height: 260upx;
		padding: 25upx;
		background: linear-gradient(#ff3340, #ff595c);
	}
</style>
