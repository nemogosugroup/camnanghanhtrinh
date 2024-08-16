<template>
    <div class="sidebar-logo-container" :class="{ 'collapse': collapse }">
        <transition name="sidebarLogoFade">
            <div v-if="collapse" key="collapse" class="sidebar-logo-link" @click="redirectRouter">
                <img v-if="icon" :src="icon" class="sidebar-logo">
            </div>
            <div v-else key="expand" class="sidebar-logo-link" @click="redirectRouter">
                <img v-if="logo" :src="logo" class="sidebar-logo">
            </div>
        </transition>
    </div>
</template>

<script>
import imagesLogo from "@/assets/images/logo/GOSU_full.png";
import imagesIcon from "@/assets/images/logo/GOSU_icon.png";
export default {
    name: 'SidebarLogo',
    props: {
        collapse: {
            type: Boolean,
            required: true
        }
    },
    data() {
        return {
            logo: imagesLogo,
            icon: imagesIcon
        }
    },
    methods: {
        redirectRouter() {
            this.$router.push(`/so-tay-hanh-trinh/`);
        }
    }
}
</script>

<style lang="scss" scoped>
.sidebarLogoFade-enter-active {
    transition: opacity 1.5s;
}

.sidebarLogoFade-enter,
.sidebarLogoFade-leave-to {
    opacity: 0;
}

.sidebar-logo-container {
    position: relative;
    width: 100%;
    height: 50px;
    line-height: 50px;
    background: #2b2f3a;
    text-align: center;
    overflow: hidden;

    & .sidebar-logo-link {
        height: 100%;
        width: 100%;
        cursor: pointer;

        & .sidebar-logo {
            height: 32px;
            vertical-align: middle;
            margin-right: 12px;
        }

        & .sidebar-title {
            display: inline-block;
            margin: 0;
            color: #fff;
            font-weight: 600;
            line-height: 50px;
            font-size: 14px;
            font-family: Avenir, Helvetica Neue, Arial, Helvetica, sans-serif;
            vertical-align: middle;
        }
    }

    &.collapse {
        .sidebar-logo {
            margin-right: 0px;
        }
    }
}
</style>
