import RenterLayout from '../components/Layout/RenterLayout.vue'
import Dashboard from '../Pages/Renter/Dashboard.vue'

export default [
  {
    path: '/renter',
    component: RenterLayout,
    redirect: { name: 'renter.dashboard' },
    children: [
              {
        path: 'dashboard',
        name: 'renter.dashboard',
        component: () => import('../Pages/Renter/Dashboard.vue'),
        meta: { title: 'Bike Management' },
      },
      {
        path: 'bikes',
        name: 'renter.bikes',
        component: () => import('../Pages/Renter/Bikes.vue'),
        meta: { title: 'Bike Management' },
      },

    ],
    meta: { role: 'renter', requiresAuth: true },
  },
]
