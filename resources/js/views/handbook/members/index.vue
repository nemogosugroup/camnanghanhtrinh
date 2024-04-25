<template>
    <el-card id="membersChartWrapper" class="box-card">
        <el-row :gutter="20">
            <el-col v-if="infoUser.is_director === 0" :span="24">
                <MembersBlock/>
            </el-col>
            <el-col v-else :span="24">
                <DepartmentsBlock :departments="departments"/>
                <DialogMembers/>
            </el-col>
        </el-row>
    </el-card>
</template>

<script>
import {mapGetters} from 'vuex';
import DepartmentsBlock from "@/views/handbook/members/components/Departments.vue"
import MembersBlock from "@/views/handbook/members/components/Members.vue"
import DialogMembers from "@/views/handbook/members/components/Dialog.vue";
import RepositoryFactory from "@/utils/RepositoryFactory";
const UserRepository = RepositoryFactory.get('user');

export default {
    name: 'HandBookMembers',
    components: {DepartmentsBlock, MembersBlock, DialogMembers},
    data() {
        return {
            list: [],
            infoUser: {},
            departments: [
                {id: 1, tags: ["Department"], name: "BAN GIÁM ĐỐC", dept: ""},

                {id: 2, pid: 1, tags: ["Department"], name: "KHỐI KINH DOANH", dept: ""},
                {id: 3, pid: 1, tags: ["Department"], name: "KHỐI TÀI CHÍNH - KẾ TOÁN", dept: ""},
                {id: 4, pid: 1, tags: ["Department"], name: "KHỐI CÔNG NGHỆ", dept: ""},
                {id: 5, pid: 1, tags: ["Department"], name: "KHỐI TỔ CHỨC NHÂN SỰ", dept: ""},
                {id: 6, pid: 1, tags: ["Department"], name: "KHỐI HỖ TRỢ", dept: ""},

                // KHỐI KINH DOANH
                {id: 7, pid: 2, tags: ["Department"], name: "TRUNG TÂM GBC", dept: ""},
                {id: 8, pid: 2, tags: ["Department"], name: "TRUNG TÂM GOTA", dept: "H-04"},
                {id: 9, pid: 2, tags: ["Department"], name: "TRUNG TÂM GAMO", dept: "H-222"},
                {id: 10, pid: 2, tags: ["Department"], name: "TRUNG TÂM CMC", dept: ""},

                // KHỐI TÀI CHÍNH KẾ TOÁN
                {id: 11, pid: 3, tags: ["Department"], name: "BỘ PHẬN KẾ TOÁN", dept: "H-03"},
                {id: 12, pid: 3, tags: ["Department"], name: "BỘ PHẬN IPO", dept: ""},

                // KHỐI TÀI CHÍNH KẾ TOÁN
                {id: 13, pid: 4, tags: ["Department"], name: "TRUNG TÂM CÔNG NGHỆ (ITC)", dept: "H-0211"},
                {id: 14, pid: 4, tags: ["Department"], name: "TRUNG TÂM NEMO", dept: "H190423"},
                {id: 15, pid: 4, tags: ["Department"], name: "BỘ PHẬN KỸ THUẬT HẠ TẦNG", dept: ""},

                // KHỐI TỔ CHỨC NHÂN SỰ
                {id: 16, pid: 5, tags: ["Department"], name: "BỘ PHẬN HÀNH CHÍNH NHÂN SỰ", dept: "H-06"},
                {id: 17, pid: 5, tags: ["Department"], name: "TRUNG TÂM ĐÀO TẠO & TRUYỀN THÔNG NỘI BỘ", dept: "HCM-17"},
                {id: 18, pid: 5, tags: ["Department"], name: "TỔ TRỢ LÝ", dept: ""},

                // KHỐI HỖ TRỢ
                {id: 19, pid: 6, tags: ["Department"], name: "TRUNG TÂM ĐỐI NGOẠI DỊCH THUẬT (IRC)", dept: "H-12"},
                {id: 20, pid: 6, tags: ["Department"], name: "BỘ PHẬN THIẾT KẾ", dept: ""},
                {id: 21, pid: 6, tags: ["Department"], name: "TRUNG TÂM COMMUNITY CARE (3C)", dept: ""},
                {id: 22, pid: 6, tags: ["Department"], name: "NHÓM TƯ VẤN PHÁP CHẾ", dept: ""},
            ]
        }
    },
    computed: {
        ...mapGetters(["user"]),
    },
    created() {
        this.getUser();
        this.getMembers(this.infoUser.dept);
    },
    mounted() {
        this.emitter.on('is-close-members-dialog', () => {
            document.querySelector('article.article').classList.remove('showing_dialog');
        });
        this.emitter.on('is-open-members-dialog', dept => {
            this.getMembers(dept);
            document.querySelector('article.article').classList.add('showing_dialog');
            this.emitter.emit('is-open-members-dialog-from-main');
        });
    },
    methods: {
        getUser() {
            this.infoUser = this.user;
        },
        async getMembers(dept) {
            this.members = [];
            const { data } = await UserRepository.getMembersData({dept: dept});
            if(data.success) {
                this.emitter.emit('isset-members-data', data.data);
            }
        }
    }
}

</script>

<style lang="scss" scoped>
@import "@style/custom.scss";

.open-book > * {
    z-index: 999 !important;
}

.open-book {
    .article {
        z-index: 2 !important;
    }
}
</style>