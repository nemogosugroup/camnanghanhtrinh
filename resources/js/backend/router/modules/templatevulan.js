import Layout from "@/layout";
import VuLan from "@/backend/views/vulan";
import Slider1 from "@/backend/views/vulan/templates/Slider1";

const templateVulanRouter = {
    path: "/vulan/template/",
    redirect: "/vulan/template/template-1",
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
        },
    ],
};

export default templateVulanRouter;
