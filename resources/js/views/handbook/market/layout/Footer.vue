<template>
    <el-footer>
        <el-row class="row-bg" justify="end">
            <el-col :span="6">
                <pagination 
                    v-show="total>8" 
                    v-bind:total="total" 
                    v-model:page="listQuery.page" 
                    v-model:limit="listQuery.limit" 
                    :layout="layout"
                    @pagination="handlefecthData" />
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
     export default {
        name: 'FooterMaket',
        components: {},
        prop:{
            listQuery: {
                type: Object,
                default: {}
            },
            layout: {
                type:String,
                default:"prev, pager, next"
            },
            total: {
                type:Number,
                default:0
            }
        },
        data () {            
            return {
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

        filters:{
            
        },

        created() {
        },

        methods: {
            handleSearch(){
                this.rules.title[0].required= true;
                this.$refs['formData'].validate((valid) => {
                    if (valid) {
                        this.$emit('search', this.searchForm.title);
                    } else {
                        return false;
                    }
                });
            },
            handleClear(){
                this.rules.title[0].required= false
                this.searchForm.title = '';
                this.$emit('search', this.searchForm.title);
            },
            handlefecthData(){
                this.$emit('pagination');
            }
        }
    }
</script>