import router from "./router";
import store from "./store";
import { ElMessage } from "element-plus";
import NProgress from "nprogress"; // progress bar
import "nprogress/nprogress.css"; // progress bar style
import { getAccessToken, removeToken, setLayout } from "@/utils/auth"; // get token from cookie
import getPageTitle from "@/utils/get-page-title";

NProgress.configure({ showSpinner: false }); // NProgress Configuration

const whiteList = [
    "/terms",
    "/privacy",
    "/login-google",
    "/login",
    "/logout",
    "/auth-redirect"
]; // no redirect whitelist
router.beforeEach(async (to, from, next) => {
    // start progress bar
    NProgress.start();
    // set page title
    document.title = getPageTitle(to.meta.title);
    // determine whether the user has logged in
    //removeToken();
    const hasToken = getAccessToken();

    // kiểm tra login với các site vệ tinh
    const currentUrl = window.location.href;
    const urlParams = new URLSearchParams(window.location.search);
    var site = urlParams.get('redirect');
    var redirect = site ? atob(site) : null;
    if(redirect && hasToken && hasToken != "undefined"){ 
        removeToken();
    }
    //const hasToken = null;
    if (hasToken && hasToken != "undefined") {
        const hasRoles = store.getters.roles && store.getters.roles.length > 0;
        setLayout(to.meta.active);
        if (hasRoles) {
            let roles = store.getters.roles;
            const accessRoutes = await store.dispatch(
                "permission/generateRoutes",
                roles
            );
            accessRoutes.forEach((route) => {
                router.addRoute(route);
            });
            if (to.name === "404") {
                next({ name: "HandBook" });
            }
            next();
        } else {
            if(redirect){
                window.location.href = `${currentUrl}`;
            }
            var roles = await store.dispatch("user/getRoles");
            if (store.getters.roles.length <= 0) {
                store.dispatch("user/logout");
                router.push(`/login`);
            }
            var info = await store.dispatch("user/getInfo");
            // generate accessible routes map based on roles
            const accessRoutes = await store.dispatch(
                "permission/generateRoutes",
                roles
            );
            accessRoutes.forEach((route) => {
                router.addRoute(route);
            });
            if (to.path === "/login") {
                next();
                NProgress.done();
            } else {
                if (to.name === "404") {
                    next({ name: "HandBook" });
                }
                if (to.path === "/") {
                    next({ name: "HandBook" });
                } else {
                    next({ ...to, replace: true });
                }
            }
        }
    } else {
        /* has no token*/
        const accessRoutes = await store.dispatch(
            "permission/generateRoutes",
            false
        );
        //console.log("this.$router.routes", router);
        accessRoutes.forEach((route) => {
            router.addRoute(route);
        });

        if (whiteList.indexOf(to.path) !== -1) {
            // in the free login whitelist, go directly
            next();
           
        } else {
            if (to.name == "VuLanDetail") {
                next();
            } else {
                next(`/login`);
            }
            // other pages that do not have permission to access are redirected to the login page.
            //next(`/login?redirect=${to.path}`);

            NProgress.done();
        }
    }
});

router.afterEach(() => {
    // finish progress bar
    NProgress.done();
});
