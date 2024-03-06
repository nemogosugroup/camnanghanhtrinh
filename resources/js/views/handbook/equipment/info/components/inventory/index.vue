<template>
    <el-row>
        <div class="inventory">
            <div class="col" v-for="i in rows" :key="i">
                <div
                    class="box"
                    :class="{
                        item : inventoryItems[setSlotNumber(i, j) - 1],
                        equipped : inventoryItems[setSlotNumber(i, j) - 1]?.status === 1
                    }"
                    v-for="j in cols"
                    :key="j"
                    :style="`${inventoryItems[setSlotNumber(i, j) - 1] ? `background-image: url(${inventoryItems[setSlotNumber(i, j) - 1].equipment.url_image})` : ''}`"
                >
                    <div v-if="inventoryItems[setSlotNumber(i, j) - 1]" class="tooltip">
                        <div class="img_name">
                            <div class="img"
                                 :style="`background-image: url(${inventoryItems[setSlotNumber(i, j) - 1].equipment.url_image})`"></div>
                            <div class="name">{{ inventoryItems[setSlotNumber(i, j) - 1].equipment.title }}</div>
                        </div>
                        <div class="desc">
                            <b>Mô tả:</b>
                            <p>{{ inventoryItems[setSlotNumber(i, j) - 1].equipment.description_in_game }}</p>
                        </div>
                        <div class="btn_equip">
                            <a
                                v-if="inventoryItems[setSlotNumber(i, j) - 1].status === 0"
                                class="equip"
                                @click="useEquipment(inventoryItems[setSlotNumber(i, j) - 1].id, inventoryItems[setSlotNumber(i, j) - 1].position)"
                            >
                                Trang bị
                            </a>
                            <a
                                v-else
                                class="remove"
                                @click="removeEquippedItem(inventoryItems[setSlotNumber(i, j) - 1].id)"
                            >
                                Gỡ bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </el-row>
</template>
<script>
import RepositoryFactory from "@/utils/RepositoryFactory";

const UserEquipmentRepo = RepositoryFactory.get('userEquipment');

export default {
    name: 'Inventory',
    components: {},
    props: {
        position: {
            type: Number,
            default: 1
        }
    },
    data() {
        return {
            cols: 5,
            rows: 10,
            inventoryItems: []
        }
    },
    computed: {},
    filters: {},

    created() {
        this.fetch();
        this.emitter.off("change-inventory-position");
        this.emitter.off("show-inventory-by-position");
        this.emitter.off("is-after-remove");
    },

    mounted() {
        this.emitter.on("change-inventory-position", pos => {
            this.fetch(pos);
        });
        this.emitter.on("show-inventory-by-position", pos => {
            this.fetch(pos);
        });
        this.emitter.on("is-after-remove", () => {
            this.fetch();
        });
    },
    methods: {
        async useEquipment(ueId, pos) {
            const {data} = await UserEquipmentRepo.useEquipment(ueId, pos);
            if (data.success) {
                await this.fetch();
                this.emitter.emit("use-equipment", ueId);
            }
        },
        removeEquippedItem(ueId) {
            this.emitter.emit("remove-equipped-item", ueId);
        },
        async fetch(pos = null) {
            const {data} = await UserEquipmentRepo.listInventoryItems({
                position: pos ?? this.position
            });
            if (data.success) {
                this.inventoryItems = data.data;
                document.querySelector('.inventory').scrollTop = 0;
            }
        },
        setSlotNumber(i, j) {
            switch (i) {
                case (1):
                    return j;
                case (2):
                    return 3 + i + j;
                case (3):
                    return 7 + i + j;
                case (4):
                    return 11 + i + j;
                case (5):
                    return 15 + i + j;
                case (6):
                    return 19 + i + j;
                case (7):
                    return 23 + i + j;
                case (8):
                    return 27 + i + j;
                case (9):
                    return 31 + i + j;
                case (10):
                    return 35 + i + j;
                default:
                    return i + j;
            }
        }
    }
}

