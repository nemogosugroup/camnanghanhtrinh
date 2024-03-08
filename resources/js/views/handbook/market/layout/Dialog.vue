<template>
    <el-dialog
        v-model="isShowDialog"
        width="500px" 
        center
        :fullscreen="false"
        @close="closeDialog"
        align-center
        :show-close="false"
        class="wp-dialog"
    >
        <template #header="{ titleClass }">
            <div class="header-dialog">
                <div class="flex">
                    <el-image style="width: 150px; height: 120px" :src="item.url_image" fit="cover" />
                    <h4 :class="titleClass">{{ item.title }}</h4>
                </div>
            </div>
        </template>
        <div class="content-dialog">
            <el-text class="desc mb-1">
                {{ type == 'course' ? item.description : item.description_in_game }}
            </el-text>
            <div class="price">
                <strong><i class="ri-money-dollar-circle-fill"></i></strong>
                <span>{{ commasThousands(item.gold) }}</span>
            </div>
        </div>
        <el-divider border-style="double" />
        <template #footer>
            <div v-if="!isShowButton" class="mb10">
                <el-text type="success" tag="b">{{ notice }}</el-text>
            </div>
            <span class="dialog-footer buttonSave">
                <el-button @click="closeDialog">Quay lại</el-button
                >
                <span v-if="isShowButton">
                    <el-button v-if="type == 'course'" type="primary" @click="handleSave" :loading="isLoading" class="ms10">
                        Đổi
                    </el-button>
                    <el-button v-else type="primary" @click="handleSave" :loading="isLoading" class="ms10">
                        Mua
                    </el-button>
                </span>
            </span>
        </template>
    </el-dialog>
</template>
<script>
    import { mapGetters } from 'vuex';
    import RepositoryFactory from "@/utils/RepositoryFactory";
    import Helper from '@/helper';
    const UserRepository = RepositoryFactory.get('user');
    export default {
        name: 'DialogMarket',
        props:{
            showDialog:{
                type: Boolean,
                default: false
            },
            isShowButton:{
                type: Boolean,
                default: false
            },
            item:{
                type: Object,
                default: {}
            },
            type:{
                type: String,
                default: 'course'
            },
        },
        data () {            
            return {
                isLoading: false,
                list: [],       
                isShowDialog: this.showDialog,
                message: 'Bạn không đủ gold để đổi!',
                notice: this.type ==  Helper.typeMarket.course ? 'Bạn đã sở hữu khoá học này!' : 'Bạn đã sở hữu trang bị này!'
            }
        },

        filters:{
            
        },
        computed: {
            ...mapGetters(["user", "gold"]),
        },        
        watch: {
            showDialog(newValue) {
                this.isShowDialog = newValue;
            },
        },
        created() {
        },
        methods: {
            closeDialog() {
                this.isShowDialog = false;
                this.$emit('show',{'isShow':false, 'isShowButton':true, 'data': {}})
            },
            // đổi trang bị hoặc đổi khoá học
            async handleSave(){
                try {
                    this.isLoading = true;
                    let goldUser = parseInt(this.gold);
                    let goldItem = parseInt(this.item.gold);
                    if(goldUser < goldItem){
                        this.$message({
                            message: this.message,
                            type: 'error'
                        })
                        this.isLoading = false
                    }else{
                        let gold = goldUser - goldItem
                        let postData = {
                            'type': this.type,
                            'item_id': this.item.id,
                            'gold': gold,
                        }
                        if(this.type == Helper.typeMarket.equipment)
                            postData['position'] = this.item.type.position;

                        const { data } = await UserRepository.add( postData );
                        if(data.success){
                            // update vàng, khoá học/trang bị của user
                            if(data.data){                                
                                this.$emit('update', { 'id': this.item.id, 'gold': gold });
                            }
                            
                            this.$message({
                                message: data.message,
                                type: 'success'
                            });
                            this.closeDialog();
                        }else{
                            this.$message({
                                message: data.message,
                                type: 'error'
                            });
                        }
                        this.isLoading = false;
                    }
                } catch (error) {
                    console.log('error', error)
                }
                
            },
            commasThousands(number) {
                return number?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }

</script>
<style lang="scss" scoped>
@import '@style/variables.scss';
.el-dialog__header{
    margin: 0;
}
.wp-dialog {    
    .header-dialog {
        border-radius: $size10;
        padding: $pd10;
        border: 1px solid $lightGray;
        .flex {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: flex-start;
            align-items: stretch;
            align-content: stretch;
        }
        .el-dialog__title {
            margin: 0;
            font-size: $fontsize16;
            margin-left: $size10;
            text-align: left;
        }
    }
    .price {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        align-content: stretch;
        font-size: $size24;
        font-weight: 700;
        strong {
            font-size: $size28;
            color:$yellow;
            margin-right: $size5;
        }
    }
}
</style>
