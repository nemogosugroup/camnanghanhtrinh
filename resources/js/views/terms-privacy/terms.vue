<template>
    <div class="app-wrapper">
        <el-container>
            <div class="text-center">
                <h1>TERMS PAGE</h1>
            </div>
        </el-container>
    </div>
    <!--  -->
</template>

<script>
export default {
    name: "Terms",
    data() {
        return {
        };
    },
    watch: {
    },
    created() {
    },
    mounted() {
    },
    destroyed() {
    },
    computed: {
    },
    methods: {
    },
};
</script>

<style lang="scss" scoped>
$bg: hsl(213, 25%, 21%);
$light_gray: #fff;
$cursor: #fff;
$bg: #2d3a4b;
$bgOverlay : #292626b3;
$dark_gray: #889aa4;
$light_gray: #eee;
$width_main: calc(100% - 30%);
$width_siderbar: calc(30%);
$height_logo: 70px;

#app {
    .sidebar-container {
        width: $width_siderbar !important;
        padding: 0;

        .sidebar-logo {
            max-height: $height_logo;
        }
    }
}
</style>
