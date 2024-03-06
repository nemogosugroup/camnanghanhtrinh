import Layout from '@/layout'
import Course from '@/backend/views/course'
import CategoryCourse from '@/backend/views/course/category'
const courseRouter = {
    path: '/admin/khoa-hoc',
    component: Layout,
    redirect: '/admin/khoa-hoc',
    name: 'Khoá học',
    meta: {
        title: 'khoá học',
        icon: 'ri-git-repository-fill'
    },
    children: [
        {
            path: 'danh-muc-khoa-hoc',
            component: CategoryCourse,
            name: 'CategoryCourse',
            meta: { title: 'Danh mục', icon: 'ri-layout-grid-fill'}
        },
        {
            path: 'khoa-hoc',
            component: Course,
            name: 'Course',
            meta: { title: 'Khoá học', icon: 'ri-git-repository-fill' }
        }
    ]
}
  
export default courseRouter