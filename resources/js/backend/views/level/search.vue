<template>
    <div class="filter-container f-left">
        <el-input placeholder="level" v-model="level" style="width: 400px;" class="filter-item"/>
        <el-button class="filter-item" type="primary" @click="handleSearch">
            <el-icon class="el-icon--left"><Search /></el-icon>Tìm kiếm
        </el-button>
        <el-button class="filter-item" type="primary" @click="handleSearch(null)">
            <el-icon class="el-icon--left"><Minus /></el-icon>Làm mới
        </el-button>
        <el-button class="filter-item" type="primary" @click="showDialog('create')">
            <el-icon class="el-icon--left"><Plus /></el-icon>Thêm
        </el-button>
    </div>
</template>
<script>
    export default {
        name: 'SearchLevel',
        props: {
            listQuery: {
                type: String,
                default: ''
            }
        },
        data () {       
            return {
                level:this.listQuery
            }            
        },
        computed: {
        },
        filters:{
            
        },

        created() {

        },


        methods: {
            showDialog(status){
                let data = { 'status': status }
                this.$emit('showdialog', data)
            },
            handleSearch(data){
                if (!data) {
                    this.level = '';
                }
                this.$emit('search', this.level)
            }
        }
    }

</script>
<style lang="scss" scoped>
    :deep .el-input__wrapper {
        width: calc(100% - 12px);
    }
</style>