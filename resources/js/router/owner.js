import OwnerLayout from '../components/Layout/OwnerLayout.vue'
import Dashboard from '../Pages/Owner/Dashboard.vue'

export default [
  {
    path: '/owner',
    component: OwnerLayout,
    children: [
      { path: '', name: 'owner.dashboard', component: Dashboard },
      {
        path: 'bikes',
        name: 'owner.bikes',
        component: () => import('../pages/Owner/Bikes.vue'),
        meta: { title: 'Bike Management' },
      },
      {
        path: 'bikes',
        name: 'owner.bikes.add',
        component: () => import('../components/Owner/components/Bike/Add.vue'),
        meta: { title: 'Bike Management' },
      },
    ],
    meta: { role: 'owner', requiresAuth: true },
  },
]
