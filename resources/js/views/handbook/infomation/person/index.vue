<template>
    <el-col :span="colSpan" style="padding-left: 2.5em;">
        <el-row>
            <el-col :span="24"> 
                <el-row>
                    <el-col v-show="!isCheckShare" :span="7"> 
                        <el-avatar shape="square" :size="150" :src="infomation.avatar"></el-avatar>
                    </el-col>
                    <el-col :span="col"> 
                        <el-form ref="formData" :model="formData" v-bind:rules="rules" label-width="120px">
                            <el-form-item label="Danh hiệu" prop="medal_id" style="margin-bottom: 10px;">
                                <el-col v-bind:span="24">
                                    <el-select
                                        filterable
                                        v-model="formData.medal_id"
                                        style="width: calc(100%);"
                                        placeholder="Chọn danh hiệu"
                                        @change="handleUpdateMedal"
                                    >
                                        <el-option
                                        v-for="item in dataMedal.medals"
                                        :key="item.id"
                                        :label="item.title"
                                        :value="item.id">
                                        </el-option>
                                    </el-select>
                                </el-col>
                            </el-form-item>
                            <el-form-item label="Thành tích" prop="category_id" style="margin-bottom: 10px;">
                                <el-col v-bind:span="24">
                                    <el-select
                                        filterable
                                        v-model="formData.category_id"
                                        style="width: calc(100%);"
                                        placeholder="Chọn thành tích"
                                        @change="handleUpdateMedal"
                                    >
                                        <el-option
                                        v-for="item in dataMedal.medal_categories"
                                        :key="item.id"
                                        :label="item.title"
                                        :value="item.id">
                                        </el-option>
                                    </el-select>
                                </el-col>
                            </el-form-item> 
                            <el-form-item label="ID nhân viên" style="margin-bottom: 0px;" prop="profile_id">
                                <el-col v-bind:span="24">
                                    <el-select-v2 
                                        filterable 
                                        v-model="formData.profile_id"
                                        style="width: calc(100%);" 
                                        :options="listEmployee"
                                        :placeholder="infomation.fullname"
                                        :props="optionProps"
                                        clearable
                                        @change="handleSearchEmployee"
                                    >
                                    </el-select-v2>
                                </el-col>
                            </el-form-item>
                        </el-form> 
                    </el-col>
                </el-row>                      
            </el-col>
            <el-col :span="24">  
                <h2 class="title-h2">{{ title }}</h2> 
                <div class="flex-center">
                    <span class="level"><strong>Level {{ infomation.level }}</strong></span>
                    <div class="progress"> 
                        <label for="">{{ infomation.experience }} EXP</label>
                        <el-progress :text-inside="false" :show-text="false" :stroke-width="10" :percentage="infomation.percentage" status="success"></el-progress>
                    </div>
                </div>
                <div class="block">
                    <!-- <el-image :src="imagePerson" @click="handleRedirect"></el-image> -->
                    <div id="equippedItems" class="block" @click="handleRedirect">
                        <el-image :src="imagePerson"></el-image>
                        <div class="pos" v-if="imagePerson">
                            <div
                                v-for="item in userEquipments.positions"
                                :key="item.value"
                                class="item"
                                :class="item.slug"
                                :style="`${equippedItems[item.value] ? `background-image: url(${equippedItems[item.value].url_image})` : ''}`"
                            >
                                <div v-if="!equippedItems[item.value]">{{ item.name }}</div>
                            </div>
                        </div>
                    </div>
                    <h3 class="title-h2">{{ infomation.fullname }}</h3>
                    <Socials v-bind:isCheckShare="isCheckShare"></Socials>
                </div>
            </el-col>   
        </el-row>
    </el-col>
