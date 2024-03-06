import Layout from '@/layout'
import Mission from '@/backend/views/mission'
import CategoryMission from '@/backend/views/mission/category'
import SubCategoryMission from '@/backend/views/mission/subcategory'
const missionsRouter = {
    path: '/admin/nhiem-vu',
    component: Layout,
    redirect: '/admin/nhiem-vu',
    name: 'Nhiệm Vụ',
    meta: {
        title: 'Nhiệm Vụ',
        icon: 'ri-quill-pen-fill'
    },
    children: [
        {
            path: 'danh-muc-nhiem-vu',
            component: CategoryMission,
            name: 'CategoryMission',
            meta: { title: 'Danh Mục', icon: 'ri-layout-grid-fill'}
        },
        {
            path: 'hang-muc-nhiem-vu',
            component: SubCategoryMission,
            name: 'SubCategoryMission',
            meta: { title: 'Hạng Mục', icon: 'ri-layout-grid-line'}
        },
        {
            path: 'nhiem-vu',
            component: Mission,
            name: 'Misson',
            meta: { title: 'Nhiệm Vụ', icon: 'ri-quill-pen-fill' }
        }
    ]
}
  
export default missionsRouter