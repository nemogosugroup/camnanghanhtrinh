<template>
    <ul class="wrap-menu">
        <li v-for="item in menu" :key="item.path">
            <router-link :to="item.path" :class="`menu ${ active == item.active ? 'active' : '' }`" :style="`background-color:${item.color}`">{{ item.title }}</router-link>
        </li>
    </ul>
</template>
<script>
import handbookRoute from "@/router/modules/hankbook";
export default {
    name: 'HandBookMenu',
    data() {
        return {
        }
    },
    computed: {
        currentPath() {
            return this.$route.path;
        },
        active() {
            const route = this.$route
            const { meta } = route;
            return meta.active
        },
        menu(){
            let list = [];
            if(handbookRoute.children){
                for (const key in handbookRoute.children) {
                    const item = handbookRoute.children[key];
                    if(item.show){
                        let _item = {
                            title: item.meta.title,
                            path: item.path,
                            color: item.meta.color,
                            active: item.meta.active
                        };
                        list.push(_item)
                    }
                }
            }
            return list
        }
    },
};
</script>
<style lang="scss" scoped>
.wrap-menu {
    list-style: none;
    margin: 0;
    padding: 0;
    li{
        &:last-child { .menu{ margin-bottom:  0; }}
    }
    min-width: 180px;
    .menu {
        display: flex;
        padding: 10px 15px;
        text-align: left;
        color: #fff;
        //transition: all ease-in-out .2s;
        width: 160px;
        position: relative;
        margin-bottom: 10px;
        height: 40px;
        line-height: 21px;    
        text-transform: uppercase;
        font-weight: 700;
        &::after{
            content: '';
            border-bottom: 20px solid #fff;
            position: absolute;
            right: 0;
            bottom: 0;
            border-left: 20px solid transparent;
            border-top: 20px solid #fff;
            border-right: 20px solid #fff;
        }
        &:hover{
            width: 180px;
            transition: all ease-in-out .2s;
            padding-left: 20px;
        }
        &.active{
            padding: 15px;
            height: 50px;
            width: 200px;    
            line-height: 23px;        
            font-size: 20px;
            &::after{
                content: '';
                border-width: 25px
            }
        }
    }
}
</style>