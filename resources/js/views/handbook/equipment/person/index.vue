<template>
    <el-col :span="colSpan" style="padding-right: 2.5em;">
        <el-row>
            <el-col :span="24">
                <el-row>
                    <el-col v-show="!isCheckShare" :span="7">
                        <el-avatar shape="square" :size="150" :src="infomation.avatar"></el-avatar>
                    </el-col>
                    <el-col :span="col">
                        <el-form ref="formData" :model="formData" v-bind:rules="rules" label-width="120px">
                            <el-form-item label="Thành tích" prop="title" style="margin-bottom: 10px;">
                                <el-col v-bind:span="24">
                                    <el-input
                                        v-model="valTitle"
                                        placeholder="Thành tích"
                                        disabled
                                    ></el-input>
                                </el-col>
                            </el-form-item>
                            <el-form-item label="Danh hiệu" prop="award" style="margin-bottom: 10px;">
                                <el-col v-bind:span="24">
                                    <el-input
                                        v-model="valAward"
                                        placeholder="Danh hiệu"
                                        disabled
                                    ></el-input>
                                </el-col>
                            </el-form-item>
                            <el-form-item label="ID Nhân sự" style="margin-bottom: 0px;" prop="profile_id">
                                <el-col v-bind:span="24">
                                    <el-input
                                        v-model="user.employee_id"
                                        placeholder="ID Nhân viên"
                                        disabled
                                    ></el-input>
                                </el-col>
                            </el-form-item>
                        </el-form>
                    </el-col>
                </el-row>
            </el-col>
            <el-col :span="24">
                <div class="flex-center" style="margin: 40px 0;">
                    <span class="level"><strong>Level {{ infomation.level }}</strong></span>
                    <div class="progress">
                        <label for="">{{ infomation.experience }} EXP</label>
                        <el-progress :text-inside="false" :show-text="false" :stroke-width="10"
                                     :percentage="infomation.percentage" status="success"></el-progress>
                    </div>
                </div>
                <div id="equippedItems" class="block">
                    <el-image :src="imagePerson"></el-image>
                    <div class="pos">
                        <div
                            v-if="imagePerson"
                            v-for="item in userEquipments.positions"
                            :key="item.value"
                            class="item"
                            :class="item.slug"
                            :style="`${equippedItems[item.value] ? `background-image: url(${equippedItems[item.value].url_image})` : ''}`"
                            @click="showInventoryByPosition(item.value)"
                        >
                            <div v-if="equippedItems[item.value]" class="tooltip">
                                <div class="img_name">
                                    <div class="img"
                                         :style="`background-image: url(${equippedItems[item.value].url_image})`"></div>
                                    <div class="name">{{ equippedItems[item.value].title }}</div>
                                </div>
                                <div class="desc">
                                    <b>Mô tả:</b>
                                    <p>{{ equippedItems[item.value].description_in_game }}</p>
                                </div>
                                <div class="btn_equip">
                                    <a
                                        class="remove"
                                        @click="removeEquippedItem(equippedItems[item.value].ue_id)"
                                    >
                                        Gỡ bỏ
                                    </a>
                                </div>
                            </div>
                            <div v-else>{{ item.name }}</div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
    </el-col>
</template>
<script>
import {mapGetters} from 'vuex';
import RepositoryFactory from '@/utils/RepositoryFactory';
import imagePerson from '@/assets/images/nhanvat.png';
import Socials from '@/components/Socials/index.vue';
import Helpers from "@/helper";
const UserRepository = RepositoryFactory.get('user');
const UserEquipmentRepo = RepositoryFactory.get('userEquipment');

