import request from "@/utils/request";
const resource = "/vulan-templates";

export default {
    list(query) {
        return request({
            url: `${resource}` + "/list",
            method: "get",
            params: query,
        });
    },
    detail(id) {
        return request({
            url: `${resource}` + `/detail/${id}`,
            method: "get",
        });
    },
    delete(id) {
        return request({
            url: `${resource}` + `/delete/${id}`,
            method: "post",
        });
    },
};
