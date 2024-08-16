<template>
    <div v-if="isEdit" :ref="`draggable${idx+1}`" id="draggableDesc" :class="`item${idx+1}`"
         class="draggable index description" v-for="(item, idx) in data"
         :style="{ top: item.style.top + 'px', left: item.style.left + 'px' }">

        <div class="wrapper_upload_image" :class="`wrapper_upload_image_${idx+1}`"
             :style="`background-image: url(/static/vulan/generals/frame_${idx+1}.png)`">
            <div :id="`uploadImage${idx+1}`" class="upload_image">
                <i v-if="item.type === 'image'" class="icon_crop ri-crop-line" :class="{'d-none': !item.url}"
                   @click="handleShowDialog(idx + 1)"/>

                <img v-if="item.type === 'image'" class="show_img" :class="{'d-none': !item.url}" :src="item.url"
                     alt="">
                <video v-else class="show_video" :class="{'d-none': !item.url}" controls autoplay loop muted>
                    <source v-if="item.url" :src="item.url" type="video/mp4">
                </video>

                <img class="icon_upload" :src="iconUpload" alt="" @click="openUploadInput(idx+1)">
                <input :id="`uploadInput${idx+1}`" :ref="`uploadInput${idx+1}`" type="file" accept="video/*,image/*"
                       class="d-none"
                       @change="(event) => handleUpload(idx+1, event)">
            </div>
        </div>

    </div>
    <div v-else :class="`item${idx+1}`" class="index description draggable index description"
         v-for="(item, idx) in data"
         :style="{ top: item.style.top + 'px', left: item.style.left + 'px' }">
        <div class="wrapper_upload_image" :class="`wrapper_upload_image_${idx+1}`"
             :style="`background-image: url(/static/vulan/generals/frame_${idx+1}.png)`">
            <div :id="`uploadImage${idx+1}`" class="upload_image">
                <img v-if="item.type === 'image'" class="show_img" :class="{'d-none': !item.url}" :src="item.url"
                     alt="">
                <video v-else class="show_video" :class="{'d-none': !item.url}" controls autoplay loop muted>
                    <source v-if="item.url" :src="item.url" type="video/mp4">
                </video>
            </div>
        </div>
    </div>

    <dialog-crop :dialog-form-visible="dialogFormVisible" :data="dataCrop" :number="currentNumber"
                 @hidedialog="handleHideDialog"></dialog-crop>
</template>
<script>
import $ from 'jquery';
import 'jquery-ui/ui/widgets/draggable';
import DialogCrop from "@/backend/views/vulan/components/DialogCrop.vue";
import DialogCaptureScreen from "@/backend/views/vulan/components/DialogCaptureScreen.vue";
import AdminRepositoryFactory from "@/backend/respository";

