<template>
    <!-- Dialog Update -->
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-form
            ref="formData"
            :model="formData"
            v-bind:rules="rules"
            label-width="160px"
        >
            <el-form-item label="Tên" prop="title">
                <el-input
                    placeholder="Tên trang bị"
                    v-model="formData.title"
                ></el-input>
            </el-form-item>
            <el-form-item label="Loại" prop="type_equipment_id">
                <el-select v-model="formData.type_equipment_id" class="m-2" placeholder="Loại">
                    <el-option
                    v-for="item in listTypes"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Hạng mục" prop="medal_id">
                <el-select v-model="formData.medal_id" class="m-2" placeholder="Hạng mục">
                    <el-option
                    v-for="item in listMedals"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="Mô tả thiết kế" prop="description_designer">
                <el-input
                    class="m-2"
                    v-model="formData.description_designer"
                    autosize
                    type="textarea"
                    placeholder="Mô tả thiết kế"
                />
            </el-form-item>
            <el-form-item label="Mô tả trong game" prop="description_in_game">
                <el-input
                    class="m-2"
                    v-model="formData.description_in_game"
                    autosize
                    type="textarea"
                    placeholder="Mô tả trong game"
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
            <el-form-item label="Hình ảnh" prop="url_image">
                <el-input
                    placeholder="Hình ảnh"
                    v-model="formData.url_image"
                >
                    <template #append>
                        <el-button type="primary" @click="handlerCkfinder"><i class="ri-upload-cloud-fill"></i></el-button>
                    </template>
                </el-input>
                <el-image
                    v-if="formData.url_image"
                    :src="formData.url_image"
                    style="width: 200px; height: 200px; object-fit: cover;"
                ></el-image>
            </el-form-item>
            <el-form-item label="Gold" prop="gold">
                <el-input-number :min="1" v-model="formData.gold" :step="1" />
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
    const equipmentRepository = AdminRepositoryFactory.get('equipment');
    export default {
        name: 'DialogEquipment',
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
                default: 'Tạo trang bị mới'
            },
            formData:{
                type:Object,
                default:{
                    title: '',
                }
            },
            listTypes:{
                type:Array,
                default:[]
            },
            listMedals:{
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
                        { required: true, message: 'Vui lòng nhập tên', trigger: 'blur' },
                    ],
                    type_equipment_id: [
                        { required: true, message: 'Vui lòng chọn loại', trigger: 'blur' },
                    ],
                    medal_id: [
                        { required: true, message: 'Vui lòng chọn hạng mục', trigger: 'blur' },
                    ],
                    description_in_game: [
                        { required: true, message: 'Vui lòng nhập mô tả trong game', trigger: 'blur' },
                    ],
                    method: [
                        { required: true, message: 'Vui lòng nhập cách thức', trigger: 'blur' },
                    ],
                    url_image: [
                        { required: true, message: 'Vui lòng nhập URL hình ảnh', trigger: 'blur' },
                    ],
                    gold: [
                        { required: true, message: 'Vui lòng nhập gold', trigger: 'blur' },
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
                        if(this.dialogStatus == 'edit'){console.log(this.formData)
                            let dataUpdate = {
                                'title' : this.formData.title,
                                'type_equipment_id': this.formData.type_equipment_id,
                                'medal_id': this.formData.medal_id,
                                'description_designer': this.formData.description_designer,
                                'description_in_game': this.formData.description_in_game,
                                'method': this.formData.method,
                                'url_image': this.formData.url_image,
                                'gold': this.formData.gold
                            }                                           
                            const query = {
                                "data": dataUpdate,
                                "id": this.formData.id,
                            }
                            equipmentRepository.update(query).then( response => {
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
                            equipmentRepository.create(this.formData).then( response => {
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
            handlerCkfinder(){
                this.showCkfinder();
            },
            showCkfinder() {
                const _this = this;
                var finder = new CKFinder();

                finder.selectActionFunction = function(fileUrl, data){
                    _this.formData.url_image = fileUrl;
                };
                finder.popup();
            },
        }
    }

</script>
<style lang="scss" scoped>
    :deep(.el-select) {
        width: 100%;
    }
    :deep(.el-image) {
        width: 100%;
        padding-top: 20px;
        > img {
            width: auto;
        }
    }
</style>