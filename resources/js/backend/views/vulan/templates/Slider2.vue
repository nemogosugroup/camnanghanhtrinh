<template>
    <div class="app-container">
        <div class="container-slider vulan-container">
            <div ref="slider" class="slider-1 bg" :style="`background-color:${colorBg}`">
                <div v-if="isEdit" class="editImages">
                    <div class="uploadImages">
                        <span class="button roboto-medium">
                            <input class="hidden-input" type="file" multiple @change="(event) => handleUpload(event)"
                                   accept="image/jpeg">
                            <span class="text">Chỉnh sửa</span> <i class="ri-edit-2-fill"></i>
                        </span>
                    </div>
                </div>
                <div v-if="!isEdit" class="slider-images">
                    <el-image :key="index" :src="listImages[0].url"/>
                    <div class="content-wish">
                        <Descriptions :style="dataSlider.content.slider_2.desc.style"
                                      :content="dataSlider.content.slider_2.desc.content" :isEdit="isEdit"
                                      @getContentDesc="handleContentDesc"/>
                    </div>
                </div>
                <div v-else class="slider-images">
                    <el-image :key="index" :src="listImages[0].url"/>
                    <div class="content-wish">
                        <Descriptions :style="dataSlider.content.slider_2.desc.style"
                                      :content="dataSlider.content.slider_2.desc.content" :isEdit="isEdit"
                                      @getContentDesc="handleContentDesc"/>
                    </div>
                </div>
                <Logo :style="dataSlider.content.slider_2.logo.style" :isEdit="isEdit"
                      @getStyleLogo="handleStyleLogo"/>
                <TitleVulan :style="dataSlider.content.slider_2.title.style" :isEdit="isEdit"
                            @getStyleTitle="handleStyleTitle"/>
                <Temp2ImagesGroup :data="dataSlider.content.slider_2.main_items" :isEdit="isEdit"/>
                <ButtonAction @handleShowHidePreview="handleShowHidePreview" :isCreate="isCreate"
                              @create="handleCreate"/>
            </div>
        </div>
    </div>
</template>
<script>
import Descriptions from '@/backend/views/vulan/components/Description2.vue';
import Temp2ImagesGroup from '@/backend/views/vulan/components/temp2ImagesGroup.vue';
import TitleVulan from '@/backend/views/vulan/components/Title2.vue';
import Logo from '@/backend/views/vulan/components/Logo.vue';
import ButtonAction from '@/backend/views/vulan/components/ButtonAction.vue';
import ImagesSlider2 from '@/assets/images/vulan/sl2.jpg';
import {Navigation, Pagination, Scrollbar, A11y, EffectCards, Autoplay, EffectCube} from 'swiper/modules';
import {Swiper, SwiperSlide} from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import 'swiper/css/effect-cards';
import 'swiper/css/effect-cube';

//repository
import AdminRepositoryFactory from '@/backend/respository';

