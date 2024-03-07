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
        <el-table-column min-width="120px" label="Tiêu đề">
            <template v-slot="item">
                <span>{{ item.row.title }}</span>
            </template>
        </el-table-column>     
        <!-- loại -->
        <el-table-column min-width="80px" label="Loại">
            <template v-slot="item">
                <span>{{ item.row.type?.title }}</span>
            </template>
        </el-table-column>
        <!-- hạng mục -->
        <el-table-column min-width="80px" label="Hạng mục">
            <template v-slot="item">
                <span>{{ item.row.medal?.title }}</span>
            </template>
        </el-table-column>
        <!-- Mô tả des -->
        <el-table-column min-width="80px" label="Mô tả des">
            <template v-slot="item">
                <el-text line-clamp="2">{{ item.row.description_designer }}</el-text>
            </template>
        </el-table-column>
        <!-- Mô tả in game -->
        <el-table-column min-width="80px" label="Mô tả in-game">
            <template v-slot="item">
                <el-text line-clamp="2">{{ item.row.description_in_game }}</el-text>
            </template>
        </el-table-column>
        <!-- cách thức -->
        <el-table-column min-width="80px" label="Cách thức">
            <template v-slot="item">
                <el-text line-clamp="2">{{ item.row.method }}</el-text>
            </template>
        </el-table-column>        
        <!-- URL Hình ảnh -->
        <el-table-column min-width="80px" label="Hình ảnh">
            <template v-slot="item">
                <el-image
                    v-if="item.row.url_image"
                    :src="item.row.url_image"
                ></el-image>
            </template>
        </el-table-column>
        <!-- gold -->
        <el-table-column min-width="80px" label="Gold">
            <template v-slot="item">
                <span>{{ commasThousands(item.row.gold) }}</span>
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
        name: 'TableEquipment',
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
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }

</script>