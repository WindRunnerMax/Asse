declare interface WxSdk {
    NavType: { url: string; fail?: (err: Error) => void };
    miniProgram: {
        navigateTo: (data: NavType) => void;
        navigateBack: (data: NavType) => void;
        switchTab: (data: NavType) => void;
        reLaunch: (data: NavType) => void;
        redirectTo: (data: NavType) => void;
        postMessage: (data: { data: unknown }) => void;
        getEnv: (funct: (res: { miniprogram: boolean }) => void) => void;
    };
}

declare const wx: WxSdk;
