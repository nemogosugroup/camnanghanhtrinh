<template>
    <el-row>
        <Info v-bind:defaultAvatar="defaultAvatar" v-bind:col-span="12"></Info>
        <InfoPerson v-bind:defaultAvatar="defaultAvatar" v-bind:col-span="12"></InfoPerson>
    </el-row>   
</template>

<script>
    //var moment = require('moment')
    import { mapGetters } from 'vuex';
    import Info from '@/views/handbook/infomation/info';
    import InfoPerson from '@/views/handbook/infomation/person';
    import defaultAvatar from '@/assets/images/logo/avatar.gif';
    export default {
        name: 'HandBookInfo',
        components: { Info, InfoPerson },
        data () {   
            return { 
                defaultAvatar: defaultAvatar,  
            }            
        },

        filters:{
            
        },

        created() {
        },

        mounted() {
        },

        methods: {
            ...mapGetters(['sidebar','user']),
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
</style>