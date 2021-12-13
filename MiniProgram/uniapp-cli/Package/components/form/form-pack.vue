<template>
    <view>

        <slot></slot>

    </view>
</template>

<script>
    import {defineInfo, verifyRules} from "./form-utils.js";
    export default {
        components: {},
        data: () => ({
            info: null
        }),
        props: {
            formData: {
                type: Object,
                default: () => ({})
            },
            rules: {
                type: Object,
                default: () => ({})
            },
        },
        beforeCreate: function() {},
        created: function() {
            this.info = defineInfo(this.rules);
        },
        filters: {},
        computed: {},
        methods: {
            verifyRules: function(){
                return verifyRules(this.formData, this.rules, this.info);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
