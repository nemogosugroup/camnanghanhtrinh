<template>
    <div class="filter-container f-left">
        <el-input placeholder="Tên" v-model="title" style="width: 400px;" class="filter-item"/>
        <el-select v-model="position" placeholder="Vị trí" style="width: 250px;margin-right: 10px" class="filter-item">
            <el-option
                v-for="item in listPositions"
                :key="item.value"
                :label="item.name"
                :value="item.value"
            />
        </el-select>
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
        name: 'SearchTypeEquipment',
        props: {
            listQuery: {
                type: String,
                default: ''
            },
            listPositions:{
                type:Array,
                default:[]
            }
        },
        data () {       
            return {
                title:this.listQuery.title,
                position:this.listQuery.position,
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
                    this.title = '';
                    this.position = '';
                }
                this.$emit('search', {
                    title: this.title,
                    position: this.position
                })
            }
        }
    }

</script>
<style lang="scss" scoped>
    :deep(.el-input__wrapper) {
        width: calc(100% - 12px);
    }
</style>