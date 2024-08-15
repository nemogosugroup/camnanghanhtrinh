import request from "@/utils/request";
const resource = "/vulan-templates";

export default {
    list() {
        return request({
            url: `${resource}` + "/list",
            method: "get",
            //params: query,
        });
    },
    detail(id) {
        return request({
            url: `${resource}` + `/detail/${id}`,
            method: "get",
        });
    },
};
