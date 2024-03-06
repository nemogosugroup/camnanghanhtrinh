import Layout from '@/layout'
import Level from '@/backend/views/level'

const levelsRouter = {
    path: "/admin/level",
    component: Layout,
    redirect: "level",
    children: [
        {
            path: "level",
            component: Level,
            name: "Level",
            meta: {
                title: "Level",
                icon: "ri-arrow-up-circle-fill",
                affix: true,
            },
        },
    ],
}
  
export default levelsRouter