"use strict";
import request from "@/modules/request";
import {toast} from "@/modules/toast";
import {extend} from  "@/modules/copy";
import storage from "@/modules/storage.js";
import {methods} from "@/vector/mixins.js";
import {data} from "@/modules/global-data";
import {PubSub} from "@/modules/event-bus";
import {extDate} from "@/modules/datetime";
import {checkUpdate} from  "@/modules/update";
import {getCurWeek} from  "@/vector/pub-fct";
import {throttleGenerater} from "@/modules/operate-limit";

function disposeApp($app){
    extDate(); //拓展Date原型
    checkUpdate(); // 检查更新
    uni.$app = $app.$scope;
    $app.$scope.toast = toast;
    $app.$scope.extend = extend;
    $app.data = $app.globalData;
    $app.$scope.data = $app.data;
    $app.$scope.eventBus = new PubSub();
    $app.$scope.extend($app.data, data);
    $app.$scope.extend($app.$scope, request);
    $app.data.colorN = $app.data.colorList.length;
    $app.$scope.reInitApp = initAppData.bind($app);
    $app.$scope.throttle = new throttleGenerater();
    $app.data.curWeek = getCurWeek($app.data.curTermStart);
    $app.$scope.onload = (funct, ...args) => {
        if($app.data.openid) funct(...args);
        else $app.$scope.eventBus.once("LoginEvent", funct);
    }
}

function initAppData(){
    var $app = this;
    uni.login({
        // #ifdef MP-WEIXIN
        provider: "weixin",
        // #endif
        // #ifdef MP-QQ
        provider: "qq",
        // #endif
    }).then((data) => {
        var [err,res] = data;
        if(err) return Promise.reject(err);
        return Promise.resolve(res);
    }).then((res) => {
        /* resolve */
        return Promise.resolve(res);
    }).then((res) => {
        $app.$scope.eventBus.commit("LoginEvent", res);
    }).catch((err) => {
        console.log(err);
        uni.showModal({
            title: "警告",
            content: "数据初始化失败,点击确定重新初始化数据",
            showCancel: false,
            success: (res) => initAppData.apply($app)
        })
    })
}

/**
 * APP启动事件
 */
function onLaunch() {
    disposeApp(this);
    initAppData.apply(this);
}

export default {onLaunch, toast}
