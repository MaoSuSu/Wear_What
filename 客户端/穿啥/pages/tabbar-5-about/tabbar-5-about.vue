<template>
	<view>
		<cu-custom bgColor="bg-gradual-red" :isBack="true">
			<block slot="backText"><text class="text-bold text-xl">返回</text></block>
			<block slot="content"><text class="text-bold text-xl">关于我们</text></block>
		</cu-custom>
		<view>
			<image class="logo response" src="../../static/logo/logo.png" mode="aspectFit"></image>
		</view>
		<view class="padding bg-white">
			<view class="text-center padding">感谢您使用多功能智能衣柜APP！</view>
			<view class="text-center padding">版本：V2.0</view>
		</view>
	</view>
</template>