export default {
    name: 'InfoEquipmentPerson',
    components: {Socials},
    props: {
        defaultAvatar: {
            type: String
        },
        colSpan: {
            required: true,
            type: Number
        }
    },
    data() {
        return {
            valTitle: null,
            valAward: null,
            formData: {
                title: '',
                award: '',
                profile_id: null,
            },
            rules: {},
            imagePerson: imagePerson,
            isCheckShare: true,
            isLoadingSearch: false,
            dataPerson: [],
            showAvatar: this.defaultAvatar,
            col: 24,
            listEmployee: [],
            infomation: {
                experience: 0,
                level: 1,
                gold: 0,
                percentage: 0,
                fullname: '',
                avatar: '',
            },
            optionProps: {
                label: 'fullname',
                value: 'profile_id',
            },
            equippedItems: []
        }
    },
    computed: {
        ...mapGetters(["user", "userEquipments"]),
        infoUser() {
            return this.user;
        },
    },
    filters: {},
    created() {
        this.infomation.experience = this.infoUser.experience;
        this.infomation.level = this.infoUser.level ? this.infoUser.level : 1;
        this.infomation.gold = this.infoUser.gold;
        this.infomation.fullname = this.infoUser.fullname;
        this.infomation.percentage = this.percent(this.infoUser);
        if (this.infoUser.achievement_user) {
            this.valTitle = this.infoUser.achievement_user.category.title;
            this.valAward = this.infoUser.achievement_user.medal.title;
        }
        this.fetchEquippedItems();
        this.emitter.off("use-equipment");
        this.emitter.off("remove-equipped-item");
    },

    mounted() {
        this.emitter.on("use-equipment", () => {
            this.fetchEquippedItems();
        });
        this.emitter.on("remove-equipped-item", ueId => {
            this.removeEquippedItem(ueId, true);
        });
    },

    methods: {
        async removeEquippedItem(ueId, isInventory = false) {
            const {data} = await UserEquipmentRepo.removeEquippedItem(ueId);
            if (data.success) {
                await this.fetchEquippedItems();
                if (isInventory) {
                    this.emitter.emit("is-after-remove", true);
                }
            }
        },
        showInventoryByPosition(position) {
            this.emitter.emit("show-inventory-by-position", position);
        },
        async fetchEquippedItems() {
            const {data} = await UserEquipmentRepo.listEquippedItems();
            if (data.success) {
                this.equippedItems = data.data;
            }
        },
        // lấy phần trăm của điểm kinh nghiệm hiện tại và level
        percent(data){
            return Helpers.percentExp(data)
        }
    }
}

</script>
<style lang="scss" scoped>
.title-h2 {
    margin: 20px 0;
    padding: 5px;
    border: 1px solid #EBEEF5;
    text-align: center;
}

.flex-center {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: center;
    align-content: stretch;
    align-items: flex-end;
}

.progress {
    label {
        font-size: 12px;
        text-align: left;
        display: block;
        color: #625f5f;
        margin-bottom: 5px;
    }

    width: 200px;
}

.level {
    font-size: 12px;
    text-align: left;
    display: block;
    color: #625f5f;
    margin-right: 5px;
}

.block {
    margin: 20px 0 0;
    text-align: center;

    .el-image {
        top: 50px;
        transform: scale(1.2);
    }
}

::v-deep .el-form-item__label {
    font-weight: bold;
}

::v-deep .el-select-dropdown__option-item {
    font-size: 14px !important;
}

#equippedItems {
    position: relative;

    .el-image {
        min-height: 300px;
    }

    .pos {
        position: absolute;
        top: 0;
        right: 40px;
        width: 100%;
        height: 100%;

        .item {
            position: absolute;
            background-color: #d0cece;
            display: inline-block;
            width: 80px;
            height: 80px;
            text-align: center;
            line-height: 80px;
            border-radius: 50%;
            box-shadow: 1px 2px 5px #000;
            color: #00000052;
            font-weight: bold;
            font-size: 14px;
            bottom: 0;
            cursor: context-menu;
            z-index: 9;
            transition: all .3s ease;

            &.item {
                cursor: pointer;
                border-color: #02305e;
                background-size: cover;

                &:hover {
                    z-index: 10;
                    box-shadow: 0 0 5px #000;

                    .tooltip {
                        left: 50px;
                        top: 50px;
                        opacity: 10;
                        width: 225px;
                        height: 175px;
                        border: 1px solid #02305e;
                        box-shadow: 0 0 5px #000;
                        cursor: context-menu;

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

                &.giay {
                    &:hover {
                        .tooltip {
                            left: 45px;
                            top: 0;
                        }
                    }
                }

                .tooltip {
                    text-align: left;
                    color: #000;
                    position: absolute;
                    opacity: 0;
                    width: 0;
                    height: 0;
                    background: #fff;
                    left: 25px;
                    top: 60px;
                    line-height: 14px;
                    font-size: 14px;
                    padding: 5px;
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

            &.mu {
                top: -10px;
            }

            &.huy_hieu {
                top: -10px;
                right: 55px;
            }

            &.phi_phong {
                top: 150px;
                left: 135px;
            }

            &.y_phuc {
                top: 135px;
                left: 50%;
            }

            &.vu_khi {
                top: 150px;
                right: 55px;
            }

            &.giay {
                bottom: -75px;
            }
        }
    }
}
</style>