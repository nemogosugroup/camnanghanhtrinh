<template>
    <el-col :span="colSpan" style="padding-right: 2.5em;">
        <el-row>
            <el-col :span="7">
                <el-card shadow="never" class="avatar" style="padding:0">
                    <el-avatar shape="square" :size="150" :src="showAvatar"></el-avatar>
                    <input
						type="file"
						id="avatar"
						name="avatar"
						accept="image/png, image/jpeg, image/gif"
						@change="handleBeforeUpload"
					/>
					<span class="changeImages">
						<i class="ri-camera-fill"></i>
					</span>
                </el-card>                                      
            </el-col>
            <el-col :span="17">
                <el-card shadow="never">
                    <div v-if="data_achievement" class="title_achievement">
                        <h4><span>Danh hiệu:</span>{{ this.data_achievement.medal }}</h4>
                        <h4><span>Thành tích:</span>{{ this.data_achievement.achievement }}</h4>
                    </div>
                    <h3 v-else>{{ msgAchievement }}</h3>
                </el-card>
            </el-col>
            <el-col :span="24" v-if="isUpdate">
                <div class="flex-center mt10">
                    <el-button type="danger" @click="handleCancel">Huỷ</el-button>
                    <el-button type="success" @click="handleUpdateUser" :loading="isLoading">Cập nhập</el-button>
                </div>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24">
                <h2 class="title-h2">{{ title }}</h2>
            </el-col>
            <el-col :span="24">
                <el-tabs v-model="activeNameTab" @tab-click="handleClickTabs">
                    <el-tab-pane label="Thông tin cá nhân" name="tabInfo">
                        <el-descriptions :column="1" border>
                            <el-descriptions-item v-for="item in listKeyInfo"
                            :key="item.key"
                            labelStyle="color:#000; font-weight:700;"
                            contentStyle="color:#000;font-weight:700;"
                            >
                                <template v-slot:label>
                                    <i :class="item.icon" style="margin-right: 5px;"></i>
                                    {{ item.title }}
                                </template>
                                {{ dataInfomation[item.key] }}
                            </el-descriptions-item>
                        </el-descriptions>
                    </el-tab-pane>
                    <el-tab-pane label="Thông tin đào tạo" name="tabTrain">
                        <el-table
                            v-if="dataTraining"
                            v-bind:data="dataTraining"
                            border
                            fit
                            highlight-current-row
                            style="width: 100%"
                            class="training"
                        >
                            <el-table-column type="expand">
                                <template v-slot="item">
                                    <el-descriptions :column="1" border>
                                        <el-descriptions-item v-for="value in listKeyTraining"
                                        :key="value.key"
                                        labelStyle="color:#000; font-weight:700; min-width:120px"
                                        contentStyle="color:#000;font-weight:700;"
                                        >
                                            <template v-slot:label>
                                                <i :class="value.icon" style="margin-right: 5px;"></i>
                                                {{ value.title }}
                                            </template>
                                            {{ item.row[value.key] }}
                                        </el-descriptions-item>
                                    </el-descriptions>
                                </template>
                            </el-table-column>
                            <el-table-column min-width="80px" label="Thời gian">
                                <template v-slot="item">
                                    <span>{{ item.row.starttime+' - '+ item.row.endtime}}</span>
                                </template>
                            </el-table-column>
                            <el-table-column min-width="80px" label="Khoá học">
                                <template v-slot="item">
                                    <span>{{ item.row.training_name }}</span>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-text v-else class="mx-1" size="large">{{ msg }}</el-text>
                    </el-tab-pane>
                </el-tabs>                                                
            </el-col>
        </el-row>
    </el-col>
