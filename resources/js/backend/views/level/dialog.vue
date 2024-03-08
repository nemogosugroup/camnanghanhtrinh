<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
            class="formData"
        >
            <el-form-item label="Level" prop="level">
                <el-input-number
                    placeholder="Level"
                    v-model="formData.level"
                    :controls="false"
                    :min="1"
                ></el-input-number>
            </el-form-item>
            <el-form-item label="Điểm kinh nghiệm" prop="experience">
                <el-input-number
                    placeholder="Điểm kinh nghiệm"
                    v-model="formData.experience"
                    :controls="false"
                    :min="1"
                ></el-input-number>
            </el-form-item>
            <el-form-item label="Vàng" prop="gold">
                <el-input-number
                    :controls="false"
                    placeholder="Vàng"
                    v-model="formData.gold"
                    :min="1"
                ></el-input-number>
            </el-form-item>
            <el-form-item label="Phần thưởng" style="margin-bottom: 0px;">
                <el-select-v2 
                    filterable 
                    v-model="formData.equipment_id" 
                    style="width: calc(100%);" 
                    placeholder="Chọn phần thưởng"
                    :options="listEquipments"
                    :props="optionProps"
                    clearable
                >
                </el-select-v2>
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
    const levelAdminRepository = AdminRepositoryFactory.get('level');
    export default {
        name: 'DialogLevel',
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
                default: 'Tạo danh mục mới'
            },
            formData:{
                type:Object,
                default:{
                    level: 1,
                    experience: 1,
                    gold: 1,
                    equipment_id: null,
                }
            },
        },
        data () {            
            return {
                list: [],
                isLoading: true,
                dialogVisible: false,
                isLoadingService: false,
                optionProps: {
                    label: 'title',
                    value: 'id',
                },
                listEquipments: [],
                rules: {
                    level: [
                        { required: true, message: 'Vui lòng nhập level', trigger: 'blur' },
                    ],
                    experience: [
                        { required: true, message: 'Vui lòng nhập điểm kinh nghiệm', trigger: 'blur' },
                    ],
                    gold: [
                        { required: true, message: 'Vui lòng nhập vàng', trigger: 'blur' },
                    ],
                },
                isLoadingSave:false,
                isShowDialog: this.dialogFormVisible
            }            
        },

        filters:{
            
        },

        created() {
            this.emitter.off("list-equipment-data");
        },
        watch: {
            dialogFormVisible(newValue) {
                this.isShowDialog = newValue;
            },
        },
        mounted() {
            this.emitter.on("list-equipment-data", data => {
                this.listEquipments = data;
            });
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
                                'level' : this.formData.level,
                                'experience' : this.formData.experience,
                                'gold' : this.formData.gold,
                                'equipment_id' : this.formData.equipment_id ?? null,
                            }                                           
                            const query = {
                                "data": dataUpdate,
                                "id": this.formData.id,
                            }
                            levelAdminRepository.update(query).then( response => {
                                const { data } = response
                                this.isLoadingSave = false;
                                if(data.success){
                                    if (!data.data) {
                                        this.$message({
                                            message: data.message,
                                            type: 'error'
                                        });
                                        return;
                                    }
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
                            levelAdminRepository.create(this.formData).then( response => {
                                this.isLoadingSave = false
                                const { data } = response;
                                if(data.success){
                                    if (!data.data) {
                                        this.$message({
                                            message: data.message,
                                            type: 'error'
                                        });
                                        return;
                                    }
                                    this.$message({
                                        message: data.message,
                                        type: 'success'
                                    })
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
    :deep(.el-select) {
        width: 100%;
    }
    :deep(.el-input__inner){
        text-align: left !important;
    }
    .el-input-number {
        width: 100%;
    }
    :deep(.el-form-item--default .el-form-item__label){
        font-weight: bold;
    }
</style>