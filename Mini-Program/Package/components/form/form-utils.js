const defineInfo = (rules) => {
    const info = {};
    for(let item in rules){
        const inner = {};
        inner.msg = "";
        inner.show = false;
        inner.rules = rules[item];
        info[item] = inner;
    }
    return info;
}

const verifyRulesUnit = (value, rulesArr, info) => {
    let allCheck = true;
    const checkResult = (result, info, msg) => {
        if(result){
            info.msg = "";
            info.show = false;
        }else{
            info.show = true;
            info.msg = msg;
            allCheck = false;
        }
        return result;
    }
    for(let i=0, len=rulesArr.length; i<len; ++i){
        const rule = rulesArr[i];
        if(rule.min && rule.max){
            const valueLength = value.length;
            if(!checkResult(rule.min <= valueLength && valueLength <= rule.max, info, rule.msg)) {
                break;
            }
        }
        if(rule.pattern && typeof(value) === "string"){
            if(!checkResult(new RegExp(rule.pattern).test(value), info, rule.msg)){
                break;
            }
        }
    }
    return allCheck;
}


const verifyRules = (form, rules, infos) => {
    let allCheck = true;
    for(let item in form){
        const value = form[item];
        const rulesArr = rules[item];
        if(rulesArr === void 0) continue;
        allCheck = verifyRulesUnit(value, rulesArr, infos[item]);
    }
    return allCheck;
}

const verifyUnit = {
    methods: {
        verifyUnit: function(name){
            uni.$app.eventBus.commit("VerifyUnit" + name.toUpperCase());
            return true;
        }
    }
}

export {defineInfo, verifyRules, verifyRulesUnit, verifyUnit}
