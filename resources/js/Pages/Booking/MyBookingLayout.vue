<template>
  <div class="my-bookings-page">
    <div class="container">
      <div class="page-header">
        <h1>My Bookings</h1>
        <p class="text-muted">Manage your upcoming and review past rentals.</p>
      </div>

      <!-- Tabs -->
      <BookingTabs v-model:activeTab="activeTab" />

      <!-- Booking List -->
      <div class="booking-list">
        <div v-if="filteredBookings.length === 0" class="text-center p-5">
          <p class="text-muted">You have no {{ activeTab }} bookings.</p>
        </div>

        <BookingCard
          v-for="booking in filteredBookings"
          :key="booking.id"
          :booking="booking"
          :is-past="activeTab === 'past'"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BookingTabs from './BookingTabs.vue';
import BookingCard from './BookingCards.vue';

const activeTab = ref('upcoming');

const bookings = ref([
  { id: 1, status: 'Confirmed', dates: 'Oct 25–28, 2025', totalPrice: 180, bike: { name: 'Trek Marlin 5', category: 'Mountain', imageUrl: 'https://placehold.co/600x400/63C1A2/FFFFFF?text=Bike' }},
  { id: 2, status: 'Pending', dates: 'Nov 10–12, 2025', totalPrice: 120, bike: { name: 'Specialized Allez', category: 'Road', imageUrl: 'https://placehold.co/600x400/333333/FFFFFF?text=Bike' }},
  { id: 3, status: 'Completed', dates: 'Sep 15–17, 2025', totalPrice: 105, bike: { name: 'Cannondale Quick', category: 'City', imageUrl: 'https://placehold.co/600x400/F8F9FA/333333?text=Bike' }},
  { id: 4, status: 'Cancelled', dates: 'Aug 20–22, 2025', totalPrice: 150, bike: { name: 'Rad Power RadCity 5', category: 'Electric', imageUrl: 'https://placehold.co/600x400/6c757d/FFFFFF?text=Bike' }}
]);

const filteredBookings = computed(() => {
  if (activeTab.value === 'upcoming') {
    return bookings.value.filter(b => ['Confirmed', 'Pending'].includes(b.status));
  } else {
    return bookings.value.filter(b => ['Completed', 'Cancelled'].includes(b.status));
  }
});
</script>

<style scoped>
.my-bookings-page {
  padding: 3rem 0;
  min-height: 100vh;
}
.page-header {
  text-align: center;
  margin-bottom: 3rem;
}
</style>
