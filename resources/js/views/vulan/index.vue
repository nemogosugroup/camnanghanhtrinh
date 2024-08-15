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
            </el-main>
        </el-container>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Navbar from './LayoutVuLan/components/Navbar';
import RepositoryFactory from '@/utils/RepositoryFactory';
const vulanRepository = RepositoryFactory.get('vulan');
export default {
    name: 'VuLanTemplates',
    components: { Navbar },
    data() {
        return {
            listTemplates: [],
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

    created() {
        this.fetch();
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
                this.lists = results.map(item => {
                    item.edit = false;
                    return item
                });
                this.listTemplates = templates.map(item => {
                    item.edit = false;
                    return item
                });
            }
        },
        chooseTemplate(index) {
            if (Object.keys(this.user).length > 0) {
                this.$router.push(`/admin/vulan/template-${index + 1}`);
            } else {
                this.$router.push('/login')
            }
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
</style>
