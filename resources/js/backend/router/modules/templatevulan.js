import Layout from "@/layout";
import VuLan from "@/backend/views/vulan";
import Slider1 from "@/backend/views/vulan/templates/Slider1";
// import Slider1 from "@/backend/views/vulan/templates/Slider1";
// import VuLan from "@/backend/views/vulan";

const templateVulanRouter = {
    path: "/admin/vulan/",
    hidden: true,
    redirect: "/admin/vulan/template-1",
    children: [
        {
            path: "template-1",
            component: Slider1,
            name: "Template1",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
                affix: true,
            },
            hidden: true,
        },
        {
            path: "template-2",
            component: Slider1,
            name: "Template2",
            meta: {
                title: "Vu lan",
                icon: "ri-hand-heart-fill",
                affix: true,
            },
            hidden: true,
        },
    ],
};

export default templateVulanRouter;