const VuLanRepository = AdminRepositoryFactory.get('vulanTemplates');
export default {
    name: 'temp2ImagesGroup',
    components: {DialogCrop, DialogCaptureScreen},
    props: {
        data: {
            type: Object,
            required: true,
            default: {}
        },
        isEdit: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            iconUpload: "/static/vulan/generals/icon_upload.png",
            dataCrop: "",
            currentNumber: 0,
            dialogFormVisible: false,
        }
    },
    setup() {
    },
    filters: {},

    created() {
        this.emitter.off('send-cropped-image');
    },
    mounted() {
        this.initDraggable();
        this.emitter.on('send-cropped-image', data => {
            this.dataCrop = data.blob;
            this.data[`${data.number - 1}`].file = this.dataURLtoFile(this.dataCrop, `file_${data.number - 1}.jpg`);
            this.data[`${data.number - 1}`].url = data.blob;
            this.data[`${data.number - 1}`].type = "image";
            this.emitter.emit("get-file-group-data", {data: this.data[`${data.number - 1}`], idx: data.number - 1});
        });
    },
    watch: {
        isEdit(newVal) {
            if (newVal) {
                this.reloadDraggable();
            }
        }
    },
    methods: {
        initDraggable() {
            this.data.forEach((item, idx) => {
                const draggableElement = $(this.$refs[`draggable${idx + 1}`]);
                let $this = this;
                draggableElement.draggable({
                    drag: (event, ui) => {
                        const position = ui.position;
                    },
                    stop: (event, ui) => {
                        const position = ui.position;
                        item.style.left = position.left;
                        item.style.top = position.top;
                        $this.emitter.emit("get-position-group-data", {data: item, idx: idx});
                    }
                });
            });
        },
        reloadDraggable() {
            this.data.forEach((item, idx) => {
                $(this.$refs[`draggable${idx + 1}`]).draggable("destroy"); // Hủy draggable hiện tại
                this.$nextTick(() => {
                    this.initDraggable(); // Khởi tạo lại draggable
                });
            });
        },
        handleShowDialog(number) {
            this.dialogFormVisible = true;
            this.dataCrop = this.data[`${number - 1}`].url;
            this.currentNumber = number;
        },
        handleHideDialog() {
            this.dialogFormVisible = false;
            this.dataCrop = "";
            this.currentNumber = 0;
        },
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
                        this.data[`${number - 1}`].type = "image";
                        this.data[`${number - 1}`].url = e.target.result;
                    };
                    reader.readAsDataURL(file);

                } else {
                    this.data[`${number - 1}`].url = this.userSubmitPreviewVideo(file, number - 1);
                    this.data[`${number - 1}`].type = "video";
                }

                this.data[`${number - 1}`].file = file;
                this.emitter.emit("get-file-group-data", {data: this.data[`${number - 1}`], idx: number - 1});
            }
        },
        userSubmitPreviewVideo(videoFile, number) {
            const pathVideo = "";
            let formData = new FormData();
            formData.append("preview_video", videoFile);
            VuLanRepository.uploadPreviewVideo(formData).then(response => {
                const {data} = response
                if (data.success) {
                    this.data[`${number}`].url = data.data;
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
.draggable {
    color: #fff;

    .ck-content {
        width: auto;
        max-width: 400px;

        &:hover {
            cursor: text;
        }
    }
}

.draggable:hover {
    cursor: move;
}

.description {
    width: auto;
    padding: 10px;
    position: absolute;
    color: #000;
}

.vulan-container .content-desc {
    align-items: start;
    width: 700px;
    height: 350px;
    background-size: contain;
    background-repeat: no-repeat;
    padding-top: 70px;
    padding-left: 90px;
}

.vulan-container .content-desc > div {
    transform: rotate(1deg);
}

.d-none {
    display: none !important;
}

#uploadBGImage {
    &.upload_image {
        width: 768px;
    }
}

.wrapper_upload_image {
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    padding: 20px;
}

.wrapper_upload_image_1 {
    width: 400px;
    height: 400px;
}

#uploadImage1 {
    width: 270px;
    height: 270px;
    transform: rotate(-1.3deg);
    top: 33px;
    left: 47px;
}

.wrapper_upload_image_2 {
    width: 545px;
    height: 415px;
}

#uploadImage2 {
    width: 423px;
    height: 304px;
    transform: rotate(1.3deg);
    top: 17px;
    left: 20px;
}

.wrapper_upload_image_3 {
    width: 310px;
    height: 330px;
}

#uploadImage3 {
    width: 213px;
    height: 225px;
    transform: rotate(2.7deg);
    left: 51px;
    top: 27px;
}

.wrapper_upload_image_4 {
    width: 310px;
    height: 395px;
}

#uploadImage4 {
    width: 225px;
    height: 304px;
    transform: rotate(-3.9deg);
    left: 5px;
    top: 18px;
}

.upload_image {
    width: 400px;
    height: 400px;
    text-align: center;
    position: relative;
    background-color: #bdbdbd;

    .icon_crop {
        position: absolute;
        right: 3px;
        top: 3px;
        padding: 5px;
        cursor: pointer;
        color: #000;
        background-color: #fff;
        font-size: 18px;
        line-height: 20px;
        height: auto;
        border: 0;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
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
