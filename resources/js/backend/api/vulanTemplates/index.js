import request from "@/backend/respository/request";
const resource = "/vulan-templates";

export default {
    list(query) {
        return request({
            url: `${resource}` + "/list",
            method: 'get',
            params: query
        })
    },
    detail(id) {
        return request({
            url: `${resource}` + "/detail/" + id,
            method: 'get'
        })
    },
    userCreate(data) {
        return request({
            url: `${resource}` + "/create/",
            method: 'post',
            data,
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
    },
    uploadPreviewVideo(data) {
        return request({
            url: `${resource}` + "/upload-preview-video/",
            method: 'post',
            data,
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
    }
}