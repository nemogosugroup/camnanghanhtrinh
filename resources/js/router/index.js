import { createRouter, createWebHistory } from "vue-router";
/* Layout */
import Login from "@/views/login";
import LoginGoogle from "@/views/login/login-google.vue";
import Terms from "@/views/terms-privacy/terms.vue";
import Privacy from "@/views/terms-privacy/privacy.vue";
import Dashboard from "@/views/dashboard";
import Redirect from "@/views/redirect";
import Page404 from "@/views/error-page/404";
import Page401 from "@/views/error-page/401";
import Profile from "@/views/profile/index";
import ListProjects from "@/views/project/index";
import CreateProject from "@/views/project/create";
import EditProject from "@/views/project/edit";
import Layout from "@/layout";
import Medal from "@/backend/views/medal";
import { getAccessToken } from "@/utils/auth";
import CategoryMedal from "@/backend/views/medal/category";
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
import vulanRoute from "./modules/vulan";
import store from "../store";
export const constantRoutes = [
    // handbookRoute,
    // hyperlinkRoute,
    vulanRoute,
    {
        path: "/login",
        component: Login,
        name: "Login",
        hidden: true,
        beforeEnter(to, from, next) {
            const queryParams = to.query;
            if (getAccessToken()) {
                if (queryParams.redirect_uri) {
                    window.location.href =
                        queryParams.redirect_uri +
                        "?token=" +
                        getAccessToken() +
                        "&state=" +
                        queryParams.state;
                } else {
                    next({ path: "/" });
                }
            } else {
                next();
            }
        },
    },
    {
        path: "/login-google",
        component: LoginGoogle,
        name: "LoginGoogle",
        hidden: true,
        beforeEnter(to, from, next) {
            const queryParams = to.query;
            if (getAccessToken()) {
                if (queryParams.redirect_uri) {
                    window.location.href =
                        queryParams.redirect_uri +
                        "?token=" +
                        getAccessToken() +
                        "&state=" +
                        queryParams.state;
                } else {
                    next({ path: "/" });
                }
            } else {
                next();
            }
        },
    },
    {
        path: "/terms",
        component: Terms,
        name: "Terms",
        hidden: true,
    },
    {
        path: "/privacy",
        component: Privacy,
        name: "Privacy",
        hidden: true,
    },
    {
        path: "/404",
        component: Page404,
        hidden: true,
        name: "404",
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
    {
        path: "/logout",
        name: "Logout",
        async beforeEnter(to, from, next) {
            // Kiểm tra xem người dùng đã đăng nhập hay chưa
            // Lấy các query parameters từ URL
            const queryParams = to.query;
            console.log("Query parameters:", queryParams);
            console.log("check getAccessToken", getAccessToken());
            if (getAccessToken()) {
                try {
                    // Thực hiện hàm đăng xuất
                    // await logout();
                    await store.dispatch("user/logout");
                    // Chuyển hướng người dùng đến trang đăng nhập
                    next({ path: "/login", query: queryParams });
                } catch (error) {
                    console.error("Error logging out:", error);
                    // Nếu có lỗi, vẫn chuyển hướng người dùng đến trang đăng nhập
                    next({ path: "/login", query: queryParams });
                }
            } else {
                next({ path: "/login", query: queryParams });
            }
        },
        hidden: true, // Ẩn router này khỏi thanh menu nếu cần
    },
];
export const asyncRoutes = [
    { path: "/:pathMatch(.*)*", redirect: "/404", hidden: true },
];

// export default router;
const createCustomRouter = () =>
    createRouter({
        //mode: 'history', // require service support
        history: createWebHistory(),
        scrollBehavior: () => ({ y: 0 }),
        routes: constantRoutes,
    });

const router = createCustomRouter();
// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
    const newRouter = createCustomRouter();
    router.matcher = newRouter.matcher; // reset router
}

export default router;
