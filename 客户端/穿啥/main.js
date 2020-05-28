import Vue from 'vue'
import App from './App'

import cuCustom from './colorui/components/cu-custom.vue'
Vue.component('cu-custom',cuCustom)

Vue.config.productionTip = false
Vue.prototype.checkLogin = function(backpage, backtype){
	var SNAME = uni.getStorageSync('SNAME');
	var SPASS = uni.getStorageSync('SPASS');
	if(SNAME == '' || SPASS == ''){
		uni.redirectTo({
			url:"../login/login?backpage="+backpage+"&backtype="+backtype
		});
		return false;
	}
	return [SNAME, SPASS];
}
Vue.prototype.check_update = function(){
				//检查应用是否需要更新
				console.log('APP当前版本号'+JSON.stringify(plus.runtime.version));
				uni.request({
					url: this.apiServer + 'update&m=check',
					method: 'POST',
					header: {
						'content-type': "application/x-www-form-urlencoded"
					},
					data: {
						version: plus.runtime.version
					},
					success: res => {
						var update_url = res.data.data.url;
						if (res.data.data.status == 1) {
							//提醒用户更新  
							uni.showModal({
								title: "检测到新版本",
								content: '是否立即前往更新？',
								cancelText: '否',
								cancelColor: '#0055ff',
								confirmText: '是',
								confirmColor: '#ff0000',
								success: (res) => {
									if (res.confirm) {
										plus.runtime.openURL(update_url);
										plus.runtime.quit();
									} else {
										//取消更新则退出APP
										plus.runtime.quit();
									}
								}
							})
						}
					}
				})
			}
// 后台
var APITOKEN  = 'api2020';
Vue.prototype.apiServer    = 'http://maosu.vicp.io/index.php?token='+APITOKEN+'&c=';
Vue.prototype.staticServer = 'http://maosu.vicp.io/';
App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

 