</template>
<script>
    import { mapGetters } from 'vuex';
    import RepositoryFactory from '@/utils/RepositoryFactory';
    const UserRepository = RepositoryFactory.get('user'); 
    const UserEquipmentRepo = RepositoryFactory.get('userEquipment');
    import Helpers from '@/helper';
    import imagePerson from '@/assets/images/nhanvat.png';
    import Socials from '@/components/Socials';
    import { ElLoading } from 'element-plus'
    export default {
        name: 'InfoPerson',
        components: { Socials},
        props: {
            defaultAvatar: {
                type: String
            },
            colSpan: {
                required: true,
                type: Number
            }
        },
        data () {
            return { 
                listTitles: [],
                listAwards: [],
                dataMedal: {},
                valEmployeeID: null,
                formData: {
                    medal_id: '',
                    category_id: '',
                    profile_id: null,
                },
                rules: {
                    // profile_id: [
                    //     { required: true, message: 'Vui lòng chọn nhân sự', trigger: 'blur' },
                    // ], 
                },
                title: 'Thông tin nhân vật',
                imagePerson: imagePerson,
                isCheckShare:true,
                isLoadingSearch:false,
                dataPerson: [],
                showAvatar:this.defaultAvatar,
                col:24,
                listEmployee:[],
                infomation : {
                    experience:0,
                    level:1,
                    gold:0,
                    percentage:0,
                    fullname:'',
                    avatar:'',
                },
                optionProps: {
                    label: 'fullname',
                    value: 'profile_id',
                },
                equippedItems: [],
                currentEquippedItems:[],
                medals:[],
                categories:[],
            }            
        },
        computed: {
            ...mapGetters(["user", "data_medal", "userEquipments", "experience", "level_user", "gold"]),
            infoUser() {
                return this.user;
            },
        },
        filters:{
            
        },
        created() {
            this.infomation.experience = this.experience;
            this.infomation.level = this.level_user ? this.level_user : 1;
            this.infomation.gold = this.gold;
            this.infomation.fullname = this.infoUser.fullname;
            this.infomation.percentage = this.percent(this.infoUser);
            this.fetch(); // open after have employee list
            this.fetchEquippedItems();
            this.currentEquippedItems = this.equippedItems;
            this.dataMedal = this.data_medal;
            this.formData.category_id = this.user.achievements?.medal_category;
            this.formData.medal_id = this.user.achievements?.medal;
        },

        mounted() {
        },

        methods: {
            handleRedirect(){
                if (this.isCheckShare) {
                    this.$router.push('/so-tay-hanh-trinh/trang-bi');
                }
            },
            async fetch() {
                const { data } = await UserRepository.listEmployee();
                if (data.success) {
                    let results = data.data;
                    this.listEmployee = results.map( item => item.profile_id != this.infoUser.profile_id ? item : false );
                }
            },
            async fetchEquippedItems(eId = null) {
                const params = { profile_id: eId }; // for filter by profile_id
                const {data} = await UserEquipmentRepo.listEquippedItems(params);
                if (data.success) {
                    this.equippedItems = data.data;
                    this.currentEquippedItems = data.data;
                }
            },
            async handleUpdateMedal() {
                try {
                    this.formData.data = {
                        'achievements': {
                            medal_category: this.formData.award,
                            medal: this.formData.title
                        },
                    };

                    this.formData.id = this.user.id // user id
                    let formData = new FormData();
                    formData.append('data', JSON.stringify(this.formData.data));
                    formData.append('id',  this.formData.id);
                    const { data } = await UserRepository.update(formData);
                    if(data.success){
                        let results = data.data;
                        this.$store.dispatch("user/update", results);
                        this.$message({
                            type: 'success',
                            message: data.message,
                        });
                        const newMedal = this.dataMedal.medals.filter(medal => {
                            return medal.id === this.formData.title;
                        })[0];
                        const newAchievement = this.dataMedal.medal_categories.filter(achievement => {
                            return achievement.id === this.formData.award;
                        })[0];
                        this.emitter.emit("is-changed-medal", {
                            medal: newMedal,
                            achievement: newAchievement
                        });
                    }else{
                        this.$message({
                            type: 'error',
                            message: data.message,
                        })
                    }
                } catch (error) {
                    console.log('Cập nhập thông tin user lỗi', error)
                }
            },
            async handleSearchEmployee() {
                try {
                    const loading = ElLoading.service({
                        lock: true,
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    if(this.formData.profile_id){
                        this.isCheckShare = false; // kiểm tra nếu là nhân sự thì được share còn của nhân sự khác sẽ ko được share;
                        this.col = 17;
                        const { data } = await UserRepository.store(this.formData);
                        // tìm kiếm thông tin nhân sự bằng profile_id
                        if (data.success) {
                            if (data.data) {
                                const results = data.data;
                                this.infomation = Object.assign({}, results);
                                this.infomation.percentage =  this.percent(this.infomation);
                            }else{
                                this.listEmployee.filter((user) => {
                                    if (user.profile_id == this.formData.profile_id) {
                                        this.infomation = Object.assign(this.infomation, user);
                                        this.infomation.experience = this.experience;
                                        this.infomation.level = this.level_user ? this.level_user : 1;
                                        this.infomation.gold = this.gold;
                                        this.infomation.percentage = this.percent(this.infomation);
                                    }
                                })
                            }
                            if (data.equipped) {
                                this.equippedItems = data.equipped;
                            }else{
                                this.equippedItems = []
                            }
                        }
                        this.$message({
                            type: 'success',
                            message: data.message,
                        })
                    }else{
                        this.isCheckShare = true;
                        this.col = 24;
                        this.infomation = Object.assign({}, this.infoUser);
                        this.infomation.percentage = this.percent(this.infomation);
                        this.$message({
                            type: 'success',
                            message: 'Lấy dữ liệu thành công',
                        });
                        this.equippedItems = this.currentEquippedItems;
                    }
                    loading.close();
                } catch (error) {
                   console.log('error', error) 
                }                
            },
            // lấy phần trăm của điểm kinh nghiệm hiện tại và level
            percent(data){
                return Helpers.percentExp(data)
            }
        }
    }

</script>
<style lang="scss" scoped>
@import '@style/person.scss';
#equippedItems{
    .el-image{
        min-height: 370px;
    }
    .pos{
        .item{
            &.giay{
                bottom: 10px;
            }
        }
    }
}
</style>