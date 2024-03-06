<template>      
    <div class="common-layout">
        <el-container class="wrapper"> 
            <SideBarMarket :list-categories="listCategories"></SideBarMarket> 
            <el-container class="is-vertical">
                <header-market></header-market>                                          
                <router-view v-slot="{ Component }" >
                    <component
                        :is="Component"
                        :key="key"
                        @categories="getListCategories"
                    />                                                      
                </router-view>                                               
            </el-container>
        </el-container>
    </div>                
</template>
<script>
    import SideBarMarket from '@/views/handbook/market/layout/SideBar';    
    import HeaderMarket from '@/views/handbook/market/layout/Header';
    export default {
        name: 'LayoutMarket',       
        components: { 
            HeaderMarket, 
            // ContentMarket,
            // FooterMaket,
            SideBarMarket,
            // FooterMaket,
            // DialogMarket
        },
        data () {            
            return {
                listCategories:[],
            }            
        },
        computed: {
            key() {
                return this.$route.path;
            }, 
        }, 
        filters:{
            
        },

        created() {
        },

        methods: {
            getListCategories(categories){
                this.listCategories = categories
            }
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
</style>