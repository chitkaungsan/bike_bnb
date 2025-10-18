import OwnerLayout from '../components/Layout/OwnerLayout.vue'
import Dashboard from '../Pages/Owner/Dashboard.vue'
import OwnerBooking from "../Pages/Owner/Booking.vue"


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
        component: () => import('../Pages/Owner/Bikes.vue'),
      },
      {
        path: 'bikes/add',
        name: 'owner.bikes.add',
        component: () => import('../components/Owner/components/Bike/Add.vue'),
      },{
        path: 'stores',
        name: 'owner.stores',
        component: () => import('../Pages/Owner/Stores.vue'),
      },
      {
        path: 'stores/add',
        name: 'owner.stores.add',
        component: () => import('../components/Owner/components/Store/Add.vue'),
      },
      { 
        path: '/owner/bookings',
        name: 'owener.bookings',
        component: OwnerBooking
      }

   
    ],
  },
]
