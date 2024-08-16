<template>
    <div class="app-container">
        <el-row>
            <el-button @click="userSubmit">SUBMIT</el-button>
        </el-row>

        <el-row>
            <el-col v-bind:span="24" style="padding: 20px">
                <div id="uploadBGImage" class="upload_image">
                    <img class="show_img" :class="{'d-none': !tmpDataBG.url}" :src="tmpDataBG.url"
                         alt="">
                    <img class="icon_upload" :src="iconUpload" alt="" @click="openUploadBGInput">
                    <input id="uploadBGInput" ref="uploadBGInput" type="file" accept="image/*"
                           class="d-none"
                           @change="(event) => handleUploadBG(event)">
                </div>
            </el-col>
        </el-row>

        <el-row>
            <el-col v-bind:span="12" v-for="(data, idx) in tmpDatas" style="padding: 20px">
                <div :id="`uploadImage${idx+1}`" class="upload_image">
                    <i v-if="data.type === 'image'" class="icon_crop ri-crop-line" :class="{'d-none': !data.url}"
                       @click="handleShowDialog(idx + 1)"/>

                    <img v-if="data.type === 'image'" class="show_img" :class="{'d-none': !data.url}" :src="data.url"
                         alt="">
                    <video v-else class="show_video" :class="{'d-none': !data.url}" controls autoplay loop muted>
                        <source v-if="data.url" :src="data.url" type="video/mp4">
                    </video>

                    <img class="icon_upload" :src="iconUpload" alt="" @click="openUploadInput(idx+1)">
                    <input :id="`uploadInput${idx+1}`" :ref="`uploadInput${idx+1}`" type="file" accept="video/*,image/*"
                           class="d-none"
                           @change="(event) => handleUpload(idx+1, event)">
                </div>
            </el-col>
        </el-row>

        <dialog-crop :dialog-form-visible="dialogFormVisible" :data="dataCrop" :number="currentNumber"
                     :file-name="currentFileName"
                     @hidedialog="handleHideDialog"></dialog-crop>

    </div>
</template>

<script>
import DialogCrop from "@/backend/views/vulan/components/DialogCrop.vue";
import AdminRepositoryFactory from "@/backend/respository";

const VuLanRepository = AdminRepositoryFactory.get('vulanTemplates');

