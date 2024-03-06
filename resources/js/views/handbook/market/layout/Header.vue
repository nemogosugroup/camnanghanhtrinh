<template>
    <el-header>
        <el-row :gutter="20" class="nav-market">
            <el-col :span="16">
                <el-menu
                    :default-active="type"
                    class="menu-header"
                    mode="horizontal"
                >
                <el-menu-item v-for="(route, index) in listRouter" :key="index" :index="route.type">                        
                        <router-link :to="route.path">
                            {{ route.title }}
                        </router-link>
                    </el-menu-item>
                </el-menu>
            </el-col>
            <el-col :span="8">
                <div class="flex-center flex-align-end wp-gold">
                    <span class="gold">
                        <strong><i class="ri-money-dollar-circle-fill"></i></strong> 
                        <span>{{ gold }}</span>
                    </span>
                </div>
            </el-col>
        </el-row>
    </el-header>
</template>
<script>
    import { mapGetters } from "vuex";
    import Helper from '@/helper';
    export default {
        name: 'HeaderMaket',
        components: {},
        data () {            
            return {
                listRouter: [
                    {
                        'path' : '/so-tay-hanh-trinh/market/khoa-hoc',
                        'title' : 'Khoá học',
                        'type': Helper.typeMarket.course
                    },
                    {
                        'path' : '/so-tay-hanh-trinh/market/trang-bi',
                        'title' : 'Trang bị',
                        'type': Helper.typeMarket.equipment
                    }                    
                ]
            }
        },
        computed: {
            ...mapGetters(["user", "gold"]),
            type() {
                const route = this.$route
                const { meta } = route;
                return meta.type
            },
            typeMarket(){
                return Helper.typeMarket;
            },
            activeMenu() {
                const route = this.$route
                const { path } = route
                return path
            },
        },

        created() {
        },

        methods: {
        }
    }
</script>
<style lang="scss" scoped>
@import '@style/variables.scss';
.nav-market {
    .menu-header {
        border:0;
        background-color: transparent;
        .el-menu-item {
            padding:0 10px 0 0px!important;
            margin-right: 10px;
            border-bottom: 0;
            position: relative;
            &::after {
                content:'';
                position:absolute;
                right: 0;
                height: calc(100% - 20px);
                width: 1px;
                background:$lightGray;
            }
            &:hover{
                background: transparent !important;            
            }
            &.is-active {
                border: 0
            }
            &:last-child{
                margin-right: 0;
                padding-right: 0 !important;
                &::after {
                    content:none;
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
                transition: all 0.3s ease-in-out;
                -moz-transition: all 0.3s ease-in-out;
                -webkit-transition: all 0.3s ease-in-out;
                line-height: 40px;
                background: rgb(237 236 236);
                box-shadow: none;
                text-align: center;
                border-radius: 10px; 
                top:0;
                text-transform: uppercase;
                &:hover{
                    transition: all 0.3s ease-in-out;
                    -moz-transition: all 0.3s ease-in-out;
                    -webkit-transition: all 0.3s ease-in-out;
                    box-shadow: $boxShadow;
                    top: -2px
                }           
            }
            &.is-active {
                a{
                    transition: all 0.3s ease-in-out;
                    -moz-transition: all 0.3s ease-in-out;
                    -webkit-transition: all 0.3s ease-in-out;
                    box-shadow: $boxShadow;
                    top: -2px
                }
            }
        }
    }
}
</style>