const vulanRepository = AdminRepositoryFactory.get('vulan');
const listImageDefault = [
    {
        url: ImagesSlider2,
        type: "images",
        show_content: true
    }
]
const dataSliderTemplate = {
    "id": 2,
    "title": "Vu Lan Template No2",
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
                    "left": 140,
                    "top": 125,
                    "color": '#fff'
                }
            },
            "desc": {
                "content": "Nhập lời chúc của bản tại đây!",
                "style": {
                    "left": 100,
                    "top": 530
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
                        "left": 710,
                        "top": 60
                    }
                },
                {
                    "type": "",
                    "url": "",
                    "style": {
                        "left": 1179,
                        "top": 29
                    }
                },
                {
                    "type": "",
                    "url": "",
                    "style": {
                        "left": 995,
                        "top": 493
                    }
                },
                {
                    "type": "",
                    "url": "",
                    "style": {
                        "left": 1370,
                        "top": 470
                    }
                },
            ]
        }
    }
}
export default {
    name: 'Vulan',
    components: {
        Swiper,
        SwiperSlide,
        Descriptions,
        TitleVulan,
        Temp2ImagesGroup,
        Logo,
        ButtonAction
    },
    data() {
        return {
            dataSlider: dataSliderTemplate,
            allowTouchMove: true,
            colorBg: dataSliderTemplate.content.slider_2.background.color,
            colorBackground: null,
            listImages: [],
            countImages: 0,
            isEdit: true,
            dialogImageUrl: '',
            dialogVisible: false,
            listItemImages: listImageDefault,
            isShow: [],
            isCreate: false,
            listFiles: []
        }
    },
    setup() {
        const onSwiper = (swiper) => {
            console.log(swiper);
        };
        const onSlideChange = () => {
            console.log('slide change');
        };
        return {
            onSwiper,
            onSlideChange,
            modules: [Navigation, Pagination, Scrollbar, A11y, EffectCards, Autoplay, EffectCube],
        };
    },
    filters: {},

    created() {
        this.listImages = listImageDefault;
        this.countImages = this.listImages.length;
        this.emitter.off("get-img-group-data");
    },
    mounted() {
        this.colorBackground = this.colorBg
        this.emitter.on("get-img-group-data", data => {
            this.dataSlider.content.slider_2.items[data.idx] = data.data;
        });
    },
    beforeDestroy() {
    },
    methods: {
        changeColor() {
            this.colorBackground = this.colorBg;
            this.dataSlider.content.slider_2.background.color = this.colorBg;
            this.isCreate = true;
        },
        //get style logo
        handleStyleLogo(data, type) {
            if (Object.keys(data).length > 0) {
                if (type == 'slider_2') {
                    this.dataSlider.content.slider_2.logo.style.left = data.left
                    this.dataSlider.content.slider_2.logo.style.top = data.top
                }
                this.isCreate = true;
            }
        },
        //get style Title
        handleStyleTitle(data, type) {
            if (Object.keys(data).length > 0) {
                if (type == 'slider_2') {
                    this.dataSlider.content.slider_2.title.style.left = data.left
                    this.dataSlider.content.slider_2.title.style.top = data.top
                }
                this.isCreate = true;
            }
        },
        // get infomation
        handleContentDesc(data, type) {
            if (Object.keys(data).length > 0) {
                if (type == 'slider_2') {
                    this.dataSlider.content.slider_2.desc.style.left = data.left
                    this.dataSlider.content.slider_2.desc.style.top = data.top
                    this.dataSlider.content.slider_2.desc.content = data.content
                }
                this.isCreate = true;
            }
        },
        // upload images
        handleUpload(event) {
            const file = event.target.files[0];

            const promises = [];
            this.listItemImages = []
            promises.push(new Promise((resolve) => {
                let _file = file;
                this.listFiles.push(_file);
                const reader = new FileReader();

                reader.onload = (e) => {
                    const dataImages = {};
                    dataImages.type = "image";
                    dataImages.url = e.target.result;
                    dataImages.show_content = false;
                    this.listImages[0] = dataImages;
                    resolve();
                };

                reader.readAsDataURL(_file);
            }));

            Promise.all(promises).then(() => {
                this.isCreate = true;
            });
        },
        //show hide content
        hanlderShowContent(index) {
            let itemImages = [];
            for (let index = 0; index < this.listItemImages.length; index++) {
                const item = this.listItemImages[index];
                let _item = {};
                _item.url = "";
                _item.type = "";
                _item.show_content = item.show_content;
                itemImages.push(_item);
            }
            this.dataSlider.content.slider_2.items = itemImages;
        },
        // show hide preview
        handleShowHidePreview(data) {
            console.log('dataSlider.content.slider_2.desc.content', this.dataSlider.content.slider_2.desc.content)
            this.isEdit = data // show hide prevew
        },
        // show content (lời chúc)
        handleShowContent(index) {
            this.isShow[index] = !this.isShow[index];
        },
        //update
        //create
        async handleCreate(check) {
            if (check) {
                const formData = new FormData();
                // Thêm nhiều file vào FormData
                if (this.listFiles.length > 0) {
                    this.listFiles.forEach((file, index) => {
                        formData.append(`files[${index}]`, file);
                    });
                }
                formData.append("content", JSON.stringify(this.dataSlider));
                const {data} = await vulanRepository.create(formData);
            }
        }
    }
}

</script>
<style lang="scss">
@import "@style/vulan.scss";
</style>
<style lang="scss" scoped>
.slider-1 {
    width: 100%;
    height: 100vh
}

.container-slider {
    position: relative;
}

.bg {
    // width: 100%;
    // height: 100%;
    background-size: cover;
    background-position: left;
}

.wish {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: 1fr;
    grid-column-gap: 0px;
    grid-row-gap: 0px;

    .contentWish {
        background: #cfcece;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        align-items: center;
    }
}

.listImages {
    // background: rgb(255, 255, 255);
    // background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(238, 238, 238, 1) 50%, rgba(204, 204, 204, 1) 100%);
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-start;
    align-items: stretch;
    align-content: stretch;
}

.app-container {
    padding: 0;
    overflow: hidden;
}

.change-color :deep(.el-color-picker__trigger) {
    border: 0;
    padding: 3px;
}

:deep(.el-upload-list) {
    margin: 0;
}

.hidden-input {
    width: 100%;
    height: 100%;
    position: absolute;
    opacity: 0;
    z-index: 2;
    cursor: pointer;
}

.uploadImages .button {
    position: relative;
}

.slider-images {
    .itemImage {
        width: 100%;
        height: 100%;
        position: relative;
        transform: translateX(0%);
        transition: all .3s ease-in-out;

        &.hide {
            transform: translateX(-50%);
            transition: all .3s ease-in-out;
        }

        :deep(.el-image) {
            width: 100%;
            height: 100%;
        }
    }

    .show-content {
        color: #fff;
        font-size: 50px;
        position: absolute;
        top: 50%;
        z-index: 999;
        right: 10px;
        transform: translate(0px, -50%);
        cursor: pointer;
    }

    .hidden-content {
        transition: all .3s ease-in-out;
        opacity: 0;
        visibility: hidden;

        &.show {
            visibility: visible;
            transition: all .3s ease-in-out;
            opacity: 1;
        }
    }
}
</style>
