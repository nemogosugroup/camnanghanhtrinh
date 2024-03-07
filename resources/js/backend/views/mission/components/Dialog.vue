<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
        >
            <el-form-item label="Tiêu đề" prop="title">
                <el-input
                    placeholder="Tiêu đề hạng mục"
                    v-model="formData.title"
                ></el-input>
            </el-form-item>
            <el-form-item label="Danh mục" prop="sub_category_id">
                <el-select v-model="formData.sub_category_id" class="m-2" placeholder="Danh mục">
                    <el-option
                    v-for="item in listCategories"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Mô tả" prop="description">
                <el-input
                    class="m-2"
                    v-model="formData.description"
                    autosize
                    type="textarea"
                    placeholder="Mô tả"
                />
            </el-form-item>
            <el-form-item label="Link đính kèm" prop="link">
                <el-input
                    placeholder="Link"
                    v-model="formData.link"
                ></el-input>
            </el-form-item>
            <el-form-item label="Mở trình duyệt mới" prop="target">
                <el-switch
                    v-model="formData.target"
                    class="ml-2"
                    style="--el-switch-on-color: #13ce66; --el-switch-off-color: #ff4949"
                />
            </el-form-item>            
            <el-form-item label="Cách thức" prop="method">
                <el-input
                    class="m-2"
                    v-model="formData.method"
                    autosize
                    type="textarea"
                    placeholder="Cách thức"
                />
            </el-form-item>
            <el-form-item label="Level" prop="level_id">
                <el-select-v2 
                    filterable 
                    v-model="formData.level_id" 
                    style="width: calc(100%);" 
                    placeholder="Level"
                    :options="listLevels"
                    :props="optionLevelProps"
                >
                </el-select-v2>
            </el-form-item>     
            <el-form-item label="Điểm kinh nghiệm" prop="experience">
                <el-input-number
                    :controls="false"
                    placeholder="Điểm kinh nghiệm"
                    v-model="formData.experience"
                    :min="0"
                ></el-input-number>
            </el-form-item>         
            <el-form-item label="Vàng" prop="gold">
                <el-input-number
                    :controls="false"
                    placeholder="Vàng"
                    v-model="formData.gold"
                    :min="0"
                ></el-input-number>
            </el-form-item>    
            <el-form-item label="Phần thưởng" prop="equipment_id">
                <el-select-v2 
                    filterable 
                    v-model="formData.equipment_id" 
                    style="width: calc(100%);" 
                    placeholder="Trang bị"
                    :options="listEquipments"
                    :props="options"
                    clearable
                    @clear="handleClearEquipment"
                >
                </el-select-v2>
            </el-form-item>       
            <el-form-item label="Thuộc nhiệm vụ" prop="parent_id">
                <el-select-v2 
                    filterable 
                    v-model="formData.parent_id" 
                    style="width: calc(100%);" 
                    placeholder="Nhiệm vụ"
                    :options="listMissions"
                    :props="options"
                    clearable
                    @clear = "handleClearParentQuest"
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
    const missionRepository = AdminRepositoryFactory.get('mission');;
    export default {
        name: 'DialogMission',
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
                default: 'Tạo nhiệm vụ mới'
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
            listLevels:{
                type:Array,
                default:[]
            },
            listMissions:{
                type:Array,
                default:[]
            },
            listEquipments:{
                type:Array,
                default:[]
            }
        },
        data () {            
            return {
                isLoading: true,
                list: [],           
                isLoading: true,
                dialogVisible: false,
                isLoadingService: false,
                rules: {
                    title: [
                        { required: true, message: 'Vui lòng nhập tiêu đề', trigger: 'blur' },
                    ],
                    sub_category_id: [
                        { required: true, message: 'Vui lòng chọn danh mục', trigger: 'blur' },
                    ],
                    description: [
                        { required: true, message: 'Vui lòng nhập mô tả', trigger: 'blur' },
                    ],
                    method: [
                        { required: true, message: 'Vui lòng nhập cách thức', trigger: 'blur' },
                    ],
                    link: [
                        { required: true, message: 'Vui lòng nhập link', trigger: 'blur' },
                    ],
                    level_id: [
                        { required: true, message: 'Vui lòng nhập level', trigger: 'blur' },
                    ],
                },
                isLoadingSave:false,
                isShowDialog: this.dialogFormVisible,
                optionLevelProps: {
                    label: 'level',
                    value: 'id',
                },
                options: {
                    label: 'title',
                    value: 'id',
                },
            }            
        },

        filters:{
            
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
                                'parent_id' : this.formData.parent_id ? this.formData.parent_id : 0,
                                'sub_category_id' : this.formData.sub_category_id,
                                'description' : this.formData.description,
                                'link' : this.formData.link,
                                'target' : this.formData.target,
                                'method' : this.formData.method,
                                'gold' : this.formData.gold,
                                'equipment_id' : this.formData.equipment_id ? this.formData.equipment_id : 0,
                                'equipment_id' : this.formData.equipment_id ? this.formData.equipment_id : 0,
                                'experience' : this.formData.experience,
                                'level_id' : this.formData.level_id
                            }                                           
                            const query = {
                                "data": dataUpdate,
                                "id": this.formData.id,
                            }
                            missionRepository.update(query).then( response => {
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
                            this.formData.parent_id = this.formData.parentId?this.formData.parentId:0;
                            missionRepository.create(this.formData).then( response => {
                                this.isLoadingSave = false
                                const { data } = response;
                                if(data.success){
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

            // xoá nhiệm vụ cha
            handleClearParentQuest(){
                this.formData.parent_id = null
            },

            // xoá nhiệm vụ cha
            handleClearEquipment(){
                this.formData.equipment_id = null
            }
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