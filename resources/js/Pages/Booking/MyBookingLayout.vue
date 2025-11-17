<template>
  <div class="my-bookings-page">
    <div class="container">
      <div class="page-header">
        <h1>My Bookings</h1>
        <p class="text-muted">Manage your upcoming and review past rentals.</p>
      </div>

      <!-- Show loading -->
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading your bookings...</p>
      </div>

      <!-- Tabs -->
      <BookingTabs v-else v-model:activeTab="activeTab" />

      <!-- Booking List -->
      <div v-if="!isLoading" class="booking-list">
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
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import BookingTabs from './BookingTabs.vue';
import BookingCard from './BookingCards.vue';

const activeTab = ref('upcoming');
const store = useStore();

// Getters
const user = computed(() => store.getters['auth/user']);
const myBookings = computed(() => store.getters['booking/myBookings'] || []);
const isLoading = computed(() => store.getters['booking/isLoading']);

// Filter logic
const filteredBookings = computed(() => {
  if (activeTab.value === 'upcoming') {
    return myBookings.value.filter(b => ['pending', 'confirmed'].includes(b.status));
  } else {
    return myBookings.value.filter(b => ['completed', 'cancelled'].includes(b.status));
  }
});

// Fetch data when user available
onMounted(() => {
  const stopWatch = watch(
    user,
    async (val) => {
      if (val && val.id) {
        await store.dispatch('booking/fetchUserBookings', val.id);
        stopWatch();
      }
    },
    { immediate: true }
  );
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
