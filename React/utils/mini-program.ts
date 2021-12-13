import { confirm } from "src/modules/toast";

export const nav = (
    url: string,
    type: "nav" | "tab" | "relunch" | "back" | "redirect" = "nav"
): void => {
    const fail = (e: Error) => console.log(e);
    switch (type) {
        case "nav":
            return wx.miniProgram.navigateTo({ url, fail });
        case "tab":
            return wx.miniProgram.switchTab({ url, fail });
        case "relunch":
            return wx.miniProgram.reLaunch({ url, fail });
        case "back":
            return wx.miniProgram.navigateBack({});
        case "redirect":
            return wx.miniProgram.redirectTo({ url, fail });
    }
};

export const updateUserInfo = async () => {
    // 小程序`sdk`跳转到小程序页面更新用户信息
    return new Promise<void>(resolve => {
        console.log("小程序`sdk`跳转到小程序页面更新用户信息");
        confirm("提示", "山科小站需要您的头像信息授权，点击确定前去授权").then(choice => {
            if (choice) {
                nav("/pages/user/grant/user-info", "nav");
                resolve();
            }
        });
    });
};

export const checkUserInfo = async (funct: () => void) => {
    // confirm用户点击确认后刷新用户头像等信息
    const choice = await confirm("提示", "授权完成后需要刷新您的信息");
    // 完成之后重新执行函数获取用户信息
    if (choice) funct();
    return void 0;
};
