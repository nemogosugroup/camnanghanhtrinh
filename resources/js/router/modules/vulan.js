import VuLan from "@/views/vulan";
import Detail from "@/views/vulan/components/Detail.vue";
const vuLanRouter = {
    path: "/vulan/",
    name: "VuLan",
    meta: {
        title: "Vu lan",
        icon: "ri-hand-heart-fill",
    },
    redirect: "/vulan/index",
    children: [
        {
            path: "index",
            component: VuLan,
            name: "VuLan",
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
    ],
};

export default vuLanRouter;
