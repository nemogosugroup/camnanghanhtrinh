<template>
    <el-scrollbar height="500px">
        <div class="list-quest">
            <div v-for="(item, index) in listData" :key="index" class="item">
                <div class="header-item">
                    <div class="flex">
                        <h6>{{ item.title }}</h6>
                        <span>{{ countQuest(item) }}</span>
                    </div>
                    <el-divider />
                    <div class="flex">
                        <div class="content">
                            <p>Mô tả: <el-text>{{ item.description }}</el-text></p>
                            <p>Phần thưởng: <el-text>{{ item.gold }}</el-text><strong><i class="ri-money-dollar-circle-fill"></i></strong></p>
                        </div>      
                        <div class="link collapsible" v-if="item.user_missions">
                            <el-button 
                                v-if="item.user_missions.status == '01'" 
                                @click="handleUpdateQuest(item.user_missions)" 
                                type="success">Nhận
                            </el-button>
                            <span v-else class="icon-success"><i class="ri-check-line"></i></span>
                        </div>                  
                        <div class="link collapsible" v-else>
                            <div v-if="item.childrents.length">
                                <slot v-if="isCompleteQuest(item)">
                                    <span @click="toggleCollapsible(index)" class="icon-success"><i class="ri-check-line"></i></span>
                                </slot>
                                <el-button v-else type="danger" class="link-quest" @click="toggleCollapsible(index)">
                                    Chưa
                                </el-button>
                            </div> 
                            <el-button v-else class="link-quest" @click="handleCreateQuest(item)" type="danger">Chưa</el-button>
                        </div>
                    </div>
                </div>
                <div v-if="item.childrents.length">
                    <ul class="sub-list" :class="{ active: activeIndex === index }">
                        <li v-for="(quest, idx) in item.childrents" :key="idx">
                            <div class="header-item">
                                <div class="flex">
                                    <h6>{{ quest.title }}</h6>
                                    <span>{{ countQuest(quest) }}</span>
                                </div>
                                <el-divider />
                                <div class="flex">
                                    <div class="content">
                                        <p>Mô tả: <el-text>{{ quest.description }}</el-text></p>
                                        <p>Phần thưởng: <el-text>{{ quest.gold }}</el-text><strong><i class="ri-money-dollar-circle-fill"></i></strong></p>
                                    </div>
                                    <div class="link"  v-if="quest.user_missions">                                       
                                        <el-button 
                                            v-if="quest.user_missions.status == '01'" 
                                            @click="handleUpdateQuest(quest.user_missions)" 
                                            type="success">Nhận
                                        </el-button>
                                        <span v-else class="icon-success"><i class="ri-check-line"></i></span>
                                    </div>
                                    <div class="link" v-else>                                        
                                        <el-button class="link-quest" @click="handleCreateQuest(quest)" type="danger">Chưa</el-button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </el-scrollbar>
</template>

<script>
    export default {
        name: 'ListQuest',
        components: { },
        
        props:{
            listData:{
                type: Array,
                default:[]
            },
            statusQuest:{
                type:Object,
                default:{}
            },
            user: {
                type:Object,
                default:{}
            }
        },

        data () {            
            return {
                activeIndex: null,
                formData:{
                    'user_id':null,
                    'mission_id':null,
                    'status':null,
                    'type':'create',
                },
                id:[]
            }            
        },        
        
        computed: {

            // listQuest(){
            //     return this.listData ?? [];
            // },

        },

        filters:{
            
        },

        created() {
        }, 
        
        mounted() {
            
        },

        methods: {
            toggleCollapsible(index) {
                if (this.activeIndex === index) {
                    this.activeIndex = null;
                } else {
                    this.activeIndex = index;
                }
            },
            countQuest(item){
                let numberQuest = 1;
                let numberQuestComplete = 0;
                // kiểm tra nhiệm vụ đã hoàn thành
                if(item.user_missions){
                    numberQuestComplete++;
                } 
                if (item.childrents && item.childrents.length) {
                    numberQuest = item.childrents.length
                    for (let index = 0; index < item.childrents.length; index++) {
                        const questComplete = item.childrents[index];
                        if(questComplete.user_missions){
                            numberQuestComplete++;
                        }
                    }
                }       
                return numberQuestComplete+'/'+numberQuest
            },
            // kiểm tra các nhiệm vụ con đã hoàn thành tất cả hay chưa
            isCompleteQuest(item) {
                let numberQuest = 0;
                let numberQuestComplete = 0;
                if (item.childrents && item.childrents.length) {
                    numberQuest = item.childrents.length;
                    for (let index = 0; index < item.childrents.length; index++) {
                        const questComplete = item.childrents[index];
                        if(questComplete.user_missions?.status == '02'){
                            numberQuestComplete++;
                        }
                    }
                }
                if (numberQuest == numberQuestComplete) {
                    return true;
                }else{
                    return false;
                }
            },
            handleCreateQuest(item){
                if (item) {
                    let url = item.link;
                    if (item.target) {
                        this.formData.mission_id = item.id;
                        this.formData.user_id = this.user.id;
                        this.formData.status = this.statusQuest.pending;
                        this.formData.type = 'create';
                        this.$emit('updateQuest', this.formData);
                        window.open(url,'_blank');                        
                    }else{
                        this.$router.push({ path: url })
                    }
                }
            },
            // update status quest
            handleUpdateQuest(item){
                this.id.push(item.id);
                this.formData.status = this.statusQuest.received;
                this.formData.id = this.id;
                this.formData.type = 'update';
                this.$emit('updateQuest', this.formData);
            }
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/variables.scss';
.list-quest {
    .item{     
        margin-bottom:$size10;  
        &:last-child {
            margin-bottom: 0;
        }
        > .header-item{
            padding:$size10;           
            border: 1px solid var(--el-border-color);
            margin-bottom:0; 
        }
        .header-item {          
            .flex {
                justify-content: space-between;
                align-items: center;
                h6{
                    margin: 0;
                    font-size: $size14;
                    font-weight: 600;
                    text-transform: uppercase;
                }
                .content {
                    p{
                        margin: 0;
                        font-size: $size14;
                        line-height: $size20;
                        i {
                            color: $yellow;
                            font-size: $size20;
                            margin-right: $size5;
                            position: relative;
                            top: 2px;
                            left: 3px;
                        }
                    }                    
                }
                .link {
                    ::v-deep(.link-quest) {
                        padding:$size10;
                        display: inline-flex;
                        border-radius: $size5;
                        background-color:$panPink;
                        color: $colorBlack;
                        font-weight: 700;
                        font-size: $size14;
                        &:after{
                            display: none !important;
                            text-decoration: none;
                        }
                    }
                }
                .success{
                    background-color: $green;
                }
            }
            .el-divider--horizontal {
                margin: $size5 0;
            }
        }
        .sub-list {
            list-style: none;
            margin:0;
            padding:0 $size10;
            height: 0;
            visibility: hidden;
            background: $lightGray;
            &.active{
                height: auto;
                //transition: all .3s ease-in-out;
                visibility:visible;
                padding:$size10;
            }
            .header-item {
                margin-bottom: $size10;
            }
        }
    }
}
</style>