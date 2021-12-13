import {startLoading, endLoading} from "./loading";
import {extend} from "./copy";
import {toast} from "./toast";
import axios from 'axios';

var headers = {'content-type': 'application/x-www-form-urlencoded'};

function ajax(requestInfo) {
    var options = {
        load: true,
        url: "",
        method: "GET",
        headers: {},
        data: {},
        params: {},
        success: () => {},
        resolve: () => {},
        fail: function() { this.completeLoad = () => { toast("External Error");}},
        reject: () => {},
        complete: () => {},
        completeLoad: () => {}
    };
    extend(options, requestInfo);
    extend(options.headers, headers);
    let loadingInstance = startLoading(options);
    return axios.request({
        url: options.url,
        data: options.data,
        params: options.params,
        method: options.method,
        headers: options.headers,
        transformRequest: [function(data) {
            let ret = ''
            for (let it in data) ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&'
            return ret
        }]
    }).then((res) => {
        if (res.status === 200 && res.data.status) {
            if(res.data.status === -1 && res.data.msg){
                toast(res.data.msg);
                return void 0;
            }
            try {
                options.success(res);
                options.resolve(res);
            } catch (e) {
                options.completeLoad = () => { toast("External Error"); }
                console.log(e);
            }
        } else {
            options.fail(res);
            options.reject(res);
        }
    }).catch((res) => {
        options.fail(res);
        options.reject(res);
    }).then((res) => {
        endLoading(options, loadingInstance);
        try {
            options.complete(res);
        } catch (e) {
            console.log(e);
        }
        options.completeLoad(res);
    })
}

/**
 * request promise封装
 */
function request(option) {
    return new Promise((resolve,reject) => {
        option.resolve = resolve;
        option.reject = reject;
        ajax(option);
    })
}


export { ajax, request }
export default { ajax, request }
