import Layout from '@/layout'
import Medal from '@/backend/views/medal'
import CategoryMedal from '@/backend/views/medal/category'

const medalsRouter = {
    path: '/admin/danh-hieu',
    component: Layout,
    redirect: '/admin/danh-hieu',
    name: 'Danh Hiệu',
    meta: {
        title: 'Danh Hiệu',
        icon: 'ri-bookmark-3-fill'
    },
    children: [
        {
            path: 'danh-muc',
            component: CategoryMedal,
            name: 'CategoryMedal',
            meta: { title: 'Danh Mục', icon: 'ri-grid-fill'}
        },
        {
            path: 'danh-hieu',
            component: Medal,
            name: 'Medal',
            meta: { title: 'Danh Hiệu', icon: 'ri-bookmark-3-fill' }
        }
    ]
}
  
export default medalsRouter