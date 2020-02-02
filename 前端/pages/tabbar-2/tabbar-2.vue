<template>
	<view class="uni-padding-wrap uni-common-mt">
		<cu-custom bgColor="bg-gradual-red">
			<block slot="content"><text class="text-bold text-xl">推荐</text></block>
		</cu-custom>
		<view class="bg-white">
			<image class="image response" src="../../static/img/wait.gif" mode="widthFix"></image>
			<view class="text-center"><text class="text-bold text-red text-lg">(^・ω・^ )
即将上线</text></view>
		</view>
	</view>
</template>

<script>
	var loginRes, _self;
	export default {
		data() {
			return {

			}
		},
		onLoad() {
			_self = this;
			loginRes = this.checkLogin('../tabbar-2/tabbar-2', 2);
			if (!loginRes) {
				return;
			}

		},
		onShow() {
			loginRes = this.checkLogin('../tabbar-2/tabbar-2', 2);
			if (!loginRes) {
				return false;
			}

		},
		methods: {

		}
	}
</script>

<style>

</style>
