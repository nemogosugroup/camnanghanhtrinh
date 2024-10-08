import { asyncRoutes, constantRoutes } from "@/router";
import { adminRoute } from "@/backend/router";
import handbookRoute from "@/router/modules/hankbook";
import hyperlinkRoute from "@/router/modules/hyperlink";
import templateVulanRouter from "@/backend/router/modules/templatevulan";

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
    if (route.meta && route.meta.roles) {
        return roles.some((role) => route.meta.roles.includes(role));
    } else {
        return true;
    }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
    const res = [];
    routes.forEach((route) => {
        const tmp = { ...route };
        if (hasPermission(roles, tmp)) {
            if (tmp.children) {
                tmp.children = filterAsyncRoutes(tmp.children, roles);
            }
            res.push(tmp);
        }
    });
    return res;
}

export function mergeRoute(a, b, prop) {
    var reduced = a.filter(function (aitem) {
        return !b.find(function (bitem) {
            return aitem[prop] === bitem[prop];
        });
    });
    return reduced.concat(b);
}

function merge(a, b, prop) {
    var reduced = a.filter(function (aitem) {
        return !b.find(function (bitem) {
            return aitem[prop] === bitem[prop];
        });
    });
    return reduced.concat(b);
}

const state = {
    routes: [],
    addRoutes: [],
};

const mutations = {
    SET_ROUTES: (state, routes) => {
        state.addRoutes = routes;
        state.routes = constantRoutes.concat(routes);
    },
};

const actions = {
    generateRoutes({ commit }, roles) {
        return new Promise((resolve) => {
            let accessedRoutes = [];
            accessedRoutes = mergeRoute(accessedRoutes, asyncRoutes, "path");
            let _templateVulanRouter = [templateVulanRouter];
            if (roles) {
                if (roles.includes("admin")) {
                    accessedRoutes = mergeRoute(
                        accessedRoutes,
                        adminRoute,
                        "path"
                    );
                    accessedRoutes = mergeRoute(
                        accessedRoutes,
                        handbookRoute,
                        "path"
                    );
                    accessedRoutes = mergeRoute(
                        accessedRoutes,
                        hyperlinkRoute,
                        "path"
                    );
                }
                if (roles.includes("user")) {
                    accessedRoutes = mergeRoute(
                        accessedRoutes,
                        handbookRoute,
                        "path"
                    );
                    accessedRoutes = mergeRoute(
                        accessedRoutes,
                        hyperlinkRoute,
                        "path"
                    );
                    accessedRoutes = mergeRoute(
                        accessedRoutes,
                        _templateVulanRouter,
                        "path"
                    );
                }
            }
            commit("SET_ROUTES", accessedRoutes);
            resolve(accessedRoutes);
        });
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
