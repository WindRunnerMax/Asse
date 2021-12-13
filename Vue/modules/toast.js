import { Message } from 'element-ui'

function toast(msg, type = 'error') {
    Message({
        message: msg,
        type: type,
        duration: 2000,
        center: true
    })
}

export { toast }
export default { toast }
