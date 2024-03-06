<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
        >
            <el-form-item label="Vị trí" prop="position">
                <el-select v-model="formData.position" class="m-2" placeholder="Vị trí">
                    <el-option
                        v-for="item in listPositions"
                        :key="item.value"
                        :label="item.name"
                        :value="item.value"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Tên" prop="title">
                <el-input
                    placeholder="Tên loại trang bị"
                    v-model="formData.title"
                ></el-input>
            </el-form-item>
            <el-form-item label="Danh mục" prop="categories">
                <el-select v-model="formData.category_id" class="m-2" placeholder="Danh mục">
                    <el-option
                    v-for="item in listCategories"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                    />
                </el-select>
            </el-form-item>
        </el-form>
        <template v-slot:footer>
            <!-- <div class="dialog-footer"> -->
                <el-button @click="closeDialog" size="default"> Hủy </el-button>
                <el-button
                    size="default"
                    type="primary"
                    @click="saveForm()"
                    v-bind:loading="isLoadingSave"
                >
                    Lưu
                </el-button>
            <!-- </div> -->
        </template>
    </el-dialog>
</template>
<script>
    import AdminRepositoryFactory from '@/backend/respository';
    const TypeEquipmentAdminRepository = AdminRepositoryFactory.get('typeEquipment');
    export default {
        name: 'DialogType',
        props:{
            dialogFormVisible:{
                type: Boolean,
                default: false
            },
            dialogStatus:{
                type: String,
                default: 'create'
            },
            titleDialog:{
                type: String,
                default: 'Tạo loại trang bị mới'
            },
            formData:{
                type:Object,
                default:{
                    title: '',
                }
            },
            listCategories:{
                type:Array,
                default:[]
            },
            listPositions:{
                type:Array,
                default:[]
            }
        },
        data () {            
            return {
                isLoading: true,
                list: [],           
                dialogVisible: false,
                isLoadingService: false,
                rules: {
                    title: [
                        { required: true, message: 'Vui lòng nhập tên loại trang bị', trigger: 'blur' },
                    ],
                    position: [
                        { required: true, message: 'Vui lòng chọn vị trí', trigger: 'blur' },
                    ],
                },
                isLoadingSave:false,
                isShowDialog: this.dialogFormVisible
            }            
        },

        filters:{
            
        },

        created() {
        },
        watch: {
            dialogFormVisible(newValue) {
                this.isShowDialog = newValue;
            },

        },
        methods: {
            closeDialog() {
                this.isShowDialog = false
                this.$emit('hidedialog', {})
            },

            //Save
            saveForm(){
                this.$refs['formData'].validate((valid) => {
                    if (valid) {
                        //this.isLoadingSave = true
                        let status = this.dialogStatus;
                        if(this.dialogStatus == 'edit'){ 
                            let dataUpdate = {
                                'title' : this.formData.title,
                                'category_id': this.formData.category_id,
                                'position': this.formData.position,
                            }                                           
                            const query = {
                                "data": dataUpdate,
                                "id": this.formData.id,
                            }
                            TypeEquipmentAdminRepository.update(query).then( response => {
                                const { data } = response
                                this.isLoadingSave = false;
                                if(data.success){
                                    this.$message({
                                        message: data.message,
                                        type: 'success'
                                    });
                                    let item = data.data;
                                    item.edit = false;
                                    item.isLoadingSave = false;
                                    item.isLoadingDelete = false;
                                    this.$emit('updateData', {'item':item, 'status':status});
                                }else{
                                    this.$message({
                                        message: data.message,
                                        type: 'error'
                                    })
                                    this.closeDialog();
                                }
                            }).catch( e => {
                                this.isLoadingSave = false
                            })
                        }else{
                            TypeEquipmentAdminRepository.create(this.formData).then( response => {
                                this.isLoadingSave = false
                                const { data } = response;
                                if(data.success){
                                    this.$message({
                                        message: data.message,
                                        type: 'success'
                                    });
                                    let item = data.data;
                                    item.edit = false;
                                    item.isLoadingSave = false;
                                    item.isLoadingDelete = false;
                                    this.$emit('updateData', {'item':item, 'status':status});
                                }else{
                                    this.$message({
                                        message: data.message,
                                        type: 'error'
                                    });
                                    this.closeDialog();
                                }
                            }).catch(e => {
                                this.isLoadingSave = false
                            })
                        }
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        }
    }

</script>
<style lang="scss" scoped>
    :deep .el-select {
        width: 100%;
    }
</style>