<template>
    <span class="svg-icon">
        <Icon :iconName="iconName"/>
    </span>
</template>

<script>
    import Icon from './SvgIconGen.vue';

    export default {
        props: {
            iconName: {
                type: String,
                required: true
            }
        },
        components: {
            Icon
        }
    };
</script>

<style lang="scss" scoped>
    .svg-icon {
        display: inline-flex;
        line-height: 1;

        svg {
            width: 1em;
        }
    }
</style>
