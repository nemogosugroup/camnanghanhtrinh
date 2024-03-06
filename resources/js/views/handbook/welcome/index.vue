<template>
    <video class="video" ref="video" :autoplay="autoplay" :muted="muted" controls>
        <source src="/static/uploads/videos/gosu.mp4" type="video/mp4">
    </video>
    <span class="button-skip button">
        <el-button type="primary" @click="handleSkip"><i class="ri-skip-forward-fill"></i></el-button>
    </span>
</template>

<script>
    import { mapGetters } from 'vuex';
    import HandBookMenu from '@/views/handbook/menu';
    import RepositoryFactory from "@/utils/RepositoryFactory";
    const QuestRepository = RepositoryFactory.get("quest");
    import Helper from '@/helper';
    export default {
        name: 'HandBookInfo',
        components: { HandBookMenu },
        data () {            
            return { 
                autoplay:false,   
                muted:true, 
                formData : {
                    'mission_id' : 31, // nhiệm vụ xem clip
                    'user_id': false,
                    'status' : Helper.statusQuest.pending
                }
            }            
        },

        filters:{
            
        },

        created() {
            this.handlePlay();           
        },
        mounted() {  
            setTimeout(() => {
                this.handleCreateQuest(); 
                console.log("Created");
            }, 91000);
        },
        computed: {
            ...mapGetters(['user']),
            flag() {
                return this.user.flag;
            },
        },
        methods: {     
            handlePlay () {
                if(!this.flag)
                    this.autoplay = true
            },
            handleSkip(){
                this.$refs.video.pause()
                this.$refs.video.currentTime = this.$refs.video.duration;
            },
            async handleCreateQuest(){
                this.formData.user_id = this.user.id;
                const { data } = await QuestRepository.create( this.formData );
                // if (item) {
                //     let url = item.link;
                //     if (item.target) {
                //         this.formData.mission_id = item.id;
                //         this.formData.user_id = this.user.id;
                //         this.formData.status = this.statusQuest.pending;
                //         this.formData.type = 'create';
                //         this.$emit('updateQuest', this.formData);
                //         window.open(url,'_blank');                        
                //     }else{
                //         this.$router.push({ path: url })
                //     }
                // }
            },
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
</style>