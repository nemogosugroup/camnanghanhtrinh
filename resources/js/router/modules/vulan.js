import VuLan from "@/views/vulan";
import Detail from "@/views/vulan/components/Detail.vue";
import Edit from "@/backend/views/vulan/edit/Slider1.vue";
const vuLanRouter = {
    path: "/vulan/",
    name: "VuLanView",
    meta: {
        title: "Vu lan",
        icon: "ri-hand-heart-fill",
    },
    redirect: "/vulan/index",
    children: [
        {
            path: "index",
            component: VuLan,
            name: "VuLanIndex",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
            },
        },
        {
            path: "detail/:id(\\d+)",
            component: Detail,
            name: "VuLanDetail",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
            },
            hidden: true,
        },
        {
            path: "edit/:id(\\d+)",
            component: Edit,
            name: "VuLanEdit",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
            },
            hidden: true,
        },
    ],
};

export default vuLanRouter;
