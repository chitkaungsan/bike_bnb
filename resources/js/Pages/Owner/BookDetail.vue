<template>
  <div class="container py-4">

    <!-- Booking Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">Booking #{{ booking.id }}</h4>
      <span :class="statusClass" class="badge fs-6 text-uppercase">
        {{ booking.status }}
      </span>
    </div>

    <!-- Booking Date Info -->
    <div class="text-muted mb-4">
      <i class="bi bi-calendar-event me-2"></i>
      Booked from <strong>{{ formatDate(booking.start_date) }}</strong> 
      to <strong>{{ formatDate(booking.end_date) }}</strong>
      ({{ booking.days }} days)
    </div>

    <div class="row g-4">
      <!-- Bike Information -->
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <img :src="booking.bike_photo" class="card-img-top" alt="Bike photo" />
          <div class="card-body">
            <h5 class="card-title">{{ booking.bike_title }} {{ booking.bike_model }} ({{ booking.bike_year }})</h5>
            <p class="card-text mb-1">Daily Rate: ฿{{ booking.daily_rate }}</p>
            <p class="card-text mb-1">Total Days: {{ booking.days }}</p>
            <h5 class="text-primary mt-2 fw-bold">Total: ฿{{ booking.total_price }}</h5>
          </div>
        </div>
      </div>

      <!-- Store Information -->
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body d-flex align-items-center">
            <img :src="booking.store_logo" class="rounded me-3" alt="Store logo" width="70" height="70" />
            <div>
              <h5 class="card-title mb-1">{{ booking.store_name }}</h5>
              <p class="text-muted mb-0">
                <i class="bi bi-telephone me-2"></i>{{ booking.store_phone }}
              </p>
              <a :href="googleMapUrl" target="_blank" class="small text-decoration-none">
                <i class="bi bi-geo-alt me-1"></i>View on Google Maps
              </a>
            </div>
          </div>
        </div>

        <!-- Rider Information -->
        <div class="card shadow-sm mt-3">
          <div class="card-body">
            <h5 class="card-title">Rider Information</h5>
            <p class="mb-1"><i class="bi bi-person me-2"></i>{{ booking.rider_name }}</p>
            <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ booking.rider_phone }}</p>
            <p class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment: {{ booking.payment_type }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="mt-4 text-end">
      <button v-if="booking.status === 'pending'" class="btn btn-success me-2" @click="confirmBooking">
        <i class="bi bi-check-circle me-1"></i> Confirm
      </button>
      <button v-if="booking.status === 'pending'" class="btn btn-outline-danger" @click="cancelBooking">
        <i class="bi bi-x-circle me-1"></i> Cancel
      </button>

      <button v-if="booking.status === 'confirmed'" class="btn btn-success" disabled>
        Confirmed ✅
      </button>

      <button v-if="booking.status === 'cancelled'" class="btn btn-secondary" disabled>
        Cancelled ❌
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref, computed,onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from 'vuex'
import { useToast } from "primevue/usetoast";


const toast = useToast()
const route = useRoute()
const store = useStore()
const booking = ref({})

// Map link
const googleMapUrl = computed(() =>
  `https://maps.google.com/?q=${booking.value.store_location}`
)

// Status badge color
const statusClass = computed(() => {
  switch (booking.value.status) {
    case 'pending':
      return 'bg-warning text-dark'
    case 'confirmed':
      return 'bg-success'
    case 'cancelled':
      return 'bg-danger'
    default:
      return 'bg-secondary'
  }
})

// Format date
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })
}

// Simulate actions
const confirmBooking = async () => {

    try {
        const bookingId = booking.value.id
        await store.dispatch('booking/confirmBooking', bookingId)
    } catch (error) {
        console.error('Error confirming booking:', error)
        toast.add({severity:'error', summary: 'Error', detail: 'Failed to confirm booking.', life: 3000});
    } finally {
       toast.add({severity:'success', summary: 'Success', detail: 'Booking confirmed successfully.', life: 3000});
    }
  // In real app, you'd call an API to confirm the booking   
  booking.value.status = 'confirmed'
}

const cancelBooking = async () => {
  booking.value.status = 'cancelled'
 try {
        const bookingId = booking.value.id
        await store.dispatch('booking/cancelBooking', bookingId)
    } catch (error) {
        console.error('Error cancelling booking:', error)
        toast.add({severity:'error', summary: 'Error', detail: 'Failed to cancel booking.', life: 3000});
    } finally {
       toast.add({severity:'success', summary: 'Success', detail: 'Booking cancelled successfully.', life: 3000});
    }
  // In real app, you'd call an API to cancel the booking  
  booking.value.status = 'cancelled'
}

onMounted( async () => {

    const bookingId = route.params.id
    const res = await store.dispatch('booking/fetchBookingDetail', bookingId)
    booking.value = res.data
  // Here you could fetch booking details from an API if needed
})
</script>

<style scoped>
.card img {
  object-fit: cover;
}
</style>
