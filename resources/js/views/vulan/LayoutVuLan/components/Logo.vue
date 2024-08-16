<template>
    <div class="sidebar-logo-container" :class="{ 'collapse': collapse }">
        <transition name="sidebarLogoFade">
            <router-link v-if="checkUser" class="sidebar-logo-link" to="/so-tay-hanh-trinh">
                <img v-if="logo" :src="logo" class="sidebar-logo">
            </router-link>
            <router-link v-else class="sidebar-logo-link" to="/">
                <img v-if="logo" :src="logo" class="sidebar-logo">
            </router-link>
        </transition>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
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
    computed: {
        ...mapGetters(["user"]),
    },
    data() {
        return {
            logo: imagesLogo,
            icon: imagesIcon,
            checkUser: false
        }
    },
    created() {
        this.checkUser = Object.keys(this.user).length > 0 ? true : false;
    },
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
    width: auto;
    height: 50px;
    line-height: 50px;
    overflow: hidden;

    & .sidebar-logo-link {
        height: 100%;
        width: 100%;

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