</template>
<script>
    import { mapGetters } from "vuex";
    import RepositoryFactory from '@/utils/RepositoryFactory';    
    const UserRepository = RepositoryFactory.get('user');
    import defaultInfo from '@/views/handbook/infomation/data/index.js';    
    export default {
        name: 'Infomation',
        components: { },
        props: {
            defaultAvatar: {
                required: true,
                type: String
            },
            colSpan: {
                required: true,
                type: Number
            }
        },
        data () {   
            const { listKeyInfo, listKeyTraining } = defaultInfo;
            return { 
                activeNameTab: 'tabInfo',
                dataInfomation: [],              
                listKeyInfo: listKeyInfo,
                listKeyTraining: listKeyTraining,
                dataTraining: [],
                title: 'Thông tin cá nhân',
                avatar: '',
                fileImages:'',
                msg: 'Bạn chưa có khoá đào tạo nào',
                msgAchievement: 'Vui lòng cập nhập thông tin',
                isUpdate: false,
                isLoading: false,
                formdata:{
                    file:false,
                    data:'',
                    id:''
                },
            }            
        },
        computed: {
            ...mapGetters(["user", "training", "data_achievement"]),
            showAvatar() {
                return this.avatar;
            },
        },
        watch: {
        },
        filters:{
            
        },

        created() {
            this.dataTraining = this.training;
            this.dataInfomation = this.user;
            this.avatar = this.user.avatar ? this.user.avatar : this.defaultAvatar;
        },

        mounted() {
            this.emitter.on("is-changed-medal", data => {
                this.data_achievement.medal = data.medal.title;
                this.data_achievement.achievement = data.achievement.title;
            });
        },

        methods: {
            handleClickTabs(tab, event) {
                //console.log(tab, event);
            },

            handleBeforeUpload(e) {
                let file =  e.target.files[0];			
                const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif']; // Các loại file hình ảnh được chấp nhận
                if (!allowedImageTypes.includes(file.type)) {
                    this.$message.error('Chỉ được phép tải lên các hình ảnh!');
                    return;
                }
                // Kiểm tra kích thước file
                const maxSizeInMB = 4;
                const isLt4M = file.size / 1024 / 1024 < maxSizeInMB;
                if (!isLt4M) {
                    this.$message.error(`Kích thước file không được vượt quá ${maxSizeInMB}MB!`);
                    return;
                }
                //this.$emit('updateAvatar', file)
                this.formdata.file = file;
                this.formdata.id = this.user.id;
                this.avatar = URL.createObjectURL(file);
                this.isUpdate = true;
            },

            async handleUpdateUser(data){
                try {
                    //this.isLoading = true;
                    this.formdata.data = {
                        'first_name': this.user.first_name,
                        'last_name': this.user.last_name,
                    };
                    let formData = new FormData();
                    formData.append('data', JSON.stringify(this.formdata.data));
                    formData.append('id',  this.formdata.id);
                    if (this.formdata.file) {
                        formData.append('file', this.formdata.file);
                    }
                    const { data } = await UserRepository.update(formData); 
                    if(data.success){
                        let results = data.data;
                        this.$store.dispatch("user/update", results);
                        this.$message({
                            type: 'success',
                            message: data.message,
                        })
                    }else{
                        this.$message({
                            type: 'error',
                            message: data.message,
                        })
                    }
                    //this.isLoading = false;
                    this.isUpdate = false;
                } catch (error) {
                    console.log('Cập nhập thông tin user lỗi', error)
                }
            },
            handleCancel(){
                this.isUpdate = false;
                this.avatar = this.user.avatar;
            }
        }
    }

</script>
<style lang="scss" scoped>
.title-h2{
    margin:20px 0;
    padding: 5px;
    border: 1px solid #EBEEF5;
    text-align: center;
}
.avatar :deep(.el-card__body) {
    padding: 0;
    display: flex;
    position: relative;
    cursor: pointer;
}
#avatar {
    position: absolute;
    z-index: 8;
    cursor: pointer;
    opacity: 0;
    width: 100%;
    height: 100%;
}
.avatar {
    width: 150px;
    height: 150px;
    .changeImages {
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0,0,0,.5);
        color: #fff;
        font-size: 40px;
        transition: all .3s ease-in-out;
        opacity: 0;
    }
    &:hover{
        .changeImages {
            transition: all .3s ease-in-out;
            opacity: 1;
        }
    }
}
.el-card {
    height: 100%;
}
.title_achievement {
    span{
        font-weight: 400;
        text-transform: none;
    }
    text-transform: uppercase;
}
.training {
    :deep(.el-descriptions__label) {
        width: 200px;
    }
}

</style>