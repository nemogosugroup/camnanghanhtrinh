<template>
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-row>
            <el-button type="info" @click="reCapture">Bỏ qua</el-button>
            <el-button type="success" @click="downloadImage">Tải ảnh</el-button>
        </el-row>
        <hr>
        <el-row>
            <el-col v-bind:span="24">
                <el-image :src="captureImage"></el-image>
            </el-col>
        </el-row>
    </el-dialog>
</template>
<script>

export default {
    name: 'DialogVulanCaptureScreen',
    components: {},
    props: {
        dialogCaptureVisible: {
            type: Boolean,
            default: false
        },
        captureImage: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            titleDialog: "Ảnh màn hình đã chụp",
            isShowDialog: this.dialogCaptureVisible
        }
    },

    filters: {},

    created() {
    },
    watch: {
        dialogCaptureVisible(newValue) {
            this.isShowDialog = newValue;
        },

    },
    methods: {
        dataURLtoFile(dataURL, filename) {
            let arr = dataURL.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[arr.length - 1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {type: mime});
        },
        downloadImage() {
            const now = new Date();
            const formattedDate = now.toLocaleDateString('vi-VN');
            const formattedTime = now.toLocaleTimeString('vi-VN');
            const name = `Vu Lan 2024 - ${formattedDate} ${formattedTime}.jpg`;

            const file = this.dataURLtoFile(this.captureImage, name);
            const url = URL.createObjectURL(file);
            const link = document.createElement('a');
            link.href = url;
            link.download = name;
            link.click();

            URL.revokeObjectURL(url);
        },
        reCapture() {
            this.closeDialog();
        },
        closeDialog() {
            this.isShowDialog = false
            this.$emit('hideCaptureDialog', {})
        },
        cropSubmit() {
            const refsCropper = this.$refs.vueCropperRef;
            this.finalData = refsCropper.getCroppedCanvas().toDataURL('image/jpeg');
            this.emitter.emit('send-cropped-image', {blob: this.finalData, number: this.number});
            this.closeDialog();
        }
    }
}

</script>
<style lang="scss" scoped>
:deep(.el-select) {
    width: 100%;
}

.cropper-container {
    height: 100% !important;
}
</style>