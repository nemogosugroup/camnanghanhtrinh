<template>
    <div class="app-container">  
        <search-post :list-query="listQuery.title" @showdialog="handleShowDialog" @search="handleSearch"></search-post>
        <table-data @showdialog="handleShowDialog" :list="list" :isLoading="isLoading"></table-data>
        <pagination 
            v-show="total>10" 
            v-bind:total="total" 
            v-model:page="listQuery.page" 
            v-model:limit="listQuery.limit"
            layout="prev, pager, next" 
        @pagination="fetch" />
        <dialog-mission :dialog-form-visible="dialogFormVisible" :dialog-status="dialogStatus" :formData="formData" :title-dialog="titleDialog" @hidedialog="handleHideDialog" :listLevels="listLevels" :list-equipments="listEquipments" :list-categories="listCategories" :list-missions="listMissions" @update-data="handleUpdateData"></dialog-mission>
        <dialog-remove :title-dialog="titleDialog" :title="title" :dialog-status="dialogStatus" :dialog-visible-remove="dialogVisibleRemove" @hiddendialog="handleHideDialog" @delete="handleRemove"></dialog-remove>
    </div>
</template>

<script>
    import Pagination from '@/components/Pagination'
    import SearchPost from '@/backend/components/SearchPost';
    import TableData from './components/Data';
    import DialogMission from './components/Dialog';
    import DialogRemove from '@/backend/components/RemovePost';
    import AdminRepositoryFactory from '@/backend/respository';
    const missionRepository = AdminRepositoryFactory.get('mission');
    export default {
        name: 'Missions',
        components: { Pagination, SearchPost, TableData, DialogMission, DialogRemove},
        data () {        
            return {
                list: [],
                listLevels: [],
                listMissions: [],
                listEquipments: [],
                listQuery: {
                    page: 1,
                    limit: 10,
                    sort: 'desc',
                    title:'',
                },  
                listCategories:[],            
                isLoading: true,
                dialogStatus:'',
                dialogFormVisible:false,
                dialogVisibleRemove:false,
                titleDialog:'',
                total:0,
                formData:{
                    parent_id:null,
                    sub_category_id: '',
                    title: '',                    
                    description: '',
                    link: '',
                    target: 0,
                    method: '',
                    gold:0,
                    equipment_id:null,
                    experience:0,                    
                    level_id: 1,
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
                const { data } = await missionRepository.list( this.listQuery );
                this.isLoading = false;                  
                if(data.success) {
                    const results = data.data.data;
                    this.listCategories = data.categories;                    
                    this.list = results.map(item => {
                        item.edit = false;
                        item.isLoadingSave = false;
                        item.isLoadingDelete = false;
                        return item
                    });
                    this.total = data.data.total;
                    if(data.levels){
                        this.listLevels = data.levels.map(item => {
                            return item
                        });
                    }
                    if(data.missions){
                        this.listMissions = data.missions.map(item => {
                            return item
                        });
                    }
                    if(data.equipments){
                        this.listEquipments = data.equipments.map(item => {
                            return item
                        });
                    }
                }
            },
            // show dialog
            handleShowDialog(data){                            
                this.dialogStatus = data.status ? data.status : 'create';
                this.titleDialog = this.dialogStatus == 'create' ? 'Tạo nhiệm vụ mới' : 'Chỉnh sửa nhiệm vụ';
                if (data.status == 'delete') {
                    this.dialogVisibleRemove = true;
                    this.id = data.id;
                    this.title = data.title;
                    this.titleDialog = 'Bạn có muốn xoá nhiệm vụ'
                }else{
                    this.dialogFormVisible = true;
                }

                if (data.status == 'edit') {
                    let item = data.item;                    
                    this.formData = Object.assign(this.formData, item);
                    this.formData.parent_id = this.formData.parent_id ? parseInt(this.formData.parent_id) : null;
                    this.formData.equipment_id = this.formData.equipment_id ? parseInt(this.formData.equipment_id) : null;
                }
            },
            // hide dialog
            handleHideDialog(){
                this.dialogStatus = '';
                this.dialogFormVisible = false;
                this.dialogVisibleRemove = false;
                this.titleDialog = '';
                this.formData = {
                    parent_id:null,
                    sub_category_id: '',
                    title: '',                    
                    description: '',
                    link: '',
                    target: 0,
                    method: '',
                    gold:0,
                    equipment_id:null,
                    experience:0,                    
                    level_id: 1,
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
                const { data } = await missionRepository.delete( this.id );
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
                    title:data,
                }, 
                this.fetch();
            },
        }
    }

</script>

<style lang="scss" scoped>
</style>