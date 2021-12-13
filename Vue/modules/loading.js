import {
    Loading
} from 'element-ui';

function startLoading(options) {
    if (!options.load) return true;
    var loadingInstance = Loading.service({
        lock: true,
        text: options.title || 'loading...'
    })
    return loadingInstance;
}

function endLoading(options, loadingInstance) {
    if (!options.load) return true;
    loadingInstance.close();
}

export {  startLoading, endLoading }

export default {  startLoading, endLoading }