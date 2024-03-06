import Layout from '@/layout'
import Equipment from '@/backend/views/equipment'
import CategoryEquipment from '@/backend/views/equipment/category'
import TypeEquipment from '@/backend/views/equipment/type'
const equipmentsRouter = {
    path: '/admin/trang-bi',
    component: Layout,
    redirect: '/admin/trang-bi',
    name: 'Trang Bị',
    meta: {
        title: 'Trang Bị',
        icon: 'ri-shirt-line'
    },
    children: [
        {
            path: 'danh-muc-trang-bi',
            component: CategoryEquipment,
            name: 'CategoryEquipment',
            meta: { title: 'Danh Mục', icon: 'ri-layout-grid-fill'}
        },
        {
            path: 'loai-trang-bi',
            component: TypeEquipment,
            name: 'TypeEquipment',
            meta: { title: 'Loại', icon: 'ri-layout-grid-line'}
        },
        {
            path: 'trang-bi',
            component: Equipment,
            name: 'Equipment',
            meta: { title: 'Trang Bị', icon: 'ri-shirt-line' }
        }
    ]
}
  
export default equipmentsRouter