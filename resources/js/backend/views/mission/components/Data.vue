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
        <el-table-column width="50" label="ID">
            <template v-slot="item">
                <span>{{ item.row.id }}</span>
            </template>
        </el-table-column>         
        <!-- tiêu đề -->
        <el-table-column min-width="120px" label="Tiêu đề">
            <template v-slot="item">
                <span>{{ item.row.title }}</span>
            </template>
        </el-table-column>     
        <!-- danh mục -->
        <el-table-column min-width="80px" label="Danh mục">
            <template v-slot="item">
                <span>{{ item.row.category.title }}</span>
            </template>
        </el-table-column>
        <!-- Mô tả -->
        <el-table-column min-width="80px" label="Mô tả">
            <template v-slot="item">
                <el-text line-clamp="2">{{ item.row.description }}</el-text>
            </template>
        </el-table-column>
        <!-- link -->
        <el-table-column min-width="80px" label="link">
            <template v-slot="item">
                <span>{{ item.row.link }}</span>
            </template>
        </el-table-column>        
        <!-- Cách thức -->
        <el-table-column min-width="120px" label="Cách thức">
            <template v-slot="item">
                <el-text line-clamp="2">{{ item.row.method }}</el-text>
            </template>
        </el-table-column>
        <!-- Phần thưởng trang bị -->
        <el-table-column min-width="120px" label="Phần thưởng trang bị">
            <template v-slot="item">
                <span>{{ item.row.equipment?.title ?? 'Không có' }}</span>
            </template>
        </el-table-column>
        <!-- Vàng -->
        <el-table-column min-width="80px" label="Vàng">
            <template v-slot="item">
                <span>{{ commasThousands(item.row.gold) }}</span>
            </template>
        </el-table-column>
        <!-- điểm kinh nghiệm -->
        <el-table-column min-width="80px" label="Điểm kinh nghiệm">
            <template v-slot="item">
                <span>{{ commasThousands(item.row.experience) }}</span>
            </template>
        </el-table-column>
        <!-- level -->
        <el-table-column min-width="40px" label="Level">
            <template v-slot="item">
                <span>{{ item.row.level.level }}</span>
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
        name: 'TableMission',
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
            },
            commasThousands(number) {
                return number?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }

</script>