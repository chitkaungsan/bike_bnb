<template>
  <div class="booking-card card mb-4">
    <div class="row g-0">
      <div class="col-md-3">
        <img :src="booking.bike.imageUrl" class="img-fluid rounded-start" :alt="booking.bike.name">
      </div>
      <div class="col-md-9">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="card-title">{{ booking.bike.name }}</h5>
              <p class="card-text text-muted">{{ booking.bike.category }}</p>
            </div>
            <span :class="`badge status-${booking.status.toLowerCase()}`">{{ booking.status }}</span>
          </div>

          <hr>

          <div class="booking-details">
            <div>
              <strong>Dates:</strong>
              <span>{{ booking.dates }}</span>
            </div>
            <div>
              <strong>Total Price:</strong>
              <span>${{ booking.totalPrice }}</span>
            </div>
          </div>

          <div class="booking-actions mt-3">
            <button class="btn btn-outline-primary btn-sm">View Details</button>
            <button v-if="isPast" class="btn btn-primary btn-sm ms-2">Leave a Review</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  booking: Object,
  isPast: Boolean
});
</script>

<style scoped>
.booking-card {
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  overflow: hidden;
}
.booking-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.card-title { font-weight: 600; }

.badge {
  font-size: 0.8rem;
  padding: 0.5em 0.9em;
  border-radius: 20px;
}
.status-confirmed { background-color: #28a745; color: white; }
.status-pending { background-color: #ffc107; color: #333; }
.status-completed { background-color: #007bff; color: white; }
.status-cancelled { background-color: #dc3545; color: white; }

.booking-details {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  font-size: 0.9rem;
}
.booking-details strong {
  display: block;
  color: var(--light-text-color);
  margin-bottom: 0.2rem;
}

@media (max-width: 768px) {
  .booking-card img {
    border-radius: var(--border-radius-md) var(--border-radius-md) 0 0;
  }
  .booking-details {
    grid-template-columns: 1fr;
  }
}
</style>
