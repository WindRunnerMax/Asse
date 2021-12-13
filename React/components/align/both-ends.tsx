import { FC } from "react";

interface Props {
    left?: string | JSX.Element;
    right?: string | JSX.Element;
    className?: string;
}

const BothEnds: FC<Props> = props => {
    return (
        <div className={"a-flex-space-between " + props.className}>
            <div>{props.left}</div>
            <div>{props.right}</div>
        </div>
    );
};

BothEnds.defaultProps = {
    left: "",
    right: "",
    className: "",
};

export default BothEnds;
