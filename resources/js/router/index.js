import { createRouter, createWebHistory } from 'vue-router';
/* Layout */
import Login from "@/views/login";
import Dashboard from "@/views/dashboard";
import Redirect from "@/views/redirect";
import Page404 from "@/views/error-page/404";
import Page401 from "@/views/error-page/401";
import Profile from "@/views/profile/index";
import ListProjects from "@/views/project/index";
import CreateProject from "@/views/project/create";
import EditProject from "@/views/project/edit";
import Layout from "@/layout";
import Medal from '@/backend/views/medal'

import CategoryMedal from '@/backend/views/medal/category'
/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
import handbookRoute from "./modules/hankbook";
import hyperlinkRoute from "./modules/hyperlink";
export const constantRoutes = [
    handbookRoute,
    hyperlinkRoute,  
    {
        path: "/login",
        component: Login,
        hidden: true,
    },
    {
        path: "/404",
        component: Page404,
        hidden: true,
    },
    {
        path: "/401",
        component: Page401,
        hidden: true,
    },
    // {
    //     path: "/project",
    //     component: Layout,
    //     redirect: "/project/list",
    //     name: "Project",
    //     meta: {
    //         title: "Dự Án",
    //         icon: "ri-questionnaire-fill",
    //     },
    //     children: [
    //         {
    //             path: "create",
    //             component: CreateProject,
    //             name: "CreateProject",
    //             meta: { title: "Tạo dự án", icon: "ri-edit-box-fill" },
    //         },
    //         {
    //             path: "edit/:id(\\d+)",
    //             component: EditProject,
    //             name: "EditProject",
    //             meta: {
    //                 title: "Chỉnh sửa dự án",
    //                 noCache: true,
    //                 activeMenu: "/project/list",
    //             },
    //             hidden: true,
    //         },
    //         {
    //             path: "list",
    //             component: ListProjects,
    //             name: "ListProjects",
    //             meta: { title: "Dự Án", icon: "ri-list-indefinite" },
    //         },
    //     ],
    // },
    {
        path: "/profile",
        component: Layout,
        redirect: "/profile/index",
        hidden: true,
        children: [
            {
                path: "index",
                component: Profile,
                name: "Profile",
                meta: {
                    title: "Profile",
                    icon: "ri-user-3-fill",
                    noCache: true,
                },
            },
        ],
    },
];
export const asyncRoutes = [
    { path: "/:pathMatch(.*)*", redirect: "/404", hidden: true },
]
  
// export default router;
const createCustomRouter = () => createRouter({
    //mode: 'history', // require service support
    history: createWebHistory(),
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRoutes
  })
  
  const router = createCustomRouter()
  // Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
  export function resetRouter() {
    const newRouter = createCustomRouter()
    router.matcher = newRouter.matcher // reset router
  }
  
  export default router