<template>
    <section class="app-main" id="wrap-app-main">
        <div class="app-container">            
            <el-row>
                <el-col :span="24">
                    <el-card shadow="hover">
                        <div v-if="checkLayoutValue == 'home'" class="flex-center">
                            <router-view v-slot="{ Component }" >
                                <component
                                    :is="Component"
                                    :key="key"
                                />                                                      
                            </router-view>   
                            <HandBookMenu></HandBookMenu>                            
                        </div>
                        <div v-else 
                            :class="`flex-center content-info ${checkLayoutValue == 'market' ? 'content-market' : (checkLayoutValue == 'welcome' ? 'wrap-video' : (checkLayoutValue != 'info' ? 'wrap-content' : ''))}`">
                            <section class="open-book">
                                <header></header>
                                <article v-if="checkLayoutValue == 'market'">                                                                         
                                    <LayoutMarket></LayoutMarket>
                                </article>
                                <article v-else class="article"> 
                                    <router-view v-slot="{ Component }" >
                                        <component
                                            :is="Component"
                                            :key="key"
                                        />                                                      
                                    </router-view>  
                                </article>
                                <footer></footer>
                            </section>
                            <HandBookMenu></HandBookMenu>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </div>
    </section>
</template>

<script>
import HandBookMenu from "@/views/handbook/menu";
import { getLayout } from "@/utils/auth"; // get token from cookie
import LayoutMarket from "./Market";
export default {
    name: "LayoutBook",
    components: {
        HandBookMenu,
        LayoutMarket
    },
    data () {            
        return {
            checkLayout:getLayout() 
        }            
    },
    computed: {
        cachedViews() {
        return this.$store.state.tagsView.cachedViews;
        },
        key() {
            return this.$route.path;
        },  
        checkLayoutValue() {
            return this.checkLayout;
        }
    }, 
    watch: {
        '$route': 'layout',
    },
    methods: {
        layout() {
            this.checkLayout = getLayout() ? getLayout() : false;
        }
    },
};
</script>

<style lang="scss" scoped>
.content-market {
    article {
        height: 100%;
    }
}
.app-main {
    /* 50= navbar  50  */
    min-height: calc(100vh - 50px);
    width: 100%;
    position: relative;
    overflow: hidden;
}

.fixed-header + .app-main {
    padding-top: 50px;
}

.hasTagsView {
    .app-main {
        /* 84 = navbar + tags-view = 50 + 34 */
        min-height: calc(100vh - 84px);
    }

    .fixed-header + .app-main {
        padding-top: 84px;
    }
}
</style>

<style lang="scss">
// fix css style bug in open el-dialog
.el-popup-parent--hidden {
    .fixed-header {
        padding-right: 15px;
    }
}
@import "@style/custom.scss";
</style>
