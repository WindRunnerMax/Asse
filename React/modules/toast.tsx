import { Modal, message } from "antd";
import { ExclamationCircleOutlined } from "@ant-design/icons";

/**
 * 弹窗提示
 */
export const toast = (
    content: string,
    type: "success" | "error" | "info" | "warn" = "info",
    time = 2
): Promise<void> => {
    return new Promise(resolve => {
        message[type]({
            content,
            duration: time,
            onClose: () => resolve(),
        });
    });
};

export const confirm = (title: string, content: string): Promise<boolean> => {
    return new Promise<boolean>(resolve => {
        Modal.confirm({
            title,
            icon: <ExclamationCircleOutlined />,
            content: content,
            okText: "确认",
            cancelText: "取消",
            maskClosable: true,
            onOk: () => resolve(true),
            onCancel: () => resolve(false),
        });
    });
};