export default {
    name: 'VuLanSlider2',
    components: {DialogCrop},
    data() {
        return {
            iconUpload: "/static/vulan/generals/icon_upload.png",
            backgroundData: {file: null, type: null, show_content: false},
            tmpDataBG: {fileName: "", type: "", url: "", show_content: false},
            filesData: [
                {file: null, type: null, show_content: false},
                {file: null, type: null, show_content: false},
                {file: null, type: null, show_content: false},
                {file: null, type: null, show_content: false}
            ],
            tmpDatas: [
                {fileName: "", type: "", url: ""},
                {fileName: "", type: "", url: ""},
                {fileName: "", type: "", url: ""},
                {fileName: "", type: "", url: ""}
            ],
            dataCrop: "",
            currentNumber: 0,
            currentFileName: "",
            dialogFormVisible: false,
            dialogVisibleRemove: false,

            tmp2Data: {
                "template_id": 2,
                "history_id": 0,
                "content": {
                    "slider_2": {
                        "background": {
                            "color": "#ed8b33"
                        },
                        "logo": {
                            "style": {
                                "left": 25,
                                "top": 25
                            }
                        },
                        "title": {
                            "style": {
                                "left": 1180,
                                "top": 300,
                                "color": '#fff'
                            }
                        },
                        "desc": {
                            "content": "Nhập lời chúc của bản tại đây!",
                            "style": {
                                "left": 1375,
                                "top": 540
                            }
                        },
                        "items": [
                            {
                                "type": "image",
                                "url": "/static/vulan/generals/tmp2_bg_default.jpg",
                                "show_content": false
                            }
                        ],
                        "main_items": [
                            {
                                "type": "",
                                "url": "",
                                "style": {
                                    "left": 1375,
                                    "top": 540
                                }
                            },
                            {
                                "type": "",
                                "url": "",
                                "style": {
                                    "left": 1375,
                                    "top": 540
                                }
                            },
                            {
                                "type": "",
                                "url": "",
                                "style": {
                                    "left": 1375,
                                    "top": 540
                                }
                            },
                            {
                                "type": "",
                                "url": "",
                                "style": {
                                    "left": 1375,
                                    "top": 540
                                }
                            },
                        ]
                    }
                }
            }
        }
    },

    filters: {},

    created() {
        this.emitter.off('send-cropped-image');
    },

    mounted() {
        this.emitter.on('send-cropped-bg-image', data => {
            this.dataCropBG = data.blob;
            this.backgroundData.file = this.dataURLtoFile(this.dataCropBG, this.tmpDataBG.fileName);
            this.backgroundData.type = "image";
            this.tmpDataBG.url = data.blob;
        });
        this.emitter.on('send-cropped-image', data => {
            this.dataCrop = data.blob;
            this.filesData[`${data.number - 1}`].file = this.dataURLtoFile(this.dataCrop, this.tmpDatas[`${data.number - 1}`].fileName);
            this.filesData[`${data.number - 1}`].type = "image";
            this.tmpDatas[`${data.number - 1}`].url = data.blob;
        });
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
        openUploadBGInput() {
            document.getElementById(`uploadBGInput`).click()
        },
        openUploadInput(number) {
            document.getElementById(`uploadInput${number}`).click()
        },
        handleUpload(number, event) {
            const file = event.target.files[0] ?? null;
            if (file) {
                const type = file.type;

                if (type.includes("image")) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.tmpDatas[`${number - 1}`].fileName = file.name;
                        this.tmpDatas[`${number - 1}`].type = "image";
                        this.tmpDatas[`${number - 1}`].url = e.target.result;
                        this.filesData[`${number - 1}`].type = this.tmpDatas[`${number - 1}`].type;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.tmpDatas[`${number - 1}`].fileName = file.name;
                    this.tmpDatas[`${number - 1}`].type = "video";
                    this.tmpDatas[`${number - 1}`].url = this.userSubmitPreviewVideo(file, number - 1);
                    this.filesData[`${number - 1}`].type = this.tmpDatas[`${number - 1}`].type;
                }

                this.filesData[`${number - 1}`].file = file;
            }
        },
        handleUploadBG(event) {
            const file = event.target.files[0] ?? null;
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.tmpDataBG.fileName = file.name;
                    this.tmpDataBG.type = "image";
                    this.tmpDataBG.url = e.target.result;
                    this.backgroundData.type = this.tmpDataBG.type;
                };
                reader.readAsDataURL(file);

                this.backgroundData.file = file;
                this.backgroundData.show_conent = this.tmpDataBG.show_conent;
            }
        },
        handleShowDialog(number) {
            this.dialogFormVisible = true;
            this.dialogVisibleRemove = true;
            this.dataCrop = this.tmpDatas[`${number - 1}`].url;
            this.currentFileName = this.tmpDatas[`${number - 1}`].fileName;
            this.currentNumber = number;
        },
        handleHideDialog() {
            this.dialogFormVisible = false;
            this.dialogVisibleRemove = false;
            this.dataCrop = "";
            this.currentFileName = "";
            this.currentNumber = 0;
        },
        userSubmit() {
            let formData = new FormData();
            formData.append('background[file]', this.backgroundData.file);
            formData.append('background[type]', this.backgroundData.type);
            formData.append('background[show_content]', this.backgroundData.show_content);
            this.filesData.forEach((data, idx) => {
                formData.append(`files[${idx}][file]`, data.file);
                formData.append(`files[${idx}][type]`, data.type);
                formData.append(`files[${idx}][show_content]`, data.show_content);
            });
            formData.append("template_id", this.tmp2Data.template_id);
            formData.append("history_id", this.tmp2Data.history_id);
            formData.append("content", JSON.stringify(this.tmp2Data.content));

            VuLanRepository.userCreate(formData).then(response => {
                const {data} = response
                if (data.success) {
                    this.$message({
                        message: data.message,
                        type: 'success'
                    });
                } else {
                    this.$message({
                        message: data.message,
                        type: 'error'
                    });
                }
            }).catch(e => {
                this.isLoadingSave = false
            })
        },
        userSubmitPreviewVideo(videoFile, number) {
            const pathVideo = "";
            let formData = new FormData();
            formData.append("preview_video", videoFile);
            VuLanRepository.uploadPreviewVideo(formData).then(response => {
                const {data} = response
                if (data.success) {
                    this.tmpDatas[`${number}`].url = data.data;
                } else {
                    this.$message({
                        message: "Đã có lỗi xảy ra! Xin thử lại!",
                        type: 'error'
                    });
                }
            }).catch(e => {
                this.isLoadingSave = false
            });

            return pathVideo;
        }
    }
}

</script>

<style lang="scss" scoped>
.d-none {
    display: none !important;
}
#uploadBGImage {
    &.upload_image {
        width: 768px;
    }
}
.upload_image {
    width: 400px;
    height: 400px;
    border: 5px solid red;
    border-radius: 5px;
    text-align: center;
    position: relative;

    .icon_crop {
        position: absolute;
        right: -40px;
        top: -5px;
        border: 2px solid;
        padding: 5px;
        cursor: pointer;
    }

    .show_img {
        width: 100%;
        height: 100%;
    }

    .show_video {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        left: 0;
        right: 0;
    }

    .icon_upload {
        width: 100px;
        height: 100px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
        opacity: 0.3;
        transition: all .4s ease-in-out;

        &:hover {
            opacity: 1;
            filter: drop-shadow(2px 4px 6px #fff);
        }
    }
}
</style>