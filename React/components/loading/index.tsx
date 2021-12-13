import styles from "./index.module.scss";
import { useMemo } from "react";

export interface Props {
    loading: "loading" | "loadmore" | "nomore";
    loadmore: () => void;
}

const Loading: React.FC<Props> = props => {
    const loadMore = () => {
        if (props.loading !== "loadmore") return void 0;
        props.loadmore();
    };
    const status = useMemo(() => {
        switch (props.loading) {
            case "loading":
                return "加载中";
            case "loadmore":
                return "点击加载更多";
            case "nomore":
                return "我也是有底线的";
        }
    }, [props.loading]);
    const Line = (
        <div
            className={`${styles.line} ${props.loading === "loading" ? styles["loader-hide"] : ""}`}
        ></div>
    );
    return (
        <div onClick={loadMore}>
            <div className={styles["load-container"]}>
                <div className={props.loading === "loading" ? "" : styles["loader-hide"]}>
                    <div className={styles.loader}></div>
                </div>
                {Line}
                <div className={styles.status}>{status}</div>
                {Line}
            </div>
        </div>
    );
};

Loading.defaultProps = {};

export default Loading;
