const hyperlinkRoute = {
    path: '/hyperlink',
    meta: {
        title: 'Liên kết',
        icon: 'ri-links-fill',
    },
    children: [
        {
            path: "/gosucorp.vn",
            children: [
                {
                    path: "https://gosucorp.vn/",
                    meta: { title: "gosucorp.vn", icon: "ri-links-fill" },
                },
            ],
        },
        {
            path: "/home.gosucorp.vn",
            children: [
                {
                    path: "https://home.gosucorp.vn/",
                    meta: { title: "home.gosucorp.vn", icon: "ri-links-fill" },
                },
            ],
        },
        {
            path: "/tool.gosu.vn",
            children: [
                {
                    path: "https://tool.gosu.vn/",
                    meta: { title: "tool.gosu.vn", icon: "ri-links-fill" },
                },
            ],
        },
        {
            path: "/s20.gosu.vn",
            children: [
                {
                    path: "https://s20.gosu.vn/",
                    meta: { title: "Trang nội bộ", icon: "ri-links-fill" },
                },
            ],
        },
        {
            path: "/studio.gosucorp.vn",
            children: [
                {
                    path: "https://studio.gosucorp.vn/",
                    meta: { title: "Tin game tổng hợp", icon: "ri-links-fill" },
                },
            ],
        },
        {
            path: "/crm.gosucorp.vn",
            children: [
                {
                    path: "https://crm.gosucorp.vn/",
                    meta: { title: "Thương vụ", icon: "ri-links-fill" },
                },
            ],
        },
        {
            path: "/ov.gosucorp.vn",
            children: [
                {
                    path: "https://ov.gosucorp.vn/",
                    meta: { title: "Dịch thuật", icon: "ri-links-fill" },
                },
            ],
        },
    ],
}
export default hyperlinkRoute;
