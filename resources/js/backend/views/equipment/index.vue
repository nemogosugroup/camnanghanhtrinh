<template>
    <div class="app-container">  
        <search-equipment
            :list-query="listQuery"
            :list-positions="listPositions"
            @showdialog="handleShowDialog"
            @search="handleSearch"
        ></search-equipment>
        <table-data @showdialog="handleShowDialog" :list="list" :isLoading="isLoading"></table-data>
        <pagination 
            v-show="total>10" 
            v-bind:total="total" 
            v-model:page="listQuery.page" 
            v-model:limit="listQuery.limit"  
            @pagination="fetch"
        />
        <dialog-equipment
            :dialog-form-visible="dialogFormVisible"
            :dialog-status="dialogStatus"
            :formData="formData"
            :title-dialog="titleDialog"
            @hidedialog="handleHideDialog"
            :list-types="listTypes"
            :list-medals="listMedals"
            @update-data="handleUpdateData"
        ></dialog-equipment>
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
    import Pagination from '@/components/Pagination'
    import SearchEquipment from './search';
    import TableData from './components/Data';
    import DialogEquipment from './components/Dialog';
    import DialogRemove from '@/backend/components/RemovePost';
    import AdminRepositoryFactory from '@/backend/respository';
    import SearchTypeEquipment from "@/backend/views/equipment/type/search.vue";
    const equipmentRepository = AdminRepositoryFactory.get('equipment');
    export default {
        name: 'Equipments',
        components: {SearchTypeEquipment, Pagination, SearchEquipment, TableData, DialogEquipment, DialogRemove},
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
                listTypes:[],
                listMedals:[],
                listPositions:[],
                isLoading: true,
                dialogStatus:'',
                dialogFormVisible:false,
                dialogVisibleRemove:false,
                titleDialog:'',
                total:0,
                formData:{
                    title: '',
                    type_equipment_id: '',
                    medal_id: '',
                    description_designer: '',
                    description_in_game: '',
                    method: '',
                    url_image: '',
                    gold: ''
                },
                id:false,
                title:'',
            }            
        },

        filters:{
            
        },

        created() {
            this.fetch();
        },

        methods: {
            async fetch(){
                this.isLoading = true;     
                const { data } = await equipmentRepository.list( this.listQuery );
                this.isLoading = false;                  
                if(data.success) {
                    const results = data.data.data;
                    this.listTypes = data.types;
                    this.listMedals = data.medals;
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
                this.titleDialog = this.dialogStatus == 'create' ? 'Tạo trang bị mới' : 'Chỉnh sửa trang bị';
                if (data.status == 'delete') {
                    this.dialogVisibleRemove = true;
                    this.id = data.id;
                    this.title = data.title;
                    this.titleDialog = 'Bạn có muốn xoá trang bị'
                }else{
                    this.dialogFormVisible = true;
                }
                
                if (data.status == 'edit') {
                    let item = data.item;
                    this.formData = {
                        'id':item.id,
                        'title':item.title,
                        'type_equipment_id':item.type_equipment_id,
                        'medal_id':item.medal_id,
                        'description_designer':item.description_designer,
                        'description_in_game':item.description_in_game,
                        'method':item.method,
                        'url_image':item.url_image,
                        'gold':item.gold,
                    }
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
                    type_equipment_id: '',
                    medal_id: '',
                    description_designer: '',
                    description_in_game: '',
                    method: '',
                    url_image:'',
                    gold:1,
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
                const { data } = await equipmentRepository.delete( this.id );
                if (data.success) {
                    this.$message({
                        message: data.message,
                        type: 'success'
                    })
                    this.handleHideDialog();
                    await this.fetch();
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