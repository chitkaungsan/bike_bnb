import AdminLayout from '../components/Layout/AdminLayout.vue'
import Dashboard from '../Pages/Admin/Dashboard.vue'

export default [
  {
    path: '/admin',
    component: AdminLayout,
    children: [
      { path: '', name: 'admin.dashboard', component: Dashboard },
      {
        path: 'bikes',
        name: 'owner.bikes',
        component: () => import('../pages/Admin/Bikes.vue'),
        meta: { title: 'Bike Management' },
      },
      {
        path: 'bikes',
        name: 'admin.bikes.add',
        component: () => import('../components/Admin/components/Bike/Add.vue'),
        meta: { title: 'Bike Management' },
      },
    ],
    meta: { role: 'admin', requiresAuth: true },
  },
]
