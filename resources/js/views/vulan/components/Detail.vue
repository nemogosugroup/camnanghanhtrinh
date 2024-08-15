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
                        shadow: false,
                        slideShadows: false,
                        shadowOffset: 0,
                        shadowScale: 0,
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
                                <Descriptions v-if="dataSlider" :style="dataSlider.content.slider_1.desc.style"
                                    :content="dataSlider.content.slider_1.desc.content" :isEdit="isEdit"
                                    @getContentDesc="handleContentDesc" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>
                <div v-else class="slider-images">
                    <el-image :key="index" :src="listImages[0].url" />
                    <div class="content-wish">
                        <Descriptions v-if="dataSlider" :style="dataSlider.content.slider_1.desc.style"
                            :content="dataSlider.content.slider_1.desc.content" :isEdit="isEdit"
                            @getContentDesc="handleContentDesc" />
                    </div>
                </div>
                <Logo v-if="dataSlider" :style="dataSlider.content.slider_1.logo.style" :isEdit="isEdit"
                    @getStyleLogo="handleStyleLogo" />
                <TitleVulan v-if="dataSlider" :style="dataSlider.content.slider_1.title.style" :isEdit="isEdit"
                    @getStyleTitle="handleStyleTitle" />
                <!--<ButtonAction @handleShowHidePreview="handleShowHidePreview" :isCreate="isCreate" @create="handleCreate"
                    :isEditPost="isEditPost" @edit="handleEdit" :isLoading="loading" />-->
            </div>
        </div>
    </div>
</template>
<script>
import Descriptions from '@/backend/views/vulan/components/Description.vue';
import TitleVulan from '@/backend/views/vulan/components/Title.vue';
import Logo from '@/backend/views/vulan/components/Logo.vue';
import ButtonAction from '@/backend/views/vulan/components/ButtonAction.vue';
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
// import AdminRepositoryFactory from '@/backend/respository';
// const vulanRepository = AdminRepositoryFactory.get('vulan');
import RepositoryFactory from '@/utils/RepositoryFactory';
const vulanRepository = RepositoryFactory.get('vulan');

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
import { mapGetters } from "vuex";
import { ElMessage } from "element-plus";
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
            dataSlider: false,
            allowTouchMove: true,
            colorBg: '#ed8b33',
            colorBackground: null,
            listImages: [],
            countImages: 0,
            isEdit: false,
            dialogImageUrl: '',
            dialogVisible: false,
            listItemImages: listImageDefault,
            isShow: [],
            isCreate: false,
            isEditPost: false,
            listFiles: [],
            user_id: false,
            history_id: false,
            loading: false,
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
    computed: {
        ...mapGetters(["user"]),
    },
    async created() {
        const id = this.$route.params && this.$route.params.id;
        this.listImages = listImageDefault;
        this.countImages = this.listImages.length;
        await this.fetch(id);
        this.colorBg = this.dataSlider.content.slider_1.background.color;
        this.listItemImages = this.dataSlider.content.slider_1.items.map((item) => {
            const data = {
                "show_content": item.show_content == "1" ? true : false,
                "url": item.url,
                "type": item.type,
            }
            return data;
        });
    },
    mounted() {
        this.colorBackground = this.colorBg
        this.user_id = this.user.id
    },
    methods: {
        async fetch(id) {
            const { data } = await vulanRepository.detail(id);
            if (data.success) {
                this.dataSlider = data.data;
                this.listItemImages = this.dataSlider.content.slider_1.items.map((item) => item);
            }
            console.log('this.dataSlider', this.dataSlider)
            console.log('this.listItemImages', this.listItemImages)
            console.log('this.dataSlider.content.slider_1.items', this.dataSlider.content.slider_1.items)
        },
        // show content (lời chúc)
        handleShowContent(index) {
            this.isShow[index] = !this.isShow[index];
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
        height: 100vh;
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
