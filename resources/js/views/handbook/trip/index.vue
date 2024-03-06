<template>
    <h1>Hiện tại đang xây dựng trang</h1>
</template>

<script>
    var moment = require('moment')
    import { mapGetters } from 'vuex';
    import Pagination from '@/components/Pagination'
    import HandBookMenu from '@/views/handbook/menu';
    import FlipHandBook from '@/components/Flipbook'
    import imageBook from '@/assets/images/book.png'
    export default {
        name: 'HandBookQuest',
        components: { Pagination, FlipHandBook, HandBookMenu },
        data () {            
            return {
                dialogFormTitle: '',
                dialogFormState: '',
                isLoading: true,
                list: [],
                total: 0,
                listQuery: {
                    page: 1,
                    limit: 10,
                    search: undefined,
                    sort: 'desc',
                    type: '',
                    name: '',
                    department: '',
                    status: '',
                },
                search: '',              
                imageBook: imageBook,
                listRouter: [
                    {
                        'link': ''
                    }
                ]
            }            
        },

        filters:{
            
        },

        created() {
        },

        methods: {
            ...mapGetters(['sidebar','user']),
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
</style>