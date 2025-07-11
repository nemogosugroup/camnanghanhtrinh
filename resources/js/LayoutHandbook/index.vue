<template>
    <div :class="classObj" class="app-wrapper">
        <div v-if="device === 'mobile' && sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
        <sidebar class="sidebar-container" />
        <div :class="{ hasTagsView: needTagsView }" class="main-container">
            <div :class="{ 'fixed-header': fixedHeader }">
                <navbar />
                <tags-view v-if="needTagsView" />
            </div>
            <LayoutBook />
        </div>
    </div>
</template>

<script>
import { Navbar, Sidebar, TagsView } from "@/layout/components";
import LayoutBook from "./components/AppBook";
import ResizeMixin from "@/layout/mixin/ResizeHandler";
import { mapState, mapGetters } from "vuex";

export default {
    name: "Layout",
    components: {
        LayoutBook,
        Navbar,
        Sidebar,
        TagsView,
    },
    mixins: [ResizeMixin],
    computed: {
        ...mapState({
            sidebar: (state) => state.app.sidebar,
            device: (state) => state.app.device,
            showSettings: (state) => state.settings.showSettings,
            needTagsView: (state) => state.settings.tagsView,
            fixedHeader: (state) => state.settings.fixedHeader,
            layout: (state) => state.user.layout,
        }),
        ...mapGetters(['layout']),
        classObj() {
            return {
                hideSidebar: !this.sidebar.opened,
                openSidebar: this.sidebar.opened,
                withoutAnimation: this.sidebar.withoutAnimation,
                mobile: this.device === "mobile",
            };
        },
    },
    methods: {
        handleClickOutside() {
            this.$store.dispatch("app/closeSideBar", {
                withoutAnimation: false,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "~@style/mixin.scss";
@import "~@style/variables.scss";

.app-wrapper {
    @include clearfix;
    position: relative;
    height: 100%;
    width: 100%;

    &.mobile.openSidebar {
        position: fixed;
        top: 0;
    }
}

.drawer-bg {
    background: #000;
    opacity: 0.3;
    width: 100%;
    top: 0;
    height: 100%;
    position: absolute;
    z-index: 999;
}

.fixed-header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9;
    width: calc(100% - #{$sideBarWidth});
    transition: width 0.28s;
}

.hideSidebar .fixed-header {
    width: calc(100% - 54px);
}

.mobile .fixed-header {
    width: 100%;
}
</style>
