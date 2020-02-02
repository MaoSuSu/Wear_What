import Vue from 'vue'
import App from './App'

import cuCustom from './colorui/components/cu-custom.vue'
Vue.component('cu-custom',cuCustom)

Vue.config.productionTip = false
Vue.prototype.checkLogin  = function(backpage, backtype){
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
// 后台
var APITOKEN  = 'api2020';
Vue.prototype.apiServer    = 'http://maosu.vicp.io/index.php?token='+APITOKEN+'&c=';
Vue.prototype.staticServer = 'http://maosu.vicp.io/';
App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

 



