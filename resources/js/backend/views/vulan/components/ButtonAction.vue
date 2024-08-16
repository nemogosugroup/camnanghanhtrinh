<template>
    <div class="wrap-button index">

        <div v-if="!isPublic" class="flex-center">
            <el-button v-if="isCreate && !isEditPost" :loading="loading" @click="handleCreate"
                class="button roboto-regular">Tạo mới <i class="ri-add-box-line"></i></el-button>
            <el-button v-if="isEditPost" @click="handleEdit" :loading="loading" class="button roboto-regular">Chỉnh sửa
                <i class="ri-edit-2-fill"></i></el-button>
            <el-button v-if="!isShowReview" class="button roboto-regular" @click="handleReview">Xem trước <i
                    class="ri-image-2-fill"></i></el-button>
            <el-button v-else class="button roboto-regular" @click="handleReview">Trở lại <i
                    class="ri-image-2-fill"></i></el-button>
            <el-button v-if="isEditPost" class="button roboto-regular">Copy link <i class="ri-link-m"></i></el-button>
            <el-button v-if="isEditPost" class="button roboto-regular">Share <i
                    class="ri-share-forward-2-fill"></i></el-button>
        </div>

        <div v-else class="flex-center">
            <el-button v-if="isPublic" class="button roboto-regular" @click="captureScreen">Screenshot <i class="ri-camera-line"></i></el-button>
            <el-button v-if="isPublic" class="button roboto-regular" @click="copyLink">Copy link <i class="ri-link-m"></i></el-button>
            <el-button v-if="isPublic" class="button roboto-regular" @click="shareFacebook">Share <i
                class="ri-share-forward-2-fill"></i></el-button>
        </div>
    </div>
</template>
<script>
import {ElMessage} from "element-plus";
import html2canvas from 'html2canvas';
export default {
    name: 'ButtonAction',
    components: {},
    props: {
        isPublic: {
            type: Boolean,
            required: true,
            default: false
        },
        isCreate: {
            type: Boolean,
            required: true,
            default: false
        },
        isEditPost: {
            type: Boolean,
            required: true,
            default: false
        },
        isLoading: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            isShowReview: false,
            loading: false,
            imageUrl: null
        }
    },
    setup() {
    },
    filters: {

    },

    created() {
        this.emitter.off("ready-to-capture-screen");
    },
    mounted() {
        this.emitter.on("ready-to-capture-screen", value => {
            setTimeout(() => {
                this.handleCaptureScreen();
            }, 500);
        });
    },
    watch: {
        isLoading(newVal) {
            console.log('newVal', newVal)
            this.loading = newVal;
        }
    },
    methods: {
        captureScreen() {
            this.emitter.emit("prepare-capture-screen", true);
        },
        handleCaptureScreen() {
            html2canvas(document.body, {
                scale: 1,
                width: window.innerWidth,
                height: window.innerHeight
            })
            .then(canvas => {
                this.imageUrl = canvas.toDataURL('image/jpeg', 1.0);
                this.emitter.emit("after-capture-screen", this.imageUrl);
            });
            setTimeout(() => {
                this.emitter.emit("re-prepare-capture-screen", false);
            }, 1000);
        },
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
        },
        handleEdit() {
            this.$emit('edit', true);
        },
        copyLink() {
            navigator.clipboard.writeText(window.location.href)
                .then(() => {
                    ElMessage.success("Liên kết đã được sao chép!");
                })
                .catch(err => {
                    console.error('Có lỗi xảy ra khi sao chép:', err);
                });
        },
        shareFacebook() {
            let url = window.location.href;
            let title = "Vu Lan 2024";
            let description = 'Hình ảnh đẹp của gia đình bạn trong ngày lễ Vu Lan';
            let imageUrl = 'https://camnanghanhtrinh.gosucorp.vn/images/sl1.jpg';
            let facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&t=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}&picture=${encodeURIComponent(imageUrl)}`;

            window.open(facebookUrl, "pop", "width=768, height=768, scrollbars=no");
        }
    }
}
</script>
<style lang="scss" scoped></style>
