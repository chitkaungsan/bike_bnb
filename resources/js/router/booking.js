import BookingStep from "../Pages/BookingStep.vue"
import BookingSuccess from "../components/BookingStep/BookingSuccess.vue"
export default [
  {
    path: '/booking/step',
    name: 'booking.step',
    component: BookingStep
  },
  {
    path: '/booking/success/:id',
    name: 'booking.success',
    component: BookingSuccess
  }
]
