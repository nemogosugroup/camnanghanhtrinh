<template>
    <div class="app-container">
        <div :class="`container-slider vulan-container ${countImages == 1 ? 'move-content' : ''}`">
            <div ref="slider" class="slider-1 bg" :style="`background-color:${colorBg}`">
                <div class="editImages" v-if="isEdit">
                    <el-upload class="editSlider" action="" multiple :limit="5">
                        <el-button class="button roboto-medium">
                            <span class="text">Chỉnh sửa</span> <i class="ri-edit-2-fill"></i>
                        </el-button>
                        <!--<template #tip>
                            <div class="el-upload__tip">
                                jpg/png files with a size less than 500KB.
                            </div>
                        </template>-->
                    </el-upload>
                    <!--<span class="move"><span class="text">Chỉnh sửa</span> <i class="ri-edit-2-fill"></i></span>-->
                    <span class="change-color"><el-color-picker v-model="colorBg" @change="changeColor" /></span>
                </div>
                <div class="slider-images">
                    <!-- <swiper :effect="'cards'" :grabCursor="true" :modules="modules" :allow-touch-move="allowTouchMove"-->
                    <swiper :effect="'cube'" :grabCursor="true" :cubeEffect="{
                        shadow: true,
                        slideShadows: true,
                        shadowOffset: 20,
                        shadowScale: 0.94,
                    }" :pagination="true" :modules="modules" :autoplay="{
                        delay: 10000000,
                        disableOnInteraction: false,
                    }" class="mySwiper">
                        <swiper-slide v-for="(item, index) in listImages" :key="index">
                            <div :class="`itemImage ${isShow[index] ? 'hide' : ''}`">
                                <el-image :src="item.url" />
                                <span v-if="!item.show_content" class="show-content"
                                    @click="handleShowContent(index)"><i ref="icon"
                                        :class="`${isShow[index] ? 'ri-arrow-right-circle-line' : 'ri-arrow-left-circle-line'}`"></i></span>
                            </div>
                            <div
                                :class="`content-wish ${!item.show_content ? 'hidden-content' : ''} ${isShow[index] ? 'show' : ''}`">
                                <Descriptions :style="styleDesc" :content="contentDesc" :isEdit="isEdit"
                                    @getContentDesc="handleContentDesc" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
                <Logo :style="styleLogo" :isEdit="isEdit" @style-logo="handleStyleLogo" />
                <TitleVulan :style="styleTitle" :isEdit="isEdit" />
                <ButtonAction v-if="isEdit" />
            </div>
        </div>
    </div>
</template>
<script>
import Descriptions from '@/backend/views/vulan/components/Description.vue';
import TitleVulan from '@/backend/views/vulan/components/Title.vue';
import Logo from '@/backend/views/vulan/components/Logo.vue';
import ButtonAction from '@/backend/views/vulan/components/ButtonAction.vue';
import ImageSlider from '@/assets/images/slider_image_demo/slider1.png';
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

const listImageDefault = [
    {
        url: ImageSlider,
        type: "images",
        show_content: true
    },
    {
        url: ImagesSlider1,
        type: "images",
        show_content: true
    }
]
const dataSlider = {
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
                    "top": 540,
                    "color": '#fff'
                }
            },
            "items": [
                {
                    "type": "image",
                    "url": "/static/uploads/bg_slider_1.jpg"
                },
                {
                    "type": "image",
                    "url": "/static/uploads/bg_slider_2.jpg"
                },
                // {
                //     "type": "image",
                //     "url": "/static/uploads/bg_slider_1.jpg"
                // },
                // {
                //     "type": "image",
                //     "url": "/static/uploads/bg_slider_1.jpg"
                // }
            ]
        }
    }
}
export default {
    name: 'Detail',
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
            listSliders: listImageDefault,
            ImagesSlider1: ImagesSlider1,
            dataSlider: dataSlider,
            allowTouchMove: true,
            styleLogo: dataSlider.content.slider_1.logo.style,
            styleTitle: dataSlider.content.slider_1.title.style,
            styleDesc: dataSlider.content.slider_1.desc.style,
            contentDesc: dataSlider.content.slider_1.desc.content,
            colorBg: dataSlider.content.slider_1.background.color,
            colorBackground: null,
            listImages: [],
            countImages: 0,
            isEdit: false,
            isShow: []
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
        this.colorBackground = this.colorBg;
    },
    beforeDestroy() {
    },
    methods: {
        changeColor() {
            this.colorBackground = this.colorBg;
        },
        handleShowContent(index) {
            this.isShow[index] = !this.isShow[index]
        },
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

.swiper-slide {
    // display: flex;
    // align-items: center;
    // justify-content: center;
    border-radius: 18px;
    font-size: 22px;
    font-weight: bold;
    color: #fff;
}

.bg {
    background-size: cover;
    background-position: left;
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

.slider-images {
    width: 100%;
    height: 100%;
}
</style>
