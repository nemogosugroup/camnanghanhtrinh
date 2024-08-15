<template>
    <div class="app-container">
        <table-data :list="list" :isLoading="isLoading"></table-data>
    </div>
</template>

<script>
import TableData from './components/Data';
import AdminRepositoryFactory from '@/backend/respository';

const VuLanRepository = AdminRepositoryFactory.get('vulanTemplates');

export default {
    name: 'VuLanTemplates',
    components: {TableData},
    data() {
        return {
            list: [],
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
        this.getDetail(1);
    },

    methods: {
        async fetch() {
            this.isLoading = true;
            const {data} = await VuLanRepository.list();
            this.isLoading = false;
            if (data.success) {
                const results = data.data;
                this.list = results.map(item => {
                    item.edit = false;
                    return item
                });
            }
        },
        async getDetail(id) {
            const {data} = await VuLanRepository.detail(id);
            if (data.success) {
                console.log(data)
            }
        }
    }
}

</script>

<style lang="scss" scoped>
</style>