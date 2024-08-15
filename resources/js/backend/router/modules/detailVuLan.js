import Layout from "@/layout";
import VuLan from "@/backend/views/vulan";
import detailVuLan from "@/backend/views/vulan/detail";

const vuLanRouter = {
    path: "/vulan/",
    hidden: true,
    redirect: "/vulan/:id(\\d+)",
    children: [
        {
            path: "/vulan/:id(\\d+)",
            component: detailVuLan,
            name: "detailVuLan",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
                affix: true,
            },
        },
    ],
};

export default vuLanRouter;
