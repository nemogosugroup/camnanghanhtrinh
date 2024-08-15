<template>
    <el-dialog
        v-model="isShowDialog"
        @close="closeDialog"
        class="wp-dialog"
    >
        <div class="content-dialog">
            <MembersBlock/>
        </div>
    </el-dialog>
</template>
<script>
import MembersBlock from "@/views/handbook/members/components/Members.vue";

export default {
    name: 'DialogMembers',
    components: {MembersBlock},
    data() {
        return {
            isShowDialog: false
        }
    },
    computed: {},
    mounted() {
        this.emitter.on('is-open-members-dialog-from-main', () => {
            this.isShowDialog = true;
        });
    },
    created() {
    },
    methods: {
        closeDialog() {
            this.isShowDialog = false;
            this.emitter.emit('is-close-members-dialog');
        }
    }
}

</script>
<style lang="scss" scoped>
@import '@style/variables.scss';

.el-dialog__header {
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
