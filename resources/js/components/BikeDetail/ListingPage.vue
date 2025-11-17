<template>
  <div class="container my-5 bike-detail-page">
    <!-- Island background layer -->
    <IslandBackground :max-icons="5" />

    <div class="row g-5">
      <!-- LEFT: Bike Details -->
      <div class="col-lg-7">
        <div class="section-card main-content">
          <!-- Header Row -->
          <div class="d-flex align-items-start justify-content-between">
            <!-- Title and info -->
            <div class="bike-info">
              <h3 class="bike-title mb-1">{{ bike_detail.title }}</h3>
              <p class="bike-subtitle">
                {{ bike_detail.model }} · Manufactured in {{ bike_detail.year }}
              </p>
            </div>

            <!-- Brand logo -->
            <div
              class="bike-logo ms-auto text-center"
              v-tooltip.top="bike_detail.brand_name"
            >
              <img
                v-if="bike_detail.brand_logo"
                :src="bike_detail.brand_logo"
                alt="logo"
                class="brand-image"
              />
              <i v-else class="bi bi-shop fs-3 text-muted"></i>
            </div>
          </div>

          <!-- Store Review -->
          <div class="d-flex align-items-start justify-content-between">
            <StoreReview
              :store_id="bike_detail.store_id"
              :store_logo="bike_detail.store_logo"
              :store_name="bike_detail.store_name"
              :address="bike_detail.store_address"
            />
          </div>

          <hr class="divider" />

          <!-- Description -->
          <p class="bike-description" v-html="bike_detail.description"></p>
        </div>
      </div>

      <!-- RIGHT: Booking Panel -->
      <div class="col-lg-5">
        <div ref="rightColumnRef" class="sticky-booking">
          <BookingPanel
            :price-per-day="bike_detail.price"
            :parent-container="rightColumnRef"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import BookingPanel from "./BookingPanel.vue";
import StoreReview from "./StoreReview.vue";
import IslandBackground from "../islandBackground.vue"; // ✅ added import

const store = useStore();
const bike_detail = computed(() => store.getters["bikes/bike_details"]);
const rightColumnRef = ref(null);
</script>

<style scoped>
/* ------------------------------
   Layout & Container
------------------------------ */
.bike-detail-page {
  position: relative;
  background-color: transparent;
  overflow: hidden;
  isolation: isolate; /* keep z-index layers clean */
  min-height: 100vh;
}

/* Background layer behind all content */
.island-background {
  position: absolute;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  opacity: 0.6;
  overflow: hidden;
}

/* ------------------------------
   Main Content Section
------------------------------ */
.main-content {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
  border: 1px solid var(--border-color);
  box-shadow: 0 4px 12px var(--shadow-color);
  padding: 2rem;
  transition: background-color var(--transition-speed), color var(--transition-speed);
  position: relative;
  z-index: 2;
}

/* ------------------------------
   Bike Info
------------------------------ */
.bike-title {
  font-weight: 600;
  color: var(--text-color);
}

.bike-subtitle {
  color: var(--secondary-text-color);
  font-size: 1rem;
}

.bike-description {
  color: var(--text-color);
  line-height: 1.6;
}

/* ------------------------------
   Brand Logo
------------------------------ */
.bike-logo {
  width: 60px;
  height: 60px;
}

.brand-image {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 2px solid var(--primary-color);
  object-fit: cover;
  box-shadow: 0 2px 6px var(--shadow-color);
  transition: transform 0.3s ease;
}
.brand-image:hover {
  transform: scale(1.05);
}

/* ------------------------------
   Text Content
------------------------------ */
.divider {
  margin: 1.5rem 0;
  border-color: var(--border-color);
}

.bike-description * {
  color: inherit !important;
}

/* ------------------------------
   Booking Panel
------------------------------ */
.sticky-booking {
  position: sticky;
  top: 100px;
  z-index: 2;
}

/* ------------------------------
   Mobile Spacing Fix
------------------------------ */
@media (max-width: 991.98px) {
  .bike-detail-page {
    padding-bottom: 100px;
  }
}
</style>
