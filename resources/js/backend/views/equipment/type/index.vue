<template>
    <div class="app-container">  
        <search-type-equipment
            :list-query="listQuery"
            :list-positions="listPositions"
            @showdialog="handleShowDialog"
            @search="handleSearch"
        ></search-type-equipment>
        <table-data @showdialog="handleShowDialog" :list="list" :isShowCategory="isShowCategory"></table-data>
        <pagination 
            v-show="total>10" 
            v-bind:total="total" 
            v-model:page="listQuery.page" 
            v-model:limit="listQuery.limit"  
        @pagination="fetch" />
        <dialog-type
            :dialog-form-visible="dialogFormVisible"
            :dialog-status="dialogStatus"
            :formData="formData"
            :title-dialog="titleDialog"
            @hidedialog="handleHideDialog"
            :list-categories="listCategories"
            :list-positions="listPositions"
            @update-data="handleUpdateData"
        ></dialog-type>

        <dialog-remove
            :title-dialog="titleDialog"
            :title="title"
            :dialog-status="dialogStatus"
            :dialog-visible-remove="dialogVisibleRemove"
            @hiddendialog="handleHideDialog"
            @delete="handleRemove"
        ></dialog-remove>
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination';
    import SearchTypeEquipment from './search';
    import DialogRemove from '@/backend/components/RemovePost';
    import TableData from '@/backend/views/equipment/components/DataType';
    import DialogType from './components/dialog';
    import AdminRepositoryFactory from '@/backend/respository';
    const TypeEquipmentAdminRepository = AdminRepositoryFactory.get('typeEquipment');
    export default {
        name: 'TypeEquipment',
        components: { Pagination, SearchTypeEquipment, TableData, DialogType, DialogRemove},
        data () {        
            return {
                list: [],
                listQuery: {
                    page: 1,
                    limit: 10,
                    sort: 'desc',
                    title:'',
                    position:'',
                },
                isLoading: true,
                dialogStatus:'',
                dialogFormVisible:false,
                dialogVisibleRemove:false,
                titleDialog:'',
                total:0,
                formData:{
                    title: '',
                    category_id: '',
                    position: '',
                },
                id:false,
                title:'',
                listCategories:[],
                listPositions:[],
                isShowCategory:true
            }            
        },

        filters:{
            
        },

        created() {         
            this.fetch();
        },
        mounted() {
           
        },

        methods: {
            async fetch(){
                this.isLoading = true;
                const { data } = await TypeEquipmentAdminRepository.list( this.listQuery );
                this.isLoading = false;                  
                if(data.success) {
                    const results = data.data.data;
                    this.listCategories = data.categories;                    
                    this.listPositions = data.positions;
                    this.list = results.map(item => {
                        item.edit = false;
                        item.isLoadingSave = false;
                        item.isLoadingDelete = false;
                        return item
                    });
                    this.total = data.data.total;
                }
            },
            // show dialog
            handleShowDialog(data){              
                this.dialogStatus = data.status ? data.status : 'create';
                this.titleDialog = this.dialogStatus == 'create' ? 'Tạo loại trang bị mới' : 'Chỉnh sửa loại trang bị';
                if (data.status == 'delete') {
                    this.dialogVisibleRemove = true;
                    this.id = data.id;
                    this.title = data.title;
                    this.position = data.position;
                    this.titleDialog = 'Bạn có muốn xoá loại trang bị'
                }else{
                    this.dialogFormVisible = true;
                }
                
                if (data.status == 'edit') {
                    let item = data.item;
                    this.formData = {
                        'title':item.title,
                        'category_id':item.category_id,
                        'position':item.position,
                        'id':item.id
                    }
                    console.log('this.formData', this.formData)
                }
            },
            // hide dialog
            handleHideDialog(){
                this.dialogStatus = '';
                this.dialogFormVisible = false;
                this.dialogVisibleRemove = false;
                this.titleDialog = '';
                this.formData = {
                    title: '',
                    category_id:'',
                    position:'',
                }
            },
            // update data
            handleUpdateData(data){
                switch (data.status) {
                    case 'edit':
                        this.list.filter( (item) => {
                            if (item.id == data.item.id) {
                                let results = data.item;
                                item.edit = false;
                                item.isLoadingSave = false;
                                item.isLoadingDelete = false;
                                item = Object.assign(item, results);
                            }
                        })
                        break;
                    default:
                        this.list.unshift(data.item);
                        break;
                }
                this.handleHideDialog();
            },
            // remove
            async handleRemove(){
                const { data } = await TypeEquipmentAdminRepository.delete( this.id );
                if (data.success) {
                    this.$message({
                        message: data.message,
                        type: 'success'
                    })
                    this.handleHideDialog();
                    this.fetch();
                }else{
                    this.$message({
                        message: data.message,
                        type: 'error'
                    })
                }
            },
            // search 
            handleSearch(data){
                this.listQuery = {
                    page: 1,
                    limit: 10,
                    sort: 'desc',
                    title:data.title,
                    position:data.position,
                },
                this.fetch();
            },
        }
    }

</script>

<style lang="scss" scoped>
</style>