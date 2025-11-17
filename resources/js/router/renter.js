import RenterLayout from '../components/Layout/RenterLayout.vue'
import Dashboard from '../Pages/Renter/Dashboard.vue'

export default [
  {
    path: '/renter',
    component: RenterLayout,
    children: [
      { path: '', name: 'renter.dashboard', component: Dashboard },
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
