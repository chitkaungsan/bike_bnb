<template>
  <div class="booking-panel-wrapper" ref="wrapperRef">
    <div class="d-none d-lg-block">
      <div class="booking-panel" ref="panelRef" :class="stickyClass">
        <div class="p-4 shadow rounded-4 border">
          <div class="d-flex align-items-center mb-3">
            <span class="price-amount fw-bold fs-4">{{ formattedPrice }}</span>
            <span class="price-period ms-1 text-muted">day</span>
          </div>

          <div class="border rounded-3 mb-3">
            <div class="row g-0">
              <div class="col-6">
                <div class="p-2 border-end">
                  <label for="checkin" class="form-label text-uppercase small fw-bold"
                    >Check-in</label
                  >
                  <Calendar
                    v-model="dates"
                    selectionMode="range"
                    :manualInput="false"
                    placeholder="Add dates"
                    class="w-100"
                    :minDate="new Date()"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="p-2">
                  <label for="checkout" class="form-label text-uppercase small fw-bold"
                    >Checkout</label
                  >
                  <div class="date-placeholder p-2">
                    {{ checkoutDateDisplay }}
                  </div>
                </div>
              </div>
            </div>
            <div class="row g-0 border-top">
              <div class="col-12">
                <div class="p-2">
                  <label for="guests" class="form-label text-uppercase small fw-bold"
                    >Guests</label
                  >
                  <Dropdown
                    v-model="selectedGuests"
                    :options="guestOptions"
                    placeholder="Add guests"
                    class="w-100"
                  />
                </div>
              </div>
            </div>
          </div>

          <Button class="w-100 p-3 reserve-btn" label="Book Now" />

          <p class="text-center text-muted small mt-2 mb-0">You won't be charged yet</p>
        </div>

        <div class="text-center mt-3">
          <a href="#" class="text-muted text-decoration-underline">
            <i class="bi bi-flag me-2"></i>Report this listing
          </a>
        </div>
      </div>
    </div>

    <div class="mobile-booking-footer d-lg-none">
      <div class="footer-content">
        <div class="price-info">
          <span class="price-amount fw-bold">{{ formattedPrice }}</span>
          <span class="price-period text-muted"> day</span>
        </div>
        <Button class="reserve-btn-mobile" label="Book Now" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import Calendar from "primevue/calendar";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";

// --- Props ---
const props = defineProps({
  pricePerNight: {
    type: Number,
    required: true,
  },
  currency: {
    type: String,
    default: "THB",
  },
  parentContainer: {
    type: Object,
    default: null,
  },
});

// --- State ---
const dates = ref();
const selectedGuests = ref();
const guestOptions = ref([1, 2, 3, 4, "5+"]);
const wrapperRef = ref(null);
const panelRef = ref(null);
const stickyClass = ref("");

// --- Computed Properties ---
const formattedPrice = computed(() => {
  return new Intl.NumberFormat("th-TH", {
    style: "currency",
    currency: props.currency,
    minimumFractionDigits: 0,
  }).format(props.pricePerNight);
});

const checkoutDateDisplay = computed(() => {
  if (dates.value && dates.value[1]) {
    return dates.value[1].toLocaleDateString();
  }
  return "Add dates";
});

// --- Logic for Sticky Behavior (MODIFIED) ---
const handleScroll = () => {
  // *** ADDITION: Disable this logic on mobile screens ***
  if (window.innerWidth < 992) {
    // Reset styles if the screen is resized from desktop to mobile
    if (panelRef.value) {
      panelRef.value.style.width = null;
      panelRef.value.style.top = null;
    }
    stickyClass.value = "";
    return;
  }

  if (!wrapperRef.value || !panelRef.value || !props.parentContainer) return;

  const panelEl = panelRef.value;
  const parentRect = props.parentContainer.getBoundingClientRect();
  const wrapperRect = wrapperRef.value.getBoundingClientRect();
  const panelHeight = panelEl.offsetHeight;
  const topOffset = 20;
  const bottomOffset = 20;

  if (wrapperRect.top <= topOffset) {
    const wrapperWidth = wrapperRef.value.offsetWidth;
    panelEl.style.width = `${wrapperWidth}px`;

    if (parentRect.bottom < panelHeight + bottomOffset) {
      stickyClass.value = "is-sticky-bottom";
      panelEl.style.top = null;
    } else {
      stickyClass.value = "is-sticky-top";
      panelEl.style.top = `${topOffset}px`;
    }
  } else {
    stickyClass.value = "";
    panelEl.style.width = null;
    panelEl.style.top = null;
  }
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
/* --- STICKY PANEL STYLES (Desktop) --- */
.booking-panel-wrapper {
  position: relative;
}
.is-sticky-top {
  position: fixed;
}
.is-sticky-bottom {
  position: absolute;
  bottom: 0;
}
.price-amount {
  color: #222;
}
.reserve-btn {
  background: #6c757d;
  border: none;
  font-weight: 600;
}
.form-label {
  font-size: 0.65rem;
  margin-bottom: 0.1rem;
}
.date-placeholder {
  color: #6c757d;
}

/* --- NEW: MOBILE FOOTER STYLES --- */
.mobile-booking-footer {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: var(--section-bg-color);
  border-top: 1px solid var(--border-color);
  padding: 0.75rem 1rem;
  z-index: 1000;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.08);
}
.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.reserve-btn-mobile {
  background: #6c757d;
  border: none;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
}

/* --- PrimeVue Overrides --- */
:deep(.p-calendar .p-inputtext) {
  padding: 0.5rem;
  border: none !important;
  box-shadow: none !important;
}
:deep(.p-dropdown) {
  border: none !important;
  box-shadow: none !important;
}
@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

@keyframes pulse-delay {
  0%,
  85% {
    opacity: 1;
  }
  90%,
  100% {
    opacity: 1;
  }
}

.reserve-btn,
.reserve-btn-mobile {
  position: relative;
  background: #6c757d;
  background-size: 200% auto;
  color: white;
  font-weight: 600;
  border: none;
  border-radius: 0.5rem;
  transition: transform 0.2s ease;
  animation: pulse-delay 2 infinite;
}

.reserve-btn:hover,
.reserve-btn-mobile:hover {
  transform: scale(1.03);
}

/* shimmer overlay */
.reserve-btn::after,
.reserve-btn-mobile::after {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 200%;
  height: 100%;
  background: linear-gradient(
    120deg,
    rgba(255, 255, 255, 0.1) 0%,
    rgba(255, 255, 255, 0.5) 50%,
    rgba(255, 255, 255, 0.1) 100%
  );
  background-size: 200% 100%;
  border-radius: inherit;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
  animation: shimmer 2s linear infinite;
  animation-delay: 2;
}
</style>
