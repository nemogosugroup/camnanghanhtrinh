<template>
    <el-dialog :title="titleDialog" v-model="isShowDialog" @close="closeDialog">
        <el-row>
            <el-button type="success" @click="cropSubmit">Submit</el-button>
        </el-row>
        <hr>
        <el-row>
            <h1>{{fileName}}</h1>
            <el-col v-bind:span="24">
                <vue-cropper
                    ref="vueCropperRef"
                    :src="data"
                    v-bind="cropperOptions"
                    style="height: 400px"
                />
            </el-col>
        </el-row>
    </el-dialog>
</template>
<script>
import VueCropper from '@ballcat/vue-cropper';
import 'cropperjs/dist/cropper.css';
import {reactive} from 'vue'
import Cropper from 'cropperjs'

export default {
    name: 'DialogVulanCrop',
    components: {VueCropper},
    props: {
        dialogFormVisible: {
            type: Boolean,
            default: false
        },
        data: {
            type: String,
            default: ""
        },
        fileName: {
            type: String,
            default: ""
        },
        number: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            titleDialog: "Crop Image",
            isLoading: true,
            dialogVisible: false,
            isLoadingService: false,
            isShowDialog: this.dialogFormVisible,
            cropperOptions: Cropper.Options = reactive({
                viewMode: 0,
                responsive: true,
                restore: true,
                checkCrossOrigin: true,
                checkOrientation: true,
                modal: true,
                guides: true,
                center: true,
                highlight: true,
                background: true,
                autoCrop: true,
                movable: true,
                rotatable: true,
                scalable: true,
                zoomable: true,
                zoomOnTouch: true,
                zoomOnWheel: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: true
            }),
            finalData: null
        }
    },

    filters: {},

    created() {
    },
    watch: {
        dialogFormVisible(newValue) {
            this.isShowDialog = newValue;
        },

    },
    methods: {
        closeDialog() {
            this.isShowDialog = false
            this.$emit('hidedialog', {})
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