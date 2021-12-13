/**
 * 全局变量
 */

export interface Data {
    url: string;
    tmp: {
        [key: string]: any;
    };
    user: number;
    project: string;
    openid: string;
    [key: string]: unknown;
}

export const data: Data = {
    url: "",
    tmp: {},
    user: 0, // `0`游客 `1`用户
    openid: "",
    project: "山科小站",
};

if (process.env.NODE_ENV === "development") {
    data.url = "/api";
}

export default { data };
