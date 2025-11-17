<template>
  <div class="container mx-auto py-6 px-4">
    <Toast />
    <div class="booking-container card shadow-lg border-0">
      <div class="stepper-header mb-4">
        <h3 class="booking-title text-center mb-3">Complete Your Booking</h3>
        <div class="steps d-flex justify-content-center">
          <div class="step-item" :class="{ 'active': currentStep === 1, 'completed': currentStep > 1 }">
            <div class="step-counter">1</div>
            <div class="step-name">Rider Details</div>
          </div>
          <div class="step-connector"></div>
          <div class="step-item" :class="{ 'active': currentStep === 2 }">
            <div class="step-counter">2</div>
            <div class="step-name">Payment</div>
          </div>
        </div>
      </div>

      <div class="py-3">
        <div v-show="currentStep === 1">
          <RenterInfo :bike="bike" ref="renterInfoRef" @update:renterData="handleRenterDataUpdate" />
        </div>

        <div v-if="currentStep === 2" class="row">
          <div class="col-lg-7 col-md-7 col-sm-12 mb-3">
            <div class="booking-panel h-100">
              <h5 class="panel-title">Confirm Your Details</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><strong>Bike:</strong> <span>{{ bike.title
                    }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Full Name:</strong> <span>{{
                    renterData.user_name }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Email:</strong> <span>{{
                    renterData.user_email }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Phone:</strong> <span>{{
                    renterData.user_phone }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Rental Dates:</strong> <span>{{
                  formatDateRange(renterData.selectedDates) }}</span></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-5 col-md-5 col-sm-12 mb-3">
            <div class="booking-panel d-flex flex-column h-100">
              <h5 class="panel-title">💳 Payment Summary</h5>
              <div class="summary-box border rounded-3 p-3 mb-3">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Price per day:</span> <span
                    class="fw-semibold">{{ formatPrice(bike.price) }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Days:</span> <span
                    class="fw-semibold">{{ renterData.days }}</span></div>
                <div class="d-flex justify-content-between border-top pt-2 mt-2"><span class="fw-bold">Total:</span>
                  <span class="fw-bold text-success fs-5">{{ formatPrice(renterData.totalPrice) }}</span></div>
              </div>
              <div class="payment-method mt-auto">
                <label class="form-label fw-semibold">Select Payment Method</label>
                <select v-model="paymentMethod" class="form-select custom-input">
                  <option value="cash">Cash on Delivery</option>
                  <option value="credit">Credit / Debit Card</option>
                  <option value="wallet">E-Wallet</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <Button v-if="currentStep > 1" label="Back" icon="pi pi-arrow-left" class="p-button-secondary"
          @click="prevStep" />
        <div v-else></div> <Button v-if="currentStep < 2" label="Next to Payment" icon="pi pi-arrow-right"
          iconPos="right" class="step-btn" @click="nextStep" />
        <Button v-if="currentStep === 2" class="step-btn" :disabled="loading" @click="submitBooking">
          <template #default>
            <span v-if="!loading">
              <i class="pi pi-check"></i>
              Confirm Booking
            </span>
            <span v-else class="spinner">
              <i class="pi pi-spin pi-spinner"></i>
              Confirming...
            </span>
          </template>
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";
import { useToast } from 'primevue/usetoast';
import Button from "primevue/button";
import RenterInfo from "./RenterInfo.vue";

// Component State
const currentStep = ref(1);
const renterInfoRef = ref(null);
const paymentMethod = ref("cash");
const toast = useToast();
const loading = ref(false);
// Data Management
const store = useStore();
const route = useRoute();
const router = useRouter();
const bike = computed(() => store.getters["bikes/bike_details"] || {});

const renterData = ref({});

// Event handler to get data from child
const handleRenterDataUpdate = (data) => {
  renterData.value = { ...data };
};

// Stepper Navigation
const nextStep = async () => {
  if (currentStep.value === 1) {
    const isValid = await renterInfoRef.value.validateRenterInfo();
    if (isValid) {
      currentStep.value++;
    }
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
};

// Final Submission
const submitBooking = async () => {
  loading.value = true;
  const bookingPayload = {
    bike_id: bike.value.id,
    payment_method: paymentMethod.value,
    ...renterData.value
  };

  try {
    const res = await store.dispatch("booking/createBooking", bookingPayload);
    toast.add({
      severity: 'success',
      summary: 'Booking Confirmed',
      detail: 'Your bike booking was successful!',
      life: 3000
    });
    console.log('Booking successful:', res.booking.id);
    router.push({ name: 'booking.success', params: { id: res.booking.id } });
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Booking Failed',
      detail: err.message,
      life: 5000
    });
    console.error('Booking failed:', err.message);
  } finally {
    loading.value = false;
  }
};

// Formatting Helpers
const formatPrice = (value) => {
  if (value === undefined || value === null) return "0 THB";
  return new Intl.NumberFormat("en-TH", {
    style: "currency",
    currency: "THB",
    minimumFractionDigits: 0,
  }).format(value);
};

const formatDateRange = (dates) => {
  if (!dates || dates.length < 2) return 'N/A';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  const start = new Date(dates[0]).toLocaleDateString('en-US', options);
  const end = new Date(dates[1]).toLocaleDateString('en-US', options);
  return `${start} - ${end}`;
};

onMounted(async () => {
  const bike_id = Number(route.query.bike_id);
  if (bike_id) {
    await store.dispatch("bikes/getBike", bike_id);
  }
});
</script>

<style scoped>
.booking-container {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
  padding: 2rem;
  color: var(--text-color);
}

.booking-panel {
  background-color: var(--background-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  padding: 1.5rem;
}

.booking-title {
  color: var(--primary-color);
  font-weight: 700;
}

.panel-title {
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: var(--text-color);
}

.custom-input {
  background-color: var(--background-color);
  color: var(--text-color);
  border: 1px solid var(--border-color);
}

.step-btn {
  background-color: var(--primary-color);
  color: #fff;
  border: none;
}

.step-btn:hover {
  background-color: var(--primary-hover-color);
}

.list-group-item {
  background-color: transparent;
  border-color: var(--border-color);
  padding-left: 0;
  padding-right: 0;
}

/* Stepper Header Styles */
.stepper-header .steps {
  align-items: center;
}

.step-item {
  display: flex;
  align-items: center;
  flex-direction: column;
  color: var(--light-text-color);
}

.step-counter {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 2px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-bottom: 0.5rem;
  background-color: var(--background-color);
  transition: all 0.3s;
}

.step-name {
  font-size: 0.9rem;
  font-weight: 500;
}

.step-item.active .step-counter,
.step-item.completed .step-counter {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  color: #fff;
}

.step-item.active .step-name,
.step-item.completed .step-name {
  color: var(--primary-color);
  font-weight: bold;
}

.step-connector {
  flex-grow: 1;
  height: 2px;
  background-color: var(--border-color);
  margin: 0 1rem;
  max-width: 100px;
  transform: translateY(-1rem);
}
.spinner {
  display: flex;
  align-items: center;
  gap: 6px;
}
.pi-spinner {
  font-size: 1rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}

</style>