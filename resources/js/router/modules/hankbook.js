import Helper from '@/helper';
import HandBook from "@/views/handbook";
import HandBookInfo from "@/views/handbook/infomation";
import HandBookWelcome from "@/views/handbook/welcome";
import HandBookQuest from "@/views/handbook/quest";
import HandBookEquipment from "@/views/handbook/equipment";
import HandBookTrip from "@/views/handbook/trip";
import HandBookMarket from "@/views/handbook/market";
//import CategoryCourseMarket from "@/views/handbook/market/components/course";
//import CategoryCourseMarket from "@/views/handbook/market/components/course";
//import CategoryEquipmentMarket from "@/views/handbook/market/components/equipment";
//import EquipmentMarket from "@/views/handbook/market/Equipment";
//import EquipmentMarket from "@/views/handbook/market";
import layoutBook from "@/LayoutHandbook";
const handbookRoute = {
    path: '/',
    component: layoutBook,
    name: 'HandBook',
    redirect: '/so-tay-hanh-trinh',
    meta: {
        title: 'Sổ tay hành trình',
        icon: 'ri-git-repository-fill',
        noCache: true,
    },
    children: [
        {
            path: '/so-tay-hanh-trinh',
            name: 'HandBook',
            component: HandBook,
            meta: { 
                title: 'Sổ tay hành trình', 
                icon:'ri-git-repository-fill',
                noCache: true, 
                active:'home',
                activeMenu: "/so-tay-hanh-trinh",
            },
            hidden: true,
            show:false
        },
        {
            path: '/so-tay-hanh-trinh/thong-tin',
            name: 'HandBookInfo',
            component: HandBookInfo,
            meta: { 
                title: 'Thông tin', 
                icon:'ri-git-repository-fill', 
                noCache: true, 
                active:'info',
                color:Helper.colorMenuMarket.info,
                activeMenu: "/so-tay-hanh-trinh"             
            },
            hidden: true,
            show:true,
        },
        {
            path: '/so-tay-hanh-trinh/welcome',
            name: 'HandBookWelcome',
            component: HandBookWelcome,
            meta: { 
                title: 'Welcome', 
                icon:'ri-git-repository-fill',
                active: 'welcome',
                noCache: true,
                activeMenu: "/so-tay-hanh-trinh"
            },
            hidden: true,
            show:false
        },
        {
            path: '/so-tay-hanh-trinh/nhiem-vu',
            name: 'HandBookQuest',
            component: HandBookQuest,
            meta: { 
                title: 'Nhiệm vụ', 
                icon:'ri-git-repository-fill', 
                noCache: true, 
                active:'quest',
                color:Helper.colorMenuMarket.quest,
                activeMenu: "/so-tay-hanh-trinh"
            },
            hidden: true,
            show:true
        },
        {
            path: '/so-tay-hanh-trinh/trang-bi',
            name: 'HandBookEquipment',
            component: HandBookEquipment,
            meta: { 
                title: 'Trang bị', 
                icon:'ri-git-repository-fill', 
                noCache: true, 
                active: 'equipment',
                color:Helper.colorMenuMarket.equipment,
                activeMenu: "/so-tay-hanh-trinh"
            },
            hidden: true,
            show:true
        },
        {
            path: '/so-tay-hanh-trinh/hanh-trinh',
            name: 'HandBookTrip',
            component: HandBookTrip,
            meta: { 
                title: 'Hành trình', 
                icon:'ri-git-repository-fill', 
                noCache: true, 
                active: 'trip',
                color:Helper.colorMenuMarket.trip,
                activeMenu: "/so-tay-hanh-trinh"
            },
            hidden: true,
            show:true
        },
        {
            path: "/so-tay-hanh-trinh/market",
            redirect: "/so-tay-hanh-trinh/market/khoa-hoc",
            name: "Market",
            meta: { 
                title: 'Market', 
                name:'Market',
                icon:'ri-git-repository-fill',
                type: Helper.typeMarket.course,
                active: 'market',
                color:Helper.colorMenuMarket.market,
                activeMenu: "/so-tay-hanh-trinh"
            },
            hidden: true,
            show:true,
            children:[
                {
                    path: 'khoa-hoc',
                    name: 'HandBookMarket',
                    component: HandBookMarket,
                    meta: { 
                        title: 'Khoá học', 
                        name:'Market',
                        icon:'ri-git-repository-fill',
                        type: Helper.typeMarket.course,
                        active: 'market',
                        activeMenu: "/so-tay-hanh-trinh"
                    },
                    hidden: true,
                },
                {
                    path: 'trang-bi',
                    name: 'EquipmentMarket',
                    component: HandBookMarket,
                    meta: { 
                        title: 'Trang bị', 
                        name:'Market',
                        icon:'ri-git-repository-fill',
                        type: Helper.typeMarket.equipment,
                        active: 'market',
                        activeMenu: "/so-tay-hanh-trinh"
                    },
                    hidden: true,
                },
                {
                    path: 'danh-muc-khoa-hoc/:slug',
                    name: 'CourseMarket',
                    component: HandBookMarket,
                    meta: { 
                        title: 'Danh mục khoá học', 
                        name:'Market',
                        icon:'ri-git-repository-fill',
                        type: Helper.typeMarket.course,
                        active: 'market',
                        activeMenu: "/so-tay-hanh-trinh"
                    },
                    hidden: true,
                },                    
                {
                    path: 'danh-muc-trang-bi/:slug',
                    name: 'CategoryequipmentMarket',
                    component: HandBookMarket,
                    meta: { 
                        title: 'Danh mục trang bị', 
                        name:'Market',
                        icon:'ri-git-repository-fill',
                        type: Helper.typeMarket.equipment,
                        active: 'market',
                        activeMenu: "/so-tay-hanh-trinh"
                    },
                    hidden: true,
                },                    
            ]
        },
    ],
}
export default handbookRoute;