</script>
<style lang="scss" scoped>
.inventory {
    width: 100%;
    max-height: 500px;
    overflow-y: scroll;

    &::-webkit-scrollbar {
        width: 5px;
    }

    &::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 15px;
    }

    &::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 15px;
    }

    &::-webkit-scrollbar-thumb:hover {
        background: #555;
        border-radius: 15px;
    }

    .col {
        height: 100px;

        .box {
            position: relative;
            z-index: 9;
            height: 80%;
            width: 16%;
            margin: 2%;
            text-align: center;
            line-height: 80px;
            background-color: #409EFFFF;
            border-color: transparent;
            border-style: double;
            display: inline-block;
            border-radius: 3px;
            transition: all .3s ease;

            &.item {
                cursor: pointer;
                border-color: #02305e;
                background-size: cover;

                &:hover {
                    z-index: 10;
                    box-shadow: 0 0 5px #000;

                    .tooltip {
                        z-index: 1;
                        left: 50px;
                        top: 50px;
                        opacity: 10;
                        width: 225px;
                        height: 175px;
                        border: 1px solid #02305e;
                        box-shadow: 0 0 5px #000;
                        cursor: context-menu;
                        padding: 5px;

                        .desc {
                            left: 0;
                        }

                        .btn_equip {
                            a {
                                right: 5px;
                            }
                        }
                    }
                }

                .tooltip {
                    z-index: 0;
                    padding: 0;
                    text-align: left;
                    position: absolute;
                    opacity: 0;
                    width: 0;
                    height: 0;
                    background: #fff;
                    left: 25px;
                    top: 60px;
                    line-height: 14px;
                    font-size: 14px;
                    border-radius: 3px;
                    overflow: hidden;
                    transition: all .3s ease;

                    .img_name {
                        position: relative;

                        .img {
                            display: inline-block;
                            width: 50px;
                            height: 50px;
                            background-size: cover;
                            box-shadow: 0 0 5px #000;
                            border-radius: 3px;
                        }

                        .name {
                            display: inline-block;
                            font-size: 16px;
                            font-weight: bold;
                            margin-left: 5px;
                            text-transform: uppercase;
                            width: 150px;
                            line-height: 16px;
                            position: absolute;
                            top: 50%;
                            left: 60%;
                            transform: translate(-50%, -50%);
                        }
                    }

                    .desc {
                        position: relative;
                        left: -500px;
                        transition: left .3s ease;

                        b {
                            position: relative;
                            top: 7px;
                        }

                        p {
                            margin: 10px 0 5px;
                            line-height: 16px;
                        }
                    }

                    .btn_equip {
                        a {
                            color: rgba(0, 0, 0, 0.3215686275);
                            position: absolute;
                            bottom: 5px;
                            padding: 5px;
                            border-radius: 3px;
                            right: 500px;
                            font-weight: bold;
                            transition: all .3s ease;

                            &.equip {
                                background-color: #409EFFFF;

                                &:hover {
                                    background-color: #9dc5ff;
                                }
                            }

                            &.remove {
                                background-color: #f97c00;

                                &:hover {
                                    background-color: #fdc285;
                                }
                            }
                        }
                    }
                }
            }

            &.equipped {
                &::before,
                &::after {
                    content: "";
                    border-radius: 3px;
                    z-index: -1;
                    margin: -5%;
                    box-shadow: inset 0 0 10px 0 #259f00;
                    animation: clipMe 2.8s ease infinite;
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    right: 0;
                }

                &::before {
                    animation-delay: -1.4s;
                }

                //&::after {
                //    content: "";
                //    position: absolute;
                //    z-index: -2;
                //    left: 0;
                //    top: 0;
                //    width: 100%;
                //    height: 100%;
                //    background: linear-gradient(
                //            62deg,
                //            rgba(238, 176, 82, 0.25),
                //            rgba(231, 203, 60, 0.25),
                //            rgba(44, 181, 224, 0.25),
                //            rgba(119, 134, 215, 0.25),
                //            rgba(0, 0, 0, 0),
                //            rgba(0, 0, 0, 0),
                //            rgba(0, 0, 0, 0)
                //    );
                //    animation: gradient-4d9565a4 1.7s ease infinite;
                //    background-size: 400% 400%;
                //}
            }
        }
    }
}

@keyframes clipMe {
    0%, 100% {
        clip: rect(0px, 220px, 2px, 0px);
    }
    25% {
        clip: rect(0px, 2px, 220px, 0px);
    }
    50% {
        clip: rect(218px, 220px, 220px, 0px);
    }
    75% {
        clip: rect(0px, 220px, 220px, 218px);
    }
}

@keyframes gradient {
    0% {
        background-position: 0 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>