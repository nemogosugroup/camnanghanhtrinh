import request from "@/backend/respository/request";
const resource = "/vulan";

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

    update(query) {
        let data = query["data"];
        return request({
            url: `${resource}` + "/update/" + `${query.id}`,
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
