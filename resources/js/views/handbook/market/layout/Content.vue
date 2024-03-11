<template>
    <el-scrollbar height="600px">
        <el-main>
            <el-row v-if="total > 0" class="row-bg" :gutter="20">
                <el-col :span="6" v-for="(item, index) in list" :key="index">
                    <div class="grid-content ep-bg-purple">
                        <!-- :body-style="{ padding: '0px' }" -->
                        <el-card @click="handleShowDialog(item)" v-if="type == 'course'">
                            <el-image style="width: 100%; height: 150px" :src="item.url_image" fit="cover" />
                            <div class="content">
                                <el-text truncated tag="b" class="w-150px mb-1">{{ item.title }}</el-text>
                                <div class="bottom">
                                    <el-button text class="button">
                                        <strong><i class="ri-money-dollar-circle-fill"></i></strong>
                                        <span>{{ commasThousands(item.gold) }}</span>
                                    </el-button>
                                </div>
                            </div>
                        </el-card>
                        <el-card @click="handleShowDialog(item)" v-else class="item-equipment">
                            <!-- <div class="flex">
                                <el-image style="width: 70px; height: 50px" :src="item.url_image" fit="cover" />
                                <el-text tag="b" class="ms10">{{ item.title }}</el-text>
                            </div>
                            <div class="content">
                                <el-text line-clamp="2" class="mb-1 desc">
                                    {{ item.description_in_game }}
                                </el-text>
                                <div class="bottom pdt10 flex-center">
                                    <el-button text class="button">
                                        <strong><i class="ri-money-dollar-circle-fill"></i></strong>
                                        <span>{{ commasThousands(item.gold) }}</span>
                                    </el-button>
                                    <el-button :type="`${handleTextBuy(item.id) ? 'success' : ''}`" class="button">
                                        {{ handleTextBuy(item.id) ? textOwn : textBuy }}
                                    </el-button>
                                </div>
                            </div> -->
                            <div class="feature-img">
                                <el-image style="width: 100%; height: 200px" :src="item.url_image" fit="cover"/>                                
                                <span> <i class="ri-money-dollar-circle-fill"></i> {{ commasThousands(item.gold) }}</span>
                            </div>
                            <div class="content">
                                <el-text tag="b" class="ms10">{{ item.title }}</el-text>
                                <el-text line-clamp="2" class="mb-1 desc">
                                    {{ item.description_in_game }}
                                </el-text>
                                <div class="pdt10 flex-center">
                                    <el-button :type="`${handleTextBuy(item.id) ? 'success' : ''}`" class="button">
                                        {{ handleTextBuy(item.id) ? textOwn : textBuy }}
                                    </el-button>
                                </div>
                            </div>
                        </el-card>
                    </div>
                </el-col>
            </el-row>
            <!-- <el-row v-else>
                <h2>{{ postFound }}</h2>
            </el-row> -->
        </el-main>
    </el-scrollbar>
</template>
<script>
    import Helper from '@/helper';
    export default {
        name: 'ContentMarket',
        components: {},
        props: {
            list: {
                type: Array,
                default: ''
            },
            total: {
                type: Number,
                default: 0
            },
            courses: {
                type: Array,
                default: []
            },
            equipments: {
                type: Array,
                default: []
            },            
        },
        data () {            
            return {
                postFound: 'Không có dữ liệu',
                textBuy: 'Mua',
                textOwn: 'Sở hữu',
            }            
        },        
        computed: {
            type() {
                const route = this.$route
                const { meta } = route;
                return meta.type
            },
        },
        created() {
        },
        
        methods: {
            handleTextBuy(id){
                return  this.equipments.includes(id) ? true : false;
            },
            handleShowDialog(item){
                let isShowButton = true;
                let data = {
                    'isShow': true,
                    'item': item,
                    'isShowButton': isShowButton,
                    'type': this.type
                }
                if(this.type == Helper.typeMarket.course){                    
                    if(this.courses.includes(item.id)){
                        data.isShowButton = false;
                    }
                }

                if(this.type == Helper.typeMarket.equipment){
                    if(this.equipments.includes(item.id)){
                        data.isShowButton = false;
                    }
                }
                //this.courses.push(item.id);
                //this.$store.commit('user/SET_COURSES', this.courses);
                this.$emit('show', data);
            },
            commasThousands(number) {
                return number?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }
</script>
<style lang="scss">
@import '@style/variables.scss';
.grid-content{
    .el-card {
        transition: all .3s ease-in-out;
        -webkit-transition: all .3s ease-in-out;
        -moz-transition: all .3s ease-in-out;
        position: relative;
        top:0;
        cursor: pointer;
        margin-bottom: $size20;
        .content {
            padding-top:0;
            text-align: center;
            h6 {
                font-size: $size16;
                margin: 0;
            }
            .bottom {
                border-top: 1px solid $lightGray; 
                .el-button {
                    // background-color: transparent !important;
                    font-size: $size16;
                    &.is-text {
                        padding: 0;
                        background: transparent !important;
                    }
                    strong {
                        color: $yellow;
                        margin-right: $size5;
                        font-size: $size24;
                    }
                }               
                &.pdt10 {
                    .el-button {
                        font-size: $size14;
                        strong {
                            font-size: $size20;
                        }
                    }
                }
            }
            
        }
        &:hover{
            top:-5px;
            &.item-equipment {
                top:0px;
                .content {
                    transform: translateX(0%);
                    -moz-transform: translateX(0%);
                    -webkit-transform: translateX(0%);
                    transition: all .3s ease-in-out;
                    -moz-transition: all .3s ease-in-out;
                    -webkit-transition: all .3s ease-in-out;
                }
                .feature-img {
                    img {
                        transform: scale(1.2);
                        -moz-transform: scale(1.2);
                        -webkit-transform: scale(1.2);
                        transition: all .3s ease-in-out;
                        -moz-transition: all .3s ease-in-out;
                        -webkit-transition: all .3s ease-in-out;
                    }
                }
                
            }
        }
        &.item-equipment {
            .el-text{
                width: 100%;
                color: $bgWhite;  
            }
            .desc{
                width: 100%;
                color: $bgWhite;
            }
            .el-card__body {
                padding:0
            }
            .feature-img {
                position: relative;
                display: flex;
                &::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: $lightBlack;
                    top: 0;
                }
                img {
                    transform: scale(1);
                    -moz-transform: scale(1);
                    -webkit-transform: scale(1);
                    transition: all .3s ease-in-out;
                    -moz-transition: all .3s ease-in-out;
                    -webkit-transition: all .3s ease-in-out;
                }
                span{
                    position: absolute;
                    padding: 3px;
                    background: $yellow;
                    top: $size10;
                    right: 0;
                    position: absolute;
                    font-size: $size12;
                    color: #fff;
                    display: flex;
                    align-items: center;
                    height: 24px;
                    z-index: 1;
                    i {
                        font-size: 18px;
                        margin-right: 5px;
                    }
                    &::after {
                        content: '';
                        position: absolute;
                        border-top: 12px solid transparent;
                        border-bottom: 12px solid transparent;
                        border-right: 12px solid #FEC171;
                        border-left: 12px solid transparent;
                        left: -24px;
                        top: 0;
                    }                    
                }
            }
            .content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, .7);
                z-index: 2;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                color: #fff;
                transform: translateX(-100%);
                -moz-transform: translateX(-100%);
                -webkit-transform: translateX(-100%);
                transition: all .3s ease-in-out;
                -moz-transition: all .3s ease-in-out;
                -webkit-transition: all .3s ease-in-out;
            }
        }
    }
}
</style>