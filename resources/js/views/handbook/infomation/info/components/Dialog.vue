<template>
    <el-dialog
        v-model="isShowDialog"
        @close="closeDialog"
        class="wp-dialog"
    >
        <template #header="{ titleClass }">
            <div class="header-dialog">
                <h3 style="margin: 0 0 15px">{{item.Subject}}</h3>
                <i>{{item.Description}}</i>
            </div>
        </template>
        <div class="content-dialog">
            <iframe
                :src="item.UrlVideo"
                width="100%"
                height="500px"
                allowfullscreen
            ></iframe>
        </div>
    </el-dialog>
</template>
<script>
    export default {
        name: 'DialogTraining',
        props:{
            showDialog:{
                type: Boolean,
                default: false
            }
        },
        data () {            
            return {
                isLoading: false,
                isShowDialog: this.showDialog
            }
        },
        computed: {},
        mounted() {
            this.emitter.on('is-open-training-dialog', data => {
                this.item = data;
                this.isShowDialog = true;
            });
        },
        created() {},
        methods: {
            closeDialog() {
                this.isShowDialog = false;
                this.item = {};
                this.emitter.emit('is-close-training-dialog');
            }
        }
    }

</script>
<style lang="scss" scoped>
@import '@style/variables.scss';
.el-dialog__header{
    margin: 0;
}
.wp-dialog {    
    .header-dialog {
        border-radius: $size10;
        padding: $pd10;
        border: 1px solid $lightGray;
        .flex {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: flex-start;
            align-items: stretch;
            align-content: stretch;
        }
        .el-dialog__title {
            margin: 0;
            font-size: $fontsize16;
            margin-left: $size10;
            text-align: left;
        }
    }
}
</style>
