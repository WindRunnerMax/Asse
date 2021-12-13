<template>
    <view >
        <view class="form-unit">
            <view v-if="row" class="y-center">
                <view :style="{width: width ? width + 'px': 'unset'}">{{label}}</view>
                <slot></slot>
            </view>
            <view v-if="!row">
                <view >{{label}}</view>
                <slot></slot>
            </view>
            <view v-if="show" class="err">{{msg}}</view>
        </view>
    </view>
</template>

<script>
    import {verifyRulesUnit} from "./form-utils.js";
    export default {
        components: {},
        data: () => ({
            info: {
                msg: null,
                show: null
            }
        }),
        props: {
            row: {
                type: Boolean,
                default: false
            },
            rule: {
                required: true,
                type: String,
            },
            label: {
                type: String,
                default: ""
            },
            width: {
                type: [Number, String],
                default: 0
            }
        },
        beforeCreate: function() {},
        created: function() {
            uni.$app.eventBus.on("VerifyUnit" + this.rule.toUpperCase(), this.verifyUnit);
        },
        beforeDestroy:function(){
            uni.$app.eventBus.off("VerifyUnit" + this.rule.toUpperCase(), this.verifyUnit);
        },
        filters: {},
        computed: {
            show: function(){
                if(this.info.show !== null) return this.info.show;
                return this.rule && this.$parent.info[this.rule].show;
            },
            msg: function(){
                if(this.info.msg !== null) return this.info.msg;
                return this.$parent.info[this.rule].msg;
            }
        },
        methods: {
            verifyUnit: function(){
                if(!this.rule) return void 0;
                const rules = this.$parent.rules[this.rule];
                const value = this.$parent.formData[this.rule];
                verifyRulesUnit(value, rules, this.info);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .form-unit{
        padding: 10px;
        border-bottom: 1px solid #EEEEEE;
    }
    .y-center{
        display: flex;
        align-items: center;
    }
    .err{
        color: red;
        margin-top: 10px;
    }
</style>
