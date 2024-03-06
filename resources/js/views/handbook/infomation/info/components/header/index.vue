<template>
    <el-row>
        <el-col :span="7" style="padding-right: 10px;">
            <el-card shadow="never" class="avatar" style="padding:0">
                <el-avatar shape="square" :size="150" :src="urlImages ? urlImages : defaultAvatar"></el-avatar>
                <input type="hidden" v-model="urlImages">                    
                <span class="changeImages" @click="handerCkfinder">
                    <i class="ri-camera-fill"></i>
                </span>
            </el-card>                                      
        </el-col>
        <el-col :span="17" style="padding-left: 10px;">
            <el-card shadow="never">
                <h3>Name card</h3>
            </el-card>
        </el-col>
    </el-row>
</template>
<script>
    import { mapGetters } from "vuex";  
    export default {
        name: 'Infomation',
        components: { },
        props: {
            defaultAvatar: {
                required: true,
                type: String
            },
        },
        data () {   
            return { 
                dataInfomation: [], 
                title: 'Thông tin cá nhân',
                urlImages:'',
            }            
        },
        computed: {
            ...mapGetters(["user", "training"]),
            avatar() {
                let avatar = this.user.avatar; 
                return avatar ? avatar : avatarDefault;
            },
        },
        filters:{
            
        },

        created() {
            this.dataTraining = this.training;
            this.dataInfomation = this.user;
        },

        mounted() {
        },

        methods: {
        }
    }

</script>