<template>
  <div class="booking-panel-wrapper" ref="wrapperRef">
    <div class="booking-panel" ref="panelRef" :class="stickyClass">
      <div class="p-4 shadow rounded-4 border">
        <div class="d-flex align-items-center mb-3">
          <span class="price-amount fw-bold fs-4">{{ formattedPrice }}</span>
          <span class="price-period ms-1 text-muted">night</span>
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

        <Button class="w-100 p-3 reserve-btn" label="Reserve" />

        <p class="text-center text-muted small mt-2 mb-0">You won't be charged yet</p>
      </div>

      <div class="text-center mt-3">
        <a href="#" class="text-muted text-decoration-underline">
          <i class="bi bi-flag me-2"></i>Report this listing
        </a>
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

// --- Logic for Sticky Behavior (FIXED) ---
const handleScroll = () => {
  if (!wrapperRef.value || !panelRef.value || !props.parentContainer) return;

  const panelEl = panelRef.value;
  const parentRect = props.parentContainer.getBoundingClientRect();
  const wrapperRect = wrapperRef.value.getBoundingClientRect();
  const panelHeight = panelEl.offsetHeight;
  const topOffset = 20; // Margin from top
  const bottomOffset = 20; // Margin from bottom

  if (wrapperRect.top <= topOffset) {
    // **FIX**: Set the width dynamically before changing position
    // This prevents the panel from resizing when it becomes 'fixed'.
    const wrapperWidth = wrapperRef.value.offsetWidth;
    panelEl.style.width = `${wrapperWidth}px`;

    if (parentRect.bottom < panelHeight + bottomOffset) {
      stickyClass.value = "is-sticky-bottom";
      // When stuck to the bottom, the 'top' style is not needed.
      panelEl.style.top = null;
    } else {
      stickyClass.value = "is-sticky-top";
      // Apply the top offset directly as an inline style.
      panelEl.style.top = `${topOffset}px`;
    }
  } else {
    stickyClass.value = "";
    // **FIX**: Reset inline styles when not sticky to return to normal behavior.
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
.booking-panel-wrapper {
  position: relative;
  min-height: 1500px;
  /* Removed min-height, as it's not needed for the logic */
}

/* State 2: Sticking to the top of the screen */
.is-sticky-top {
  position: fixed;
  /* The 'top' and 'width' properties are now set via inline styles */
}

/* State 3: Sticking to the bottom of the parent container */
.is-sticky-bottom {
  position: absolute;
  bottom: 0;
}

/* Component Styling */
.price-amount {
  color: #222;
}
.reserve-btn {
  background: linear-gradient(to right, #e61e4d 0%, #e31c5f 50%, #d70466 100%);
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

/* PrimeVue Overrides */
:deep(.p-calendar .p-inputtext) {
  padding: 0.5rem;
  border: none !important;
  box-shadow: none !important;
}
:deep(.p-dropdown) {
  border: none !important;
  box-shadow: none !important;
}
</style>
