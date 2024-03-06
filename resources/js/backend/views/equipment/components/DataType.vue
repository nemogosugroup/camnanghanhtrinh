<template>
    <el-table
        ref="dragTable"
        v-loading="isLoading"        
        row-key="id"
        border
        fit
        highlight-current-row
        style="width: 100%"
        @selection-change="handleSelectionChange"
        v-bind:data="list"
    >
        <!-- Check All -->
        <el-table-column
            type="selection"
            width="40"
            align="center">
        </el-table-column>           
        <!-- tiêu đề -->
        <el-table-column min-width="120px" label="Tên">
            <template v-slot="item">
                <span>{{ item.row.title }}</span>
            </template>
        </el-table-column>     
        <!-- danh mục -->
        <el-table-column min-width="80px" label="Danh mục">
            <template v-slot="item">
                <span>{{ item.row.category?.title }}</span>
            </template>
        </el-table-column>
        <!-- Vị trí -->
        <el-table-column min-width="80px" label="Vị trí">
            <template v-slot="item">
                <span>{{ item.row.position_value }}</span>
            </template>
        </el-table-column>
        <!-- Action -->
        <el-table-column align="center" min-width="80px" label="Sửa">
            <template v-slot="item">
                <!-- Nút sửa -->
                <el-button type="danger" size="small" @click="showDialog({'status':'delete', 'id':item.row.id, 'title':item.row.title})">Xóa</el-button>
                <el-button type="primary" size="small" @click="showDialog({'status':'edit', 'item':item.row})">Sửa</el-button>
            </template>
        </el-table-column>
    </el-table>
</template>
<script>
    export default {
        name: 'TableEquipmentType',
        props: {
            list: {
                type: Array,
                default: ''
            },
            isLoading:{
                type: Boolean,
                default: false
            },
        },
        data () {  
            return {
                multipleSelection: [],
            }           
        },

        filters:{
            
        },

        created() {},

        methods: {
            showDialog(data){
                this.$emit('showdialog', data)
            },
            handleSelectionChange(val) {
                // this.multipleSelection = val;
                // this.listIds = [];
                // if (this.multipleSelection.length > 0) {
                //     let _this = this;
                //     this.multipleSelection.filter(item => {
                //         _this.listIds.push(item.id);
                //     });
                //     this.$emit('listIds', this.listIds);
                // }
            }
        }
    }

</script>