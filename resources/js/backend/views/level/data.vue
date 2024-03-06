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
        <!-- Level -->
        <el-table-column min-width="120px" label="Level">
            <template v-slot="item">
                <span>{{ item.row.level }}</span>
            </template>
        </el-table-column>     
        <!-- điểm kinh nghiệm -->
        <el-table-column min-width="80px" label="Điểm kinh nghiệm">
            <template v-slot="item">
                <span>{{ item.row.experience }}</span>
            </template>
        </el-table-column>
        <!-- Vàng -->
        <el-table-column min-width="80px" label="Vàng">
            <template v-slot="item">
                <span>{{ item.row.gold }}</span>
            </template>
        </el-table-column>
        <!-- Phần thưởng -->
        <el-table-column min-width="80px" label="Phần thưởng">
            <template v-slot="item">
                <span>{{ item.row.equipment?.title }}</span>
            </template>
        </el-table-column>
        <!-- Action -->
        <el-table-column align="center" min-width="50px" label="Sửa">
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
        name: 'TableLevel',
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