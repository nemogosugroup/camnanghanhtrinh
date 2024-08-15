// import Layout from "@/layout";
// import VuLan from "@/backend/views/vulan";
// import detailVuLan from "@/backend/views/vulan/detail";

// const vuLanRouter = {
//     path: "/vulan/",
//     redirect: "",
//     children: [
//         {
//             path: "",
//             component: VuLan,
//             name: "VuLan",
//             meta: {
//                 title: "Vu lan",
//                 icon: "ri-hand-heart-fill",
//                 affix: true,
//             },
//         },
//     ],
// };

// export default vuLanRouter;
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
    ],
};

export default vuLanRouter;
