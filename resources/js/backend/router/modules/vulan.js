import Layout from "@/layout";
import template2VuLan from "@/backend/views/vulan/slider2";
import VuLan from "@/views/vulan";
const vuLanRouter = {
    path: "/vulan/",
    redirect: "",
    children: [
        {
            path: "",
            component: VuLan,
            name: "VuLan",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
                affix: true,
            },
        },
        {
            path: "/template-2",
            component: template2VuLan,
            name: "VuLan2",
            meta: {
                title: "Vu lan 2",
                icon: "ri-hand-heart-fill",
                affix: true,
            },
        },
    ],
};

export default vuLanRouter;
