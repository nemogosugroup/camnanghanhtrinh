<template>
    <div class="wrap">
        <div class="header flex-center">
            <h3 class="uppercase">Tiến độ hoàn thành</h3>
            <el-text size="large" class="count-radius mb10">{{ countQuest() }}</el-text>
            <div class="progress flex-center">                
                <el-progress
                v-for="(progress, index) in listProgress" :key="index" 
                :percentage="progress.percent" 
                :stroke-width="20" 
                :class="progressColor(progress.status)"
                :text-inside="true">
                <el-button text class="hidden"></el-button>
            </el-progress>
            </div>
        </div>
        <el-divider />
        <div class="">
            <div class="flex-center flex-align-center wp-gold mb20">
                <span class="gold">
                    <strong><i class="ri-money-dollar-circle-fill"></i></strong> 
                    <span>{{ commasThousands(gold) }}</span>
                </span>
            </div>
            <div class="flex-center flex-align-center wp-gold mb20">
                <span class="gold exp">
                    <strong><i class="ri-printer-line"></i></strong> 
                    <span>{{ commasThousands(experience) }}</span>
                </span>
            </div>
            <div class="flex-center flex-align-center">
                <el-button type="success" @click="handleUpdateQuest()" :loading="isLoading">Nhận thưởng</el-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'UserChapter',
    components: { },
    props: {
        listData: {
            type: Array,
            default: []
        },
        statusQuest:{
            type:Object,
            default:{}
        },
        // isLoading:{
        //     type:Boolean,
        //     default:false
        // },
    },
    data() {
        return {
            formData:{
                id: [],
                type: "update",
                status: this.statusQuest.received
            },
            experience:0,
            totalExpComplete:0,
            gold:0,
            listProgress:[],
            isLoading:false,
        };
    },   

    filters: {},

    created() {
        
    },

    methods: {
        countQuest() {
            let numberQuestComplete = 0;
            let count = 0;
            let gold = 0;
            let experience = 0;
            let totalExp = 0;
            let expComplete = 0;
            for (const key in this.listData) {
                const item = this.listData[key];
                if (item.user_missions) {
                    numberQuestComplete++;
                    // kiểm tra các nhiệm vụ đã hoàn thành nhưng chưa nhận phần thưởng
                    if(item.user_missions.status == '01'){
                        gold = gold + item.gold;
                        experience = experience + item.experience;
                        this.formData.id.push(item.user_missions.id);// lấy danh sách id của nhiệm vụ đã hoàn thành nhưng chưa nhận thưởng
                    }else{
                        expComplete = expComplete + item.experience;
                    }
                }
                if (item.childrents.length == 0) {
                    count++;
                    // tính tổng điểm kinh nghiệm của chương
                    totalExp = totalExp + item.experience;
                }
                if (item.childrents.length > 0) {
                    count = count + item.childrents.length;
                    if (item.childrents && item.childrents.length) {
                        for (let index = 0; index < item.childrents.length; index++) {
                            const questComplete = item.childrents[index];
                            if(questComplete.user_missions){
                                numberQuestComplete++;
                                // tính tổng điểm kinh nghiệm của chương
                                totalExp = totalExp + questComplete.experience;
                                // kiểm tra các nhiệm vụ đã hoàn thành nhưng chưa nhận phần thưởng
                                if(questComplete.user_missions.status == '01'){
                                    gold = gold + questComplete.gold;
                                    experience = experience + questComplete.experience; 
                                    this.formData.id.push(questComplete.user_missions.id);// lấy danh sách id của nhiệm vụ đã hoàn thành nhưng chưa nhận thưởng                                   
                                }else{
                                    expComplete = expComplete + questComplete.experience;
                                }
                            }
                        }
                    }  
                }                
            }
            this.gold = gold;
            this.experience = experience;// tổng điểm kinh nghiệm hoàn thành chưa nhận
            this.totalExpComplete = expComplete + experience;// tổng điểm kinh nghiệm hoàn thành đã nhận
            this.progressQuest(totalExp);
            return numberQuestComplete + '/' + count;
        },
        // tính phần trăm nhiệm vụ đã hoàn thành
        progressQuest(totalExp){
            let exp = Math.round(totalExp/5);                    
            let total = this.totalExpComplete;                        
            for (let index = 0; index < 5; index++) {
                let progress = {
                    'exp' : exp,
                    'percent' : 0,
                    'status': 'none'
                }
                if (total > 0) {
                    let percent = Math.round( (total/exp) * 100 );                    
                    if (percent >= 100) {
                        progress['percent'] = 100;
                        progress['status'] = 'success';
                    }
                    if( percent >= 0 && percent < 100){
                        progress['percent'] = percent;
                        progress['status'] = 'default';
                    }
                    this.listProgress[index] = progress;
                    total = total - exp;
                }else{
                    this.listProgress[index] = progress;
                }
            }
        },
        // show progressColor
        progressColor: function(val){
            if (val == 'success') {
                return 'el-bg-success'
            } else if (val == 'default'){
                return 'el-bg-default'
            } else {
                return 'el-bg-none'
            }
        },
        // nhận phần thưởng
        handleUpdateQuest: function(){
            this.isLoading = true;
            this.$emit('updateQuest', this.formData);            
            setTimeout(() => {
                this.isLoading = false;
                this.formData = {
                    id: [],
                    type: "update",
                    status: this.statusQuest.received
                }
            }, 1000);
        },
        commasThousands(number) {
            return number?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }
};
</script>
<style lang="scss" scoped>
@import '@style/variables.scss';
.wrap {
    height: 500px;
    .header {
        &.flex-center {
            flex-direction: column;
        }
        .progress {
            width: 80%;
            .el-progress {
                width: calc(20% - 5px);
                margin: 0 2.5px;
                &.el-bg-success {
                    :deep(.el-progress-bar__inner) {
                        background-image: linear-gradient(to right, #548235, #548235, #548235) !important;
                    }
                }
                &.el-bg-default {
                    :deep(.el-progress-bar__inner) {
                        //background-image: linear-gradient(to right, #548235, #C5E0B4, #fff) !important;
                        background-color: transparent !important;
                    }
                    :deep(.el-progress-bar__outer){
                        background-image: linear-gradient(to right, #548235, #C5E0B4, #fff) !important;
                    }
                }
                &.el-bg-none {
                    :deep(.el-progress-bar__inner) {
                        background-image: linear-gradient(to right, #fff, #fff, #fff) !important;
                    }
                }
                :deep(.el-progress-bar__outer){
                    border-radius: 0;
                }
                :deep(.el-progress-bar__inner){
                    border-radius: 0;
                }
                :deep(.el-progress-bar) {
                    box-shadow: 0px 0px 7px 0px rgba(0,0,0,.8);
                }
            }
        }
    }
    h3 {
        margin: $size10 0;
    }
    
}
</style>
