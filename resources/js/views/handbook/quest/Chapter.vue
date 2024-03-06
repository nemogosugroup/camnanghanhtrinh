<template>
    <el-row :gutter="20">
        <el-col :span="12">
            <h2 class="uppercase">{{ title }}</h2>
        </el-col>
        <el-col :span="12">
            <div class="wrap-carousel" style="height: 70px">
                <el-carousel 
                    :interval="5000" 
                    arrow="always" 
                    :autoplay="false" 
                    :loop="true"
                    @change="handleChangeChapters"
                    indicator-position="none"
                >
                    <el-carousel-item v-for="item in listCategories" :key="item.id" style="height: 70px" :name="item.slug">
                        <h4 justify="center" class="uppercase">{{ item.title }}</h4>
                    </el-carousel-item>
                </el-carousel>
            </div>
        </el-col>
    </el-row>    
</template>

<script>
    export default {
        name: 'Chapter',
        components: { },
        props: {
            listChapters:{
                type: Object,
                default:{}
            }
        },
        data () {            
            return {
                sub_category_id : 0,
            }            
        },

        computed: {

            listCategories(){
                return this.listChapters?.sub_categories ?? [];
            },

            title(){
                return this.listChapters?.title ?? 'Nhiệm vụ';
            }

        },

        filters:{
            
        },

        created() {},

        methods: {
            handleChangeChapters(index) {
                this.sub_category_id = this.listCategories[index].id;
                let data = {
                    'chapter' : true,// không lấy dữ liệu của chapter khi thay đổi chapter
                    'sub_category_id' : this.sub_category_id
                }
                this.$emit('updateChapter', data);
            },
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/variables.scss';
.wrap-carousel {
    position: relative;
    ::v-deep(.el-carousel__container){
        height: 70px !important;
        .el-carousel__item {
            h4 {
                width: 100%;
                text-align: center;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                align-content: stretch;
                height: 100%;
                margin: 0;
            }
        }
        ::v-deep(.el-carousel__arrow) {
            background-color: transparent;
            .el-icon {
                color: $colorBlack;
                font-size: $size20;
            }
        }
    }
}
</style>