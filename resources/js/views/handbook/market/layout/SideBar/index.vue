<template>
    <el-aside width="250px" class="siderbar">
        <h3 class="title-h3"> {{ title }} </h3>
        <el-scrollbar height="600px">
            <el-menu 
                :default-active="activeMenu"
                :background-color="variables.bgColor"
                :text-color="variables.textColor"
                :active-text-color="variables.activeColor"
                class="menu-category"
            >
                <el-menu-item v-for="(item, index) in listCategories" :key="index" :index="resolvePath(pathCategoryRoute+item.slug)">                        
                    <router-link :to="resolvePath(pathCategoryRoute+item.slug)">
                        <span>{{ item.title }}</span>
                    </router-link>
                </el-menu-item>
                <el-menu-item :index="resolvePath(pathRouteAll)">
                    <router-link :to="resolvePath(pathRouteAll)"><span>{{ nameRoute }}</span></router-link>
                </el-menu-item>
            </el-menu>
        </el-scrollbar>
    </el-aside>
</template>
<script>
    import LinkCategory from './Link'
    import { isExternal } from '@/utils/validate'
    import helper from '@/helper'
    import path from 'path'
     export default {
        name: 'SiderbarMaket',
        props: {
            listCategories: {
                type: Array,
                default: ''
            }
        },
        computed: {
            variables() {
                return helper.variablesMarket
            },
            activeMenu() {
                const route = this.$route
                const { path } = route
                return path
            },
            pathCategoryRoute(){
                return this.$route.meta.type == 'course' ? '/so-tay-hanh-trinh/market/danh-muc-khoa-hoc/' : '/so-tay-hanh-trinh/market/danh-muc-trang-bi/';
            },
            pathRouteAll(){
                return this.$route.meta.type == 'course' ? '/so-tay-hanh-trinh/market/khoa-hoc' : '/so-tay-hanh-trinh/market/trang-bi';
            }
        },
        components: { LinkCategory },
        data () {            
            return {
                title: this.$route.meta.name,
                // pathRoute: this.$route.meta.type == 'course' ? '/so-tay-hanh-trinh/market/danh-muc-khoa-hoc/' : '/so-tay-hanh-trinh/market/danh-muc-trang-bi/',
                // pathRouteAll: ,
                basePath:'',
                nameRoute: 'Tất cả'
            }            
        },

        filters:{
            
        },

        created() {
        },
        mounted() {
        },
        methods: {
            resolvePath(routePath) {
                if (isExternal(routePath)) {
                    return routePath
                }
                if (isExternal(this.basePath)) {
                    return this.basePath
                }
                return path.resolve(this.basePath, routePath)
            },
        }
    }
</script>
<style lang="scss" scoped>

@import '@style/variables.scss';
    .menu-category{
        .el-menu-item {
            padding:0 !important;
            &:hover {
                background-color: transparent !important
            }
            &.is-active {
                a{
                    &:before{
                        transition: all .3s ease-in-out;
                        -moz-transition: all .3s ease-in-out;
                        -webkit-transition: all .3s ease-in-out;
                        left: 0px;
                        width:100%;
                        opacity: 1;
                    }
                    span {
                        left: 10px;
                    }
                }                
            }
            a {
                padding: 0 var(--el-menu-base-level-padding);
                display: block;
                width: 100%;
                color: var(--el-menu-text-color);
                font-weight: 700;
                font-size: 1.2em;
                position: relative;
                transition: all .3s ease-in-out;
                -moz-transition: all .3s ease-in-out;
                -webkit-transition: all .3s ease-in-out;
                line-height: 40px;
                &:before{
                    content: '';
                    position: absolute;
                    transition: all .3s ease-in-out;
                    -moz-transition: all .3s ease-in-out;
                    -webkit-transition: all .3s ease-in-out;
                    width: 0;
                    height: 100%;
                    border-radius: 5px;
                    background: rgba($color: $yellow, $alpha: 0.5);
                    left: 0;
                    top: 0;
                    opacity: 0;
                }
                &:hover {
                    &:before{
                        transition: all .3s ease-in-out;
                        -moz-transition: all .3s ease-in-out;
                        -webkit-transition: all .3s ease-in-out;
                        left: 0px;
                        width:100%;
                        opacity: 1;
                    }
                    span {
                        left: 10px;
                    }
                }
                span{
                    transition: all .3s ease-in-out;
                    -moz-transition: all .3s ease-in-out;
                    -webkit-transition: all .3s ease-in-out;
                    position: relative;
                    z-index: 2;
                    left: 0;
                }
            }
        }
    }
</style>