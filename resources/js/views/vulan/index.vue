<template>
    <div class="common-layout">
        <el-container>
            <el-header>
                <Navbar />
            </el-header>
            <el-main>
                <el-row :gutter="20">
                    <el-col :span="12" v-for="(item, index) in listTemplates" :key="index">
                        <div class="item">
                            <el-image :src="item.demo_img" />
                            <el-button type="warning" @click="chooseTemplate(index)">Chọn template</el-button>
                        </div>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="6" v-for="(item, index) in lists" :key="index">
                        <div class="item-vulan">
                            <el-image :src="item.demo_img ? item.demo_img : imagesDefault" />
                            <div class="action" v-if="item.edit">
                                <router-link :to="`/vulan/edit/${item.id}`"><button><i
                                            class="ri-edit-2-fill"></i></button></router-link>
                                <button @click="handleRemove(item.id)"><i class="ri-delete-bin-6-fill"></i></button>
                            </div>
                            <el-button type="warning" @click="handleReadmore(item.id)">Xem</el-button>
                        </div>
                    </el-col>
                </el-row>
            </el-main>
        </el-container>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Navbar from './LayoutVuLan/components/Navbar';
import RepositoryFactory from '@/utils/RepositoryFactory';
import ImagesSlider1 from '@/assets/images/vulan/sl1.jpg';
const vulanRepository = RepositoryFactory.get('vulan');
export default {
    name: 'VuLanTemplates',
    components: { Navbar },
    data() {
        return {
            imagesDefault: ImagesSlider1,
            listTemplates: [],
            lists: [],
            isLoading: true,
            formData: {
                title: "",
                content: ""
            },
            id: false,
            title: '',
        }
    },

    filters: {},

    async created() {
        await this.fetch();
    },
    computed: {
        ...mapGetters(["user"]),
    },
    methods: {
        async fetch() {
            this.isLoading = true;
            const { data } = await vulanRepository.list();
            this.isLoading = false;
            if (data.success) {
                const results = data.data;
                const templates = data.templates;
                this.listTemplates = templates.map(item => {
                    item.edit = false;
                    return item
                });
                this.lists = results.map(item => {
                    item.edit = this.user.id == item.user_id ? true : false;
                    return item
                });
                console.log('this.lists', this.lists);
            }
        },
        chooseTemplate(index) {
            if (Object.keys(this.user).length > 0) {
                this.$router.push(`/admin/vulan/template-${index + 1}`);
            } else {
                this.$router.push('/login')
            }
        },
        handleReadmore(id) {
            this.$router.push(`/vulan/detail/${id}`);
        },
        handleRemove() {

        }
    }
}

</script>

<style lang="scss" scoped>
.item {
    text-align: center;

    .el-button {
        margin-top: 5px;
    }
}

:deep(.el-header) {
    padding: 0
}

.item-vulan {
    position: relative;
    border-radius: 10px;
    border: 1px solid #ccc;
    top: 0px;
    transition: all .3s ease-in-out;
    padding: 10px;
    margin-top: 25px;

    &:hover {
        top: 10px;
        transition: all .3s ease-in-out;
    }

    .action {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;

        .button {
            margin-left: 5px;
        }
    }
}
</style>
