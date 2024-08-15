<template>
    <div class="navbar">
        <Logo />

        <!-- <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />-->

        <div class="right-menu">
            <el-dropdown v-if="checkLogin" class="avatar-container right-menu-item hover-effect" trigger="click">
                <div class="avatar-wrapper">
                    <img :src="avatar + '?imageView2/1/w/80/h/80'" class="user-avatar" />
                    <el-icon>
                        <CaretBottom />
                    </el-icon>
                </div>
                <template #dropdown>
                    <el-dropdown-menu>
                        <router-link to="/profile/index">
                            <el-dropdown-item>Profile</el-dropdown-item>
                        </router-link>
                        <router-link to="/">
                            <el-dropdown-item>Dashboard</el-dropdown-item>
                        </router-link>
                        <el-dropdown-item divided @click="logout">
                            <span style="display: block">Log Out</span>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
            <router-link v-else to="/login">
                <span style="color: #fff; font-weight: 700;">Đăng nhập</span>
            </router-link>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Breadcrumb from "@/components/Breadcrumb";
import Hamburger from "@/components/Hamburger";
import Logo from './Logo'
// import ErrorLog from "@/components/ErrorLog";
// import Screenfull from "@/components/Screenfull";
// import SizeSelect from "@/components/SizeSelect";
// import Search from "@/components/HeaderSearch";
import avatarDefault from "@/assets/images/logo/avatar.gif";
export default {
    components: {
        Breadcrumb,
        Hamburger,
        Logo
    },
    data() {
        return {
            checkLogin: false
        }
    },
    computed: {
        ...mapGetters(["sidebar", "device", "user"]),
        avatar() {
            let avatar = this.user.avatar;
            return avatar ? avatar : avatarDefault;
        },
        variables() {
            return variables;
        },
    },
    mounted() {
        this.checkLogin = Object.keys(this.user).length > 0 ? true : false;
    },
    methods: {
        toggleSideBar() {
            this.$store.dispatch("app/toggleSideBar");
        },
        async logout() {
            await this.$store.dispatch("user/logout");
            this.$router.push(`/login`);
        },
    },
};
</script>

<style lang="scss" scoped>
.navbar {
    height: 50px;
    overflow: hidden;
    position: relative;
    //background: #fff;
    box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
    background: #2b2f3a;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-between;
    align-items: center;
    align-content: stretch;
    padding-left: 15px;
    padding-right: 15px;

    .hamburger-container {
        line-height: 46px;
        height: 100%;
        float: left;
        cursor: pointer;
        transition: background 0.3s;
        -webkit-tap-highlight-color: transparent;

        &:hover {
            background: rgba(0, 0, 0, 0.025);
        }
    }

    .breadcrumb-container {
        float: left;
    }

    .errLog-container {
        display: inline-block;
        vertical-align: top;
    }

    .right-menu {
        float: right;
        height: 100%;
        line-height: 50px;

        &:focus {
            outline: none;
        }

        .right-menu-item {
            display: inline-block;
            padding: 0 8px;
            height: 100%;
            font-size: 18px;
            color: #5a5e66;
            vertical-align: text-bottom;

            &.hover-effect {
                cursor: pointer;
                transition: background 0.3s;

                &:hover {
                    background: rgba(0, 0, 0, 0.025);
                }
            }
        }

        .avatar-container {
            margin-right: 30px;

            .avatar-wrapper {
                margin-top: 5px;
                position: relative;

                .user-avatar {
                    cursor: pointer;
                    width: 40px;
                    height: 40px;
                    border-radius: 10px;
                }

                .el-icon-caret-bottom {
                    cursor: pointer;
                    position: absolute;
                    right: -20px;
                    top: 25px;
                    font-size: 12px;
                }
            }
        }
    }
}
</style>
