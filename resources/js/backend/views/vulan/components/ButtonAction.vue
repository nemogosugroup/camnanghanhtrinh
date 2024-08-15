<template>
    <div class="wrap-button index">
        <div class="flex-center">
            <el-button v-if="isCreate" @click="handleCreate" class="button roboto-regular">Tạo mới <i
                    class="ri-add-box-line"></i></el-button>
            <el-button v-if="!isShowReview" class="button roboto-regular" @click="handleReview">Xem trước <i
                    class="ri-image-2-fill"></i></el-button>
            <el-button v-else class="button roboto-regular" @click="handleReview">Trở lại <i
                    class="ri-image-2-fill"></i></el-button>
            <el-button class="button roboto-regular">Copy link <i class="ri-link-m"></i></el-button>
            <el-button class="button roboto-regular">Share <i class="ri-share-forward-2-fill"></i></el-button>
        </div>
    </div>
</template>
<script>
export default {
    name: 'ButtonAction',
    components: {},
    props: {
        isCreate: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            isShowReview: false
        }
    },
    setup() {
    },
    filters: {

    },

    created() {
    },
    mounted() {

    },
    beforeDestroy() {

    },
    methods: {
        handleReview() {
            this.isShowReview = !this.isShowReview;
            if (this.isShowReview) {
                this.$emit('handleShowHidePreview', false); // IsEdit => false to review
            } else {
                this.$emit('handleShowHidePreview', true); // IsEdit => true to eddit
            }
        },
        handleCreate() {
            this.$emit('create', true);
        }
    }
}
</script>
<style lang="scss" scoped></style>
