<template>
    <!-- Dialog Remove -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" width="30%" @close="closeDialog">
        <span>{{ title }}</span>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="closeDialog"> Hủy </el-button>
                <el-button
                    type="primary"
                    @click="handleDelete()"
                    v-bind:loading="isLoadingSave"
                >
                    Xóa
                </el-button>
            </div>
        </template>        
        
    </el-dialog>
</template>
<script>
    export default {
        name: 'DialogRemove',
        props:{
            dialogVisibleRemove:{
                type: Boolean,
                default: false
            },
            titleDialog:{
                type: String,
                default: 'Xóa danh hiệu'
            },
            title:{
                type: String,
                default: ''
            },
        },
        data () {              
            return {
                isLoading: true,  
                isLoadingSave:false,
                isShowDialog: this.dialogVisibleRemove
            }            
        },

        filters:{
            
        },

        created() {
        },
        watch: {
            dialogVisibleRemove(newValue) {
                this.isShowDialog = newValue;
            },

        },
        methods: {
            closeDialog() {
                this.isShowDialog = false
                this.$emit('hiddendialog', {})
            },
            handleDelete(){
                this.isShowDialog = false
                this.$emit('delete');
                // if (this.listIdStores.length > 0) {
                //     this.isShowDialog = false
                //     this.$emit('deleteStore');
                // }else{
                //     this.$message({
                //         message: 'Vui lòng chọn store cần xóa',
                //         type: 'error'
                //     })
                // }
            }
        }
    }

</script>