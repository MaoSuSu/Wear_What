
var isReady=false;var onReadyCallbacks=[];
var __uniConfig = {"pages":["pages/tabbar-1/tabbar-1","pages/tabbar-2/tabbar-2","pages/tabbar-3/tabbar-3","pages/tabbar-4/tabbar-4","pages/tabbar-5/tabbar-5","pages/tabbar-3-input/tabbar-3-input","pages/tabbar-5-about/tabbar-5-about","pages/login/login","pages/register/register"],"window":{"navigationStyle":"custom"},"tabBar":{"borderStyle":"white","color":"#8F8F94","selectedColor":"#f33e54","blurEffect":"extralight","list":[{"pagePath":"pages/tabbar-1/tabbar-1","iconPath":"static/img/tabbar/home.png","selectedIconPath":"static/img/tabbar/homeactive.png","text":"衣柜"},{"pagePath":"pages/tabbar-2/tabbar-2","iconPath":"static/img/tabbar/guanzhu.png","selectedIconPath":"static/img/tabbar/guanzhuactive.png","text":"推荐"},{"pagePath":"pages/tabbar-3/tabbar-3","iconPath":"static/img/tabbar/plus.png","selectedIconPath":"static/img/tabbar/plusactive.png"},{"pagePath":"pages/tabbar-4/tabbar-4","iconPath":"static/img/tabbar/news.png","selectedIconPath":"static/img/tabbar/newsactive.png","text":"护理"},{"pagePath":"pages/tabbar-5/tabbar-5","iconPath":"static/img/tabbar/me.png","selectedIconPath":"static/img/tabbar/meactive.png","text":"我的"}]},"splashscreen":{"alwaysShowBeforeRender":true,"autoclose":false},"appname":"多功能智能衣柜VUE","compilerVersion":"2.5.1","entryPagePath":"pages/tabbar-1/tabbar-1","networkTimeout":{"request":6000,"connectSocket":6000,"uploadFile":6000,"downloadFile":6000}};
var __uniRoutes = [{"path":"/pages/tabbar-1/tabbar-1","meta":{"isQuit":true,"isTabBar":true},"window":{}},{"path":"/pages/tabbar-2/tabbar-2","meta":{"isQuit":true,"isTabBar":true},"window":{}},{"path":"/pages/tabbar-3/tabbar-3","meta":{"isQuit":true,"isTabBar":true},"window":{}},{"path":"/pages/tabbar-4/tabbar-4","meta":{"isQuit":true,"isTabBar":true},"window":{}},{"path":"/pages/tabbar-5/tabbar-5","meta":{"isQuit":true,"isTabBar":true},"window":{}},{"path":"/pages/tabbar-3-input/tabbar-3-input","meta":{},"window":{}},{"path":"/pages/tabbar-5-about/tabbar-5-about","meta":{},"window":{}},{"path":"/pages/login/login","meta":{},"window":{}},{"path":"/pages/register/register","meta":{},"window":{}}];
__uniConfig.onReady=function(callback){if(__uniConfig.ready){callback()}else{onReadyCallbacks.push(callback)}};Object.defineProperty(__uniConfig,"ready",{get:function(){return isReady},set:function(val){isReady=val;if(!isReady){return}const callbacks=onReadyCallbacks.slice(0);onReadyCallbacks.length=0;callbacks.forEach(function(callback){callback()})}});
service.register("uni-app-config",{create(a,b,c){if(!__uniConfig.viewport){var d=b.weex.config.env.scale,e=b.weex.config.env.deviceWidth,f=Math.ceil(e/d);Object.assign(__uniConfig,{viewport:f,defaultFontSize:Math.round(f/20)})}return{instance:{__uniConfig:__uniConfig,__uniRoutes:__uniRoutes}}}});
