<template>
    <el-card class="box-card">
        <el-row :gutter="20">
            <el-col :span="8">
                <el-avatar :src="logoQuest" :size="60"/>
            </el-col>
            <el-col :span="16"><Chapter :list-chapters="listChapters" @update-chapter = "handleChangeChapter"/></el-col>
            <el-col :span="8"><el-card class="box-card">
                <UserChapter :list-data="listData" :status-quest = "statusQuest" @update-quest = "handleUpdateQuest"></UserChapter>
            </el-card></el-col>
            <el-col :span="16"><el-card class="box-card"><ListQuest :list-data="listData" :status-quest = "statusQuest" :user="infoUser" @update-quest = "handleUpdateQuest"/></el-card></el-col>
        </el-row>
    </el-card>
    <FireWorks></FireWorks>
</template>

<script>
    import { mapGetters } from 'vuex';
    import store from '@/store';
    import UserChapter from './UserChapter';
    import Chapter from './Chapter';
    import ListQuest from './ListQuest';
    import FireWorks from '@/components/FireWorks';
    import RepositoryFactory from "@/utils/RepositoryFactory";
    const QuestRepository = RepositoryFactory.get("quest");
    import logoQuest from '@/assets/images/logo/logo_quest.png';
    import Helper from '@/helper';
    export default {
        name: 'HandBookQuest',
        components: { UserChapter, Chapter, ListQuest, FireWorks },
        data () {            
            return {
                isLoading: true,
                listData: [],
                total: 0,
                listQuery: {
                    sub_category_id: 1,
                }, 
                listChapters:{},
                logoQuest:logoQuest,
                statusQuest: Helper.statusQuest,
                infoUser: {},
                isLoadingUpdate: false,
                currentLevel: 1,
            }            
        },

        computed: {
            ...mapGetters(["user", "level_user", "experience", "gold"]),
        },

        filters:{
            
        },

        created() {
            this.getUser();
            this.fetch();
            this.currentLevel = this.level_user;
            this.emitter.emit("show-fire-work", false);
        },
        mounted() {
            
        },

        methods: {
            getUser() {                
                this.infoUser = this.user;
            },
            async fetch(){
                this.isLoading = true;     
                const { data } = await QuestRepository.list( this.listQuery );                              
                if(data.success) {
                    const results  = data.data;
                    const chapters = data.chapters;
                    if (results) {
                        this.listData = results.map(item => {
                            return item
                        })
                    };
                    if (chapters) {
                        this.listChapters = chapters
                    }
                }
                this.listQuery.chapter = false;
                this.isLoading = false;
            },
            // thay đổi giá trị chapters (sub category id)
            handleChangeChapter(data){
                this.listQuery = data;
                this.fetch()
            },

            //nhận nhiệm vụ
            async handleUpdateQuest(formData){
                this.isLoading = true;
                let results = false;
                // kiểm tra xem có nhiệm vụ nào đã hoàn thành nhưng chưa nhận thưởng
                if (formData['id'] && formData['id'].length == 0) {
                    this.$notify({
                        title: 'Bạn chưa hoàn thành bất kỳ nhiệm vụ nào',
                        type: 'error',
                        duration: 3000
                    });
                    return;
                }
                if (formData.type == "create") {
                    const { data } = await QuestRepository.create( formData ); 
                    results = data.success;
                }else{
                    const { data } = await QuestRepository.update( formData ); 
                    results = data.success;  
                    if (data.success) {
                        const currentLevel = this.currentLevel;
                        this.$store.dispatch("user/update", data.data);
                        //hiện thị thông báo chúc mừng khi được tăng lv
                        if (this.level_user > currentLevel) {
                            this.emitter.emit("show-fire-work", true);
                        }
                    }                  
                }
                if (results) {
                    this.fetch();
                    this.isLoadingUpdate = false;
                    
                    this.$notify({
                        title: 'Xin chúc mừng',
                        message: 'Bạn đã nhận phần thưởng thành công!',
                        type: 'success',
                        duration: 3000
                    });
                }
            }
        }
    }

</script>

<style lang="scss" scoped>
@import "@style/custom.scss";
    .open-book > * {
        z-index: 999 !important;
    }
    .open-book {
        .article {
            z-index: 2 !important;
        }
    }    
</style>