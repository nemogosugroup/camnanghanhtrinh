import request from "@/backend/respository/request";
const resource = "/vulan-templates";

export default {
    create(data) {
        return request({
            url: `${resource}` + "/create",
            method: "post",
            data: data,
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },

    detail(id) {
        //get id template
        return request({
            url: `${resource}` + "/detail/" + `${id}`,
            method: "get",
        });
    },

    update(data, id) {
        return request({
            url: `${resource}` + "/update/" + `${id}`,
            method: "post",
            data,
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },

    delete(id) {
        return request({
            url: `${resource}` + "/delete/" + `${id}`,
            method: "post",
        });
    },
};
