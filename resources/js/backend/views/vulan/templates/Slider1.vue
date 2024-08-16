<template>
    <div class="app-container">
        <div class="container-slider vulan-container">
            <div ref="slider" class="slider-1 bg" :style="`background-color:${colorBg}`">
                <div v-if="isEdit" class="editImages">
                    <span class="button roboto-medium" @click="hanldeBack">
                        <span class="text">Trở lại</span> <i class="ri-arrow-go-back-line"></i>
                    </span>
                    <div class="uploadImages">
                        <span class="button roboto-medium">
                            <input class="hidden-input" type="file" multiple @change="(event) => handleUpload(event)"
                                accept="image/jpeg">
                            <span class="text">Thêm ảnh</span> <i class="ri-edit-2-fill"></i>
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
                <div v-else class="editImages">
                    <span class="button roboto-medium" @click="hanldeBack">
                        <span class="text">Trở lại</span> <i class="ri-arrow-go-back-line"></i>
                    </span>
                </div>
                <div v-if="!isEdit" class="slider-images">
                    <swiper :effect="'fade'" :grabCursor="true" :modules="modules" :autoplay="{
                        delay: 1000,
                        disableOnInteraction: false,
                    }" class="mySwiper">
                        <swiper-slide v-for="(item, index) in listItemImages" :key="index">
                            <div :class="`itemImage ${isShow[index] ? 'hide' : ''}`">
                                <el-image :src="item.url" :fit="`cover`" />
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
                    <el-image :key="index" :src="listImages[0].url" :fit="`cover`" />
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
                <ButtonAction @handleShowHidePreview="handleShowHidePreview" :isCreate="isCreate" @create="handleCreate"
                    :isEditPost="isEditPost" @edit="handleEdit" :isPublic="false" :isLoading="loading" />
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
import { Navigation, Pagination, Scrollbar, A11y, EffectCards, Autoplay, EffectCube, EffectFade } from 'swiper/modules';
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
    {
        url: ImagesSlider1,
        type: "images",
        show_content: true
    }
]
import { mapGetters } from "vuex";
import { ElMessage } from "element-plus";

export default {
    name: 'Vulan',
    components: {
        Swiper,
        SwiperSlide,
        Descriptions,
        TitleVulan,
        Logo,
        ButtonAction,
    },
    data() {
        return {
            dataSlider: false,
            allowTouchMove: true,
            colorBg: '#ed8b33',
            colorBackground: null,
            listImages: [],
            countImages: 0,
            isEdit: true,
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
            modules: [Navigation, Pagination, Scrollbar, A11y, EffectCards, Autoplay, EffectCube, EffectFade],
            //modules: [],
        };
    },
    filters: {

    },
    computed: {
        ...mapGetters(["user"]),
    },
    async created() {
        this.listImages = listImageDefault;
        this.countImages = this.listImages.length;
        await this.getDetail();
        this.colorBg = this.dataSlider.content.slider_1.background.color;
    },
    mounted() {
        this.colorBackground = this.colorBg
        this.user_id = this.user.id
    },
    beforeDestroy() {
    },
    methods: {
        async getDetail() {
            const { data } = await vulanRepository.detail(1);
            if (data.success) {
                this.dataSlider = data.data;
                this.dataSlider.template_id = data.data.id; // update template Id
                this.dataSlider.id = false; // xoá id id này sẽ để dùng history_id
            }
        },
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
                    this.dataSlider.content.slider_1.title.style.color = data.color
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
                _item.url = item.url;
                _item.type = "image";
                _item.show_content = item.show_content;
                itemImages.push(_item);
            }
            this.dataSlider.content.slider_1.items = itemImages;
        },
        // show hide preview
        handleShowHidePreview(data) {
            this.isEdit = data // show hide prevew
        },
        // show content (lời chúc)
        handleShowContent(index) {
            this.isShow[index] = !this.isShow[index];
        },
        //update
        async handleEdit(check) {
            if (check && this.dataSlider.id) {
                this.loading = true;
                const formData = new FormData();
                console.log('this.dataSlider.content', this.dataSlider.content);
                formData.append("content", JSON.stringify(this.dataSlider.content));
                formData.append("user_id", this.user_id);
                formData.append("template_id", this.dataSlider.template_id);
                if (this.listFiles.length > 0) {
                    this.listFiles.forEach((file, index) => {
                        formData.append(`files[${index}][file]`, file);
                        formData.append(`files[${index}][type]`, "image");
                        formData.append(`files[${index}][show_content]`, this.listItemImages[index].show_content ? 1 : 0);
                    });
                    this.listFiles = [];
                }
                try {
                    const { data } = await vulanRepository.update(formData, this.dataSlider.id);
                    if (data.success) {
                        this.dataSlider = data.data;
                        this.isEditPost = true;
                        this.isCreate = false;
                        ElMessage.success("cập nhập thành công");
                        this.listItemImages = this.dataSlider.content.slider_1.items.map((item) => {
                            const data = {
                                "show_content": item.show_content == "1" ? true : false,
                                "url": item.url,
                                "type": item.type,
                            }
                            return data;
                        });
                    }
                } catch (error) {
                    console.error('error', error)
                }
                this.loading = false;
            }
        },
        //create
        async handleCreate(check) {
            if (check) {
                this.loading = true;
                const formData = new FormData();
                // Thêm nhiều file vào FormData
                if (this.user_id) {
                    if (this.listFiles.length > 0) {
                        this.listFiles.forEach((file, index) => {
                            formData.append(`files[${index}][file]`, file);
                            formData.append(`files[${index}][type]`, "image");
                            formData.append(`files[${index}][show_content]`, this.listItemImages[index].show_content ? 1 : 0);
                        });
                        formData.append("content", JSON.stringify(this.dataSlider.content));
                        formData.append("template_id", this.dataSlider.template_id);
                        formData.append("user_id", this.user_id);
                        try {
                            const { data } = await vulanRepository.create(formData);
                            if (data.success) {
                                this.dataSlider = data.data;
                                this.$router.push("/vulan/detail/" + this.dataSlider.id)
                                this.isEditPost = true;
                                this.isCreate = false;
                                this.listItemImages = this.dataSlider.content.slider_1.items.map((item) => {
                                    const data = {
                                        "show_content": item.show_content == "1" ? true : false,
                                        "url": item.url,
                                        "type": item.type,
                                    }
                                    return data;
                                });
                                this.listFiles = [];
                                console.log('this.listItemImages', this.listItemImages);
                                console.log('this.dataSlider.content.slider_1.items', this.dataSlider.content.slider_1.items);
                                ElMessage.success("Tạo mới thành công");
                            }
                        } catch (error) {
                            console.error('error', error)
                        }
                    } else {
                        ElMessage.error("Vui lòng chọn ảnh nền");
                    }
                    this.loading = false;
                } else {
                    ElMessage.error("Bạn không có quyền tạo");
                }
            }
        },
        hanldeBack() {
            this.$router.push({ name: "VuLanIndex" });
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
        z-index: 9999;
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
