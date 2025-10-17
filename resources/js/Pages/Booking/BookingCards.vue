<template>
  <div class="booking-card card mb-4 shadow-sm border-0">
    <div class="row g-0 align-items-stretch">
      <!-- Bike Image -->
      <div class="col-md-4 col-sm-12">
        <img
          :src="booking.bike_photo"
          class="img-fluid rounded-start h-100 object-fit-cover"
          :alt="booking.bike_title"
        />
      </div>

      <!-- Booking Info -->
      <div class="col-md-8 col-sm-12">
        <div class="card-body d-flex flex-column justify-content-between h-100">
          <!-- Store Info -->
          <div class="d-flex align-items-center mb-3">
            <img
              v-if="booking.store_logo"
              :src="booking.store_logo"
              alt="Store Logo"
              class="store-logo me-2"
            />
            <div>
              <h6 class="mb-0">{{ booking.store_name }}</h6>
              <small class="text-muted">📞 {{ booking.store_phone || 'N/A' }}</small>
            </div>
          </div>

          <!-- Bike Info -->
          <div>
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h5 class="card-title mb-1">{{ booking.bike_title }}</h5>
                <p class="card-text text-muted mb-1">
                  Model: {{ booking.bike_model }} ({{ booking.bike_year }})
                </p>
              </div>
              <span :class="`badge status-${booking.status.toLowerCase()}`">
                {{ booking.status }}
              </span>
            </div>

            <div class="booking-details mb-2">
              <div>
                <strong>Start Date:</strong>
                <span>{{ formatDate(booking.start_date) }}</span>
              </div>
              <div>
                <strong>End Date:</strong>
                <span>{{ formatDate(booking.end_date) }}</span>
              </div>
              <div>
                <strong>Days:</strong>
                <span>{{ booking.days }}</span>
              </div>
              <div>
                <strong>Daily Rate:</strong>
                <span>฿{{ formatNumber(booking.daily_rate) }}</span>
              </div>
              <div>
                <strong>Total Price:</strong>
                <span class="fw-bold text-primary">฿{{ formatNumber(booking.total_price) }}</span>
              </div>
              <div>
                <strong>Payment:</strong>
                <span>{{ booking.payment_type || 'N/A' }}</span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-3">
            <button class="btn btn-outline-primary btn-sm">
              View Details
            </button>
            <button
              v-if="isPast"
              class="btn btn-primary btn-sm ms-2"
            >
              Leave a Review
            </button>
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

function formatDate(date) {
  if (!date) return '';
  const d = new Date(date);
  return d.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
}

// 💰 Format number with commas (e.g. 100000 -> 100,000)
function formatNumber(value) {
  if (value === null || value === undefined) return '0';
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0 }).format(value);
}
</script>

<style scoped>
.booking-card {
  background-color: var(--section-bg-color, #fff);
  border-radius: var(--border-radius-md, 10px);
  overflow: hidden;
  transition: all 0.2s ease-in-out;
}
.booking-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.store-logo {
  width: 45px;
  height: 45px;
  border-radius: 8px;
  object-fit: cover;
  border: 1px solid #ddd;
}

.badge {
  font-size: 0.8rem;
  padding: 0.5em 0.9em;
  border-radius: 20px;
  text-transform: capitalize;
}
.status-confirmed { background-color: #28a745; color: #fff; }
.status-pending { background-color: #ffc107; color: #333; }
.status-completed { background-color: #007bff; color: #fff; }
.status-cancelled { background-color: #dc3545; color: #fff; }

.booking-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 0.5rem;
  font-size: 0.9rem;
}
.booking-details strong {
  display: block;
  color: #555;
}

@media (max-width: 768px) {
  .booking-card img {
    border-radius: 10px 10px 0 0;
  }
}
</style>
