import Vue from 'vue'
import App from './App.vue'
import router from './router'
import dispose from '@/vector/dispose'
import util from '@/utils/util.js'

Vue.config.productionTip = false

import layout from '@/components/common/layout';
Vue.use(layout)

import { Button } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(Button)

util.extDate();
Vue.prototype.$custApp = {};
Vue.prototype.$custApp.ajax = dispose.ajax;
Vue.prototype.$custApp.toast = dispose.toast;
Vue.prototype.$custApp.globalData = dispose.globalData;
const getApp = () => {return Vue.prototype.$custApp;}
if(!window.custApp) window.custApp = getApp();

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')


router.beforeEach((to, from, next) => {
    next();
})