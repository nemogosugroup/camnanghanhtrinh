<template>
    <el-image :src="imageBook" @click="handleChangeRouter" style="cursor: pointer;"/>
</template>

<script>
    import { mapGetters } from 'vuex';
    import BookImages from '@/assets/images/book.png'
    export default {
        name: 'HandBookComponents',
        components: { },
        data () {            
            return {                   
                imageBook: BookImages,
            }            
        },
        computed: {
            ...mapGetters(["user"])
        },
        filters:{
        },

        created() {
        },

        methods: {
            handleChangeRouter(){
                if (this.user.flag > 0) {
                    this.$router.push('/so-tay-hanh-trinh/thong-tin');
                }else{
                    this.$router.push('/so-tay-hanh-trinh/welcome');
                }
            }
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
.f-left {
    float: left;
}
.app-container .is-disabled ::deep .el-input__inner,
.el-select .el-input.is-disabled .el-input__inner {
    background-color: #fff !important;
    opacity: 1;
    cursor: default;
    color: #606266;
}
.el-form-item .is-disabled ::deep .el-input__inner {
    background-color: #fff;
    opacity: 1;
    cursor: default;
    color: #606266
}
.el-form-item ::deep .el-textarea__inner {
    min-height: 40px !important;
    padding: 9px 10px
}
.app-container .el-table ::deep .cell {
    word-break: break-word;
}
</style>