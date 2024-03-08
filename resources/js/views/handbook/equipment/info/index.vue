<template>
    <el-col :span="colSpan" style="padding-left: 5em;">
        <el-row>
            <el-col :span="12" style="padding-right: 10px;">
                <el-select
                    v-model="defaultPos"
                    style="width: calc(100%);"
                    @change="changeInventoryPosition"
                    placeholder="Chọn vị trí"
                >
                    <el-option
                        v-for="item in userEquipments.positions"
                        :key="item.value"
                        :label="item.name"
                        :value="item.value">
                    </el-option>
                </el-select>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24" class="equipment_wrapper">
                <Inventory
                    :position="defaultPos"
                ></Inventory>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="12" style="padding-right: 10px;">
                <div class="gold_wrapper">
                    <el-image :src="goldCoinImg"></el-image>
                    <div class="gold">{{ commasThousands(gold) }}</div>
                </div>
            </el-col>
        </el-row>
    </el-col>
</template>
<script>
import {mapGetters} from "vuex";
import goldCoinImg from '@/assets/images/user_equipment/gold-coin.png';
import Inventory from "@/views/handbook/equipment/info/components/inventory/index.vue";

export default {
    name: 'InfoEquipment',
    components: {Inventory},
    props: {
        colSpan: {
            required: true,
            type: Number
        }
    },
    data() {
        return {
            dataUser: [],
            defaultPos: 1,
            gold: 0,
            goldCoinImg: goldCoinImg
        }
    },
    computed: {
        ...mapGetters(['sidebar', 'user', 'userEquipments']),
    },
    watch: {},
    filters: {},
    created() {
        this.dataUser = this.user;
        this.gold = this.dataUser.gold;
        this.emitter.off("show-inventory-by-position");
    },

    mounted() {
        this.emitter.on("show-inventory-by-position", pos => {
            this.defaultPos = pos;
        });
    },

    methods: {
        changeInventoryPosition() {
            this.emitter.emit("change-inventory-position", this.defaultPos);
        },
        commasThousands(number) {
            return number?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }
}

</script>
<style lang="scss" scoped>
.equipment_wrapper {
    height: 500px;
    max-width: 500px;
    margin: 20px 0 30px;
    position: relative;
    right: 10px;
}

.gold_wrapper {
    border: 1px solid;
    border-radius: 10px;
    position: relative;
    height: 40px;
    margin-left: 20px;
    padding-left: 35px;

    .el-image {
        max-width: 50px;
        position: absolute;
        left: -20px;
        bottom: -6px;
    }

    .gold {
        line-height: 40px;
        font-weight: bold;
        text-align: center;
        padding-right: 50px;
    }
}
</style>