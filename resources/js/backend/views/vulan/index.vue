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
                        <span class="change-color"><el-color-picker v-model="colorBg" @change="changeColor" /></span>
                    </div>
                    <div v-if="listItemImages.length > 0">
                        <div class="listImages">
                            <div class="itemImage" v-for="(item, index) in listItemImages" :key="index">
                                <el-image style="width: 100px; height: 100px" :src="item.url" :fit="`cover`" />
                                <el-switch v-model="item.show_content" class="ml-2"
                                    style="--el-switch-on-color: #13ce66; --el-switch-off-color: #ff4949"
                                    active-text="Hiển thị" inactive-text="Ẩn" @change="hanlderShowContent(index)" />
                                <span>Lời chúc</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!isEdit" class="slider-images">
                    <swiper :effect="'cube'" :grabCursor="true" :pagination="true" :cubeEffect="{
                        shadow: true,
                        slideShadows: true,
                        shadowOffset: 20,
                        shadowScale: 0.94,
                    }" :modules="modules" :autoplay="{
                        delay: 10000000,
                        disableOnInteraction: false,
                    }" class="mySwiper">
                        <swiper-slide v-for="(item, index) in listItemImages" :key="index">
                            <div :class="`itemImage ${isShow[index] ? 'hide' : ''}`">
                                <el-image :src="item.url" />
                                <span v-if="!item.show_content" class="show-content"
                                    @click="handleShowContent(index)"><i ref="icon"
                                        :class="`${isShow[index] ? 'ri-arrow-right-circle-line' : 'ri-arrow-left-circle-line'}`"></i></span>
                            </div>
                            <div
                                :class="`content-wish ${!item.show_content ? 'hidden-content' : ''} ${isShow[index] ? 'show' : ''}`">
                                <Descriptions :style="dataSlider.content.slider_1.desc.style"
                                    :content="dataSlider.content.slider_1.desc.content" :isEdit="isEdit"
                                    @getContentDesc="handleContentDesc" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
                <div v-else class="slider-images">
                    <el-image :key="index" :src="listImages[0].url" />
                    <div class="content-wish">
                        <Descriptions :style="dataSlider.content.slider_1.desc.style"
                            :content="dataSlider.content.slider_1.desc.content" :isEdit="isEdit"
                            @getContentDesc="handleContentDesc" />
                    </div>
                </div>
                <Logo :style="dataSlider.content.slider_1.logo.style" :isEdit="isEdit"
                    @getStyleLogo="handleStyleLogo" />
                <TitleVulan :style="dataSlider.content.slider_1.title.style" :isEdit="isEdit"
                    @getStyleTitle="handleStyleTitle" />
                <ButtonAction @handleShowHidePreview="handleShowHidePreview" :isCreate="isCreate"
                    @create="handleCreate" />
            </div>
        </div>
    </div>
</template>
<script>
import Descriptions from './components/Description.vue';
import TitleVulan from './components/Title.vue';
import Logo from './components/Logo.vue';
import ButtonAction from './components/ButtonAction.vue';
import bg_vulan from '@/assets/images/bg/bg_test.jpg';
import bg_wish from '@/assets/images/bg/bg_wish.png';
import bg_end_vulan from '@/assets/images/bg/bg_end_vulan.png';
import ImagesSlider1 from '@/assets/images/vulan/sl1.jpg';
import { Navigation, Pagination, Scrollbar, A11y, EffectCards, Autoplay, EffectCube } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';

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
    // {
    //     url: imageSlider,
    //     type: "images"
    // },
    {
        url: ImagesSlider1,
        type: "images",
        show_content: true
    }
]
const dataSliderTemplate = {
    "id": 1,
    "title": "Vu Lan Template No1",
    "content": {
        "slider_1": {
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
                    "url": "",
                    "show_content": true,
                },
                {
                    "type": "image",
                    "url": "",
                    "show_content": true,
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
        Logo,
        ButtonAction
    },
    data() {
        return {
            dataSlider: dataSliderTemplate,
            allowTouchMove: true,
            // styleLogo: dataSlider.content.slider_1.logo.style,
            // styleTitle: dataSlider.content.slider_1.title.style,
            // styleDesc: dataSlider.content.slider_1.desc.style,
            // contentDesc: dataSlider.content.slider_1.desc.content,
            colorBg: dataSliderTemplate.content.slider_1.background.color,
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
            //modules: [],
        };
    },
    filters: {

    },

    created() {
        //this.listImages = this.dataSlider.content.slider_1.items;
        this.listImages = listImageDefault;
        this.countImages = this.listImages.length;
    },
    mounted() {
        this.colorBackground = this.colorBg

    },
    beforeDestroy() {
    },
    methods: {
        changeColor() {
            this.colorBackground = this.colorBg;
            this.dataSlider.content.slider_1.background.color = this.colorBg;
            this.isCreate = true;
        },
        //get style logo
        handleStyleLogo(data, type) {
            if (Object.keys(data).length > 0) {
                if (type == 'slider_1') {
                    this.dataSlider.content.slider_1.logo.style.left = data.left
                    this.dataSlider.content.slider_1.logo.style.top = data.top
                }
                this.isCreate = true;
            }
        },
        //get style Title
        handleStyleTitle(data, type) {
            if (Object.keys(data).length > 0) {
                if (type == 'slider_1') {
                    this.dataSlider.content.slider_1.title.style.left = data.left
                    this.dataSlider.content.slider_1.title.style.top = data.top
                }
                this.isCreate = true;
            }
        },
        // get infomation
        handleContentDesc(data, type) {
            if (Object.keys(data).length > 0) {
                if (type == 'slider_1') {
                    this.dataSlider.content.slider_1.desc.style.left = data.left
                    this.dataSlider.content.slider_1.desc.style.top = data.top
                    this.dataSlider.content.slider_1.desc.content = data.content
                }
                this.isCreate = true;
            }
        },
        // upload images
        handleUpload(event) {
            const file = event.target.files;
            if (file.length > 0) {

                const promises = [];
                this.listItemImages = []
                for (let index = 0; index < file.length; index++) {
                    promises.push(new Promise((resolve) => {
                        let _file = file[index];
                        this.listFiles.push(_file);
                        const type = _file.type;
                        const reader = new FileReader();

                        reader.onload = (e) => {
                            const dataImages = {};
                            dataImages.type = type;
                            dataImages.url = e.target.result;
                            dataImages.show_content = false;
                            this.listItemImages.push(dataImages);
                            resolve();
                        };

                        reader.readAsDataURL(_file);
                    }));
                }

                Promise.all(promises).then(() => {
                    //console.log('this.listItemImages', this.listItemImages);
                    this.isCreate = true;
                });
            }
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
            this.dataSlider.content.slider_1.items = itemImages;
        },
        // show hide preview
        handleShowHidePreview(data) {
            console.log('dataSlider.content.slider_1.desc.content', this.dataSlider.content.slider_1.desc.content)
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
                const { data } = await vulanRepository.create(formData);
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
