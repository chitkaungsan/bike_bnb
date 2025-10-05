import OwnerLayout from '../components/Layout/OwnerLayout.vue'
import Dashboard from '../pages/Owner/Dashboard.vue'

export default [
  {
    path: '/owner',
    component: OwnerLayout,
    redirect: { name: 'owner.dashboard' },
    children: [
      {
        path: 'dashboard', // relative path!
        name: 'owner.dashboard',
        component: Dashboard,
      },
      {
        path: 'bikes', // relative path!
        name: 'owner.bikes',
        component: () => import('../pages/Owner/Bikes.vue'),
      },
      {
        path: 'bikes/add',
        name: 'owner.bikes.add',
        component: () => import('../components/Owner/components/Bike/Add.vue'),
      },
    ],
  },
]
