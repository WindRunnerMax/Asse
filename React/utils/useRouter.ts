import { useNavigate } from "react-router-dom";
import { data } from "src/modules/global-data";
import { toast } from "src/modules/toast";

export const useRoutePath = () => {
    const navigate = useNavigate();

    return (path: string, replace = false) => {
        if (data.user === 0) {
            toast("您处于游客状态，请在山科小站中操作");
            return void 0;
        }
        navigate(path, { replace });
    };
};
