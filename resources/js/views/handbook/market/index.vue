<template>      
    <content-market :list="list" :total="total" @show="handleShowDialog" :courses="courses" :equipments="equipments"></content-market>
    <DialogMarket :show-dialog="showDialog"  @show="handleShowDialog" :item="item" :type="type" :isShowButton="isShowButton" @update="handleInfoUser"/>    
    <!-- <FooterMaket @search="handleSearch" :listQuery="listQuery" :total="total" :layout="layout"></FooterMaket>  -->
    <el-footer>
        <el-row class="row-bg" justify="space-between">
            <el-col :span="6">
                <pagination 
                    v-show="total>8" 
                    v-bind:total="total" 
                    v-model:page="listQuery.page" 
                    v-model:limit="listQuery.limit" 
                    :layout="layout"
                    @pagination="fetch" />
            </el-col>
            <el-col :span="6">
                <el-form
                    ref="formData"
                    :model="searchForm"
                    v-bind:rules="rules"
                >
                <el-form-item prop="title">
                    <el-input
                    placeholder="Type"
                    class="input-with-select"
                    v-model="searchForm.title"
                    clearable
                    @clear="handleClear"
                    >
                        <template #append>
                            <el-button @click="handleSearch"><i class="ri-search-line"></i></el-button>
                        </template>
                    </el-input>
                </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-footer>
</template>
<script>
    import { mapGetters } from 'vuex';
    import Pagination from '@/components/Pagination'
    import SideBarMarket from './layout/SideBar';    
    import HeaderMarket from './layout/Header';      
    import ContentMarket from './layout/Content';
    import FooterMaket from './layout/Footer';
    import DialogMarket from './layout/Dialog';
    import RepositoryFactory from "@/utils/RepositoryFactory";
    const MarketRepository = RepositoryFactory.get("market");
    import Helper from '@/helper';
    import { ElLoading } from 'element-plus'
    export default {
        name: 'HandBookMarket',
        emits: ["categories"],
        components: { 
            Pagination, 
            HeaderMarket, 
            ContentMarket,
            SideBarMarket,
            FooterMaket,
            DialogMarket
        },
        data () {            
            return {
                isLoading: true,
                list: [],
                total: 0,
                listQuery: {
                    page: 1,
                    limit: 8,
                    sort: 'desc',
                    title: '',
                    type:this.$route.meta.type, // phân biệt khoá học và trang bị
                    slug:this.$route.params.slug, // slug danh mục
                }, 
                listCategories:[],
                courses:[],
                equipments:[],
                showDialog:false,
                isShowButton:true,
                item:{}, // item khoá học hoặc item trang bị
                type: this.$route.meta.type, // kiểm tra xem là trang bị/khoá học
                isEmitCategory:true,
                layout:"prev, pager, next",
                searchForm: {
                    title: '',
                },
                rules: {
                    title: [
                        { required: true, message: 'Vui lòng nhập dữ liệu' },
                    ],
                },
            }            
        },
        computed: {
            //...mapGetters(["user", "courses", "equipments"]),
            ...mapGetters(["user"]),
            // type(){
            //     return this.$route.meta.type
            // }
        },
        filters:{
            
        },

        created() {
            this.fetch();
        },

        methods: {
            async fetch(){
                // const loading = ElLoading.service({
                //     lock: true,
                //     background: 'rgba(0, 0, 0, 0.7)'
                // });
                this.isLoading = true;     
                const { data } = await MarketRepository.list( this.listQuery );
                this.isLoading = false;                  
                if(data.success) {
                    const results = data.data.data;
                    this.listCategories = data.categories;                    
                    this.list = results.map(item => {
                        return item
                    });
                    this.total = data.data.total;
                    if(this.isEmitCategory){
                        this.$emit('categories', this.listCategories)
                        this.isEmitCategory = false;
                    }  
                    if(data.courses){
                        this.courses = data.courses.map(item => {
                            return item.id
                        });
                    }  
                    if(data.equipments){
                        this.equipments = data.equipments.map(item => {
                            return item.id
                        });
                    } 
                }
            },
            handleSearch(){
                this.rules.title[0].required= true;
                this.$refs['formData'].validate((valid) => {
                    if (valid) {
                        this.listQuery.title = this.searchForm.title;
                        this.listQuery.page = 1;
                        this.fetch();
                    } else {
                        return false;
                    }
                });
            },
            handleClear(){
                this.rules.title[0].required= false
                this.listQuery.title = '';
                this.listQuery.page = 1;
                this.fetch();
            },
            // show/hide dialog
            handleShowDialog(data){
                this.item = data.item;
                this.showDialog = data.isShow;
                this.isShowButton = data.isShowButton;
            },
            // update user
            handleInfoUser(data){
                console.log('data', data);
                if (this.type == Helper.typeMarket.course) {
                    this.courses.push(data.id)
                    this.$store.commit('user/SET_COURSES', this.courses);
                }else{
                    this.equipments.push(data.id)
                    this.$store.commit('user/SET_EQUIPMENTS', this.equipments);
                }
                this.$store.commit('user/SET_GOLD', data.gold);
            }
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
.content-market {
    article {
        height: 100%;
    }
}
.pagination-container {
    margin: 0;
    padding:0
}
</style>