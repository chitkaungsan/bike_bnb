<template>
  <div class="booking-container card shadow-lg border-0">
    <div class="row">
      <div class="col-lg-7 col-md-7 col-sm-12 mb-3">
        <div class="booking-panel">
          <div class="card-body p-0"> <h5 class="panel-title">
              {{ bike.title }} - {{ bike.model }}
            </h5>
            <div class="row">
              <div class="col-md-5">
                <Image
                  :src="bike.photo"
                  alt="Bike Image"
                  width="100%"
                  class="img-fluid rounded-start p-2 bike-image"
                  preview
                />
              </div>
              <div class="col-md-7">
                <DateSelector
                  v-model="selectedDates"
                  :bookedDates="bookedDates"
                />
                <div
                  v-for="(item, index) in priceSummary"
                  :key="index"
                  class="mt-3 p-3 border border-dashed rounded d-flex justify-content-between align-items-center shadow-sm summary-box"
                >
                  <p class="mb-0 fw-semibold text-secondary">{{ item.label }}</p>
                  <p class="mb-0 fw-bold text-success">{{ item.value }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5 col-md-5 col-sm-12">
        <div class="booking-panel">
          <h5 class="panel-title">Rider Information</h5>
          <div class="card-body p-0">
            <form @submit.prevent>
              <div class="mb-3">
                <label for="user_name" class="form-label">Full Name</label>
                <input
                  id="user_name"
                  v-model="user_name"
                  type="text"
                  class="form-control custom-input"
                  placeholder="Enter your full name"
                />
                <small v-if="errors.user_name" class="text-danger">{{ errors.user_name }}</small>
              </div>
              <div class="mb-3">
                <label for="user_phone" class="form-label">Phone</label>
                <vue-tel-input
                  id="user_phone"
                  v-model="user_phone"
                />
                <small v-if="errors.user_phone" class="text-danger">{{ errors.user_phone }}</small>
              </div>
              <div class="mb-3">
                <label for="user_email" class="form-label">Email</label>
                <input
                  id="user_email"
                  v-model="user_email"
                  type="email"
                  class="form-control custom-input"
                  placeholder="Enter your email"
                />
                <small v-if="errors.user_email" class="text-danger">{{ errors.user_email }}</small>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// --- SCRIPT IS UNCHANGED, AS REQUESTED ---
import { ref, onMounted, computed, watch } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import Image from "primevue/image";
import DateSelector from "../BookingStep/DateSelector.vue";

const props = defineProps({
  bike: { type: Object, required: true },
});

const emit = defineEmits(['update:renterData']);

const selectedDates = ref([]);
const bookedDates = ref([]);
const days = ref(0);
const route = useRoute();
const store = useStore();

const bike_price = ref(props.bike.price || 0);
const user_id = ref("");
const user_name = ref("");
const user_phone = ref("");
const user_email = ref("");
const errors = ref({});

const getRenterData = () => ({
  user_id: user_id.value,
  user_name: user_name.value,
  user_phone: user_phone.value,
  user_email: user_email.value,
  selectedDates: selectedDates.value,
  daily_rate: bike_price.value,
  totalPrice: totalPrice.value,
  days: days.value,
});

watch(selectedDates, (newDates) => {
  if (newDates && newDates.length === 2 && newDates[0] && newDates[1]) {
    const [start, end] = newDates;
    if (end < start) {
        days.value = 0;
        return;
    };
    const diffTime = Math.abs(end - start);
    days.value = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
  } else {
    days.value = 0;
  }
}, { deep: true });

const totalPrice = computed(() => days.value * (props.bike.price || 0));

watch([user_name, user_phone, user_email, selectedDates, totalPrice, days], () => {
  emit('update:renterData', getRenterData());
}, { deep: true, immediate: true });

const validateRenterInfo = async () => {
  errors.value = {};
  if (!user_name.value) errors.value.user_name = "Full name is required.";
  if (!user_phone.value) errors.value.user_phone = "Phone number is required.";
  if (!user_email.value) errors.value.user_email = "Email is required.";
  else if (!/\S+@\S+\.\S+/.test(user_email.value))
    errors.value.user_email = "Invalid email format.";
  
  if(days.value < 1) {
    console.error("Date range is not selected.");
    return false;
  }

  return Object.keys(errors.value).length === 0;
};

const formatPrice = (val) =>
  new Intl.NumberFormat("en-TH", {
    style: "currency",
    currency: "THB",
    minimumFractionDigits: 0,
  }).format(val || 0);

const priceSummary = computed(() => [
  { label: "Price Per Day", value: formatPrice(props.bike.price) },
  { label: "Days", value: days.value },
  { label: "Total", value: formatPrice(totalPrice.value) },
]);

onMounted(async () => {
  const start_date = route.query.start_date;
  const end_date = route.query.end_date;

  if (start_date && end_date) {
    selectedDates.value = [new Date(start_date), new Date(end_date)];
  }

  const userData = await store.dispatch("auth/fetchUser");
  if (userData) {
      user_id.value = userData.id || "";
      user_name.value = userData.name || "";
      user_phone.value = userData.phone || "";
      user_email.value = userData.email || "";
      bike_price.value = props.bike.price || 0;

  }
});

function getDatesBetween(start, end) {
  const dateArray = [];
  let currentDate = new Date(start);
  const endDate = new Date(end);

  while (currentDate <= endDate) {
    dateArray.push(new Date(currentDate));
    currentDate.setDate(currentDate.getDate() + 1);
  }

  return dateArray;
}

onMounted( async () => {
  // Example booked ranges
  let bike_id = route.query.bike_id;
  const res = await store.dispatch("booking/getBookDateWithBikeId", bike_id);

  let bookings = res
      bookings.forEach((booking) => {
      const dates = getDatesBetween(booking.start, booking.end);
      bookedDates.value.push(...dates);
    });
});

defineExpose({
  validateRenterInfo,
});
</script>

<style scoped>
/* ✅ ADDED: New styles for the booking panel design */
.booking-container {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
  padding: 2rem;
  color: var(--text-color);
  transition: background-color 0.3s, color 0.3s;
}

.booking-panel {
  background-color: var(--background-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  padding: 1.5rem;
  width: 100%;
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

.summary-box {
  background-color: var(--section-bg-color) !important;
}
/* --- End of new styles --- */

.bike-image {
  background-color: #fff;
  border: 1px solid var(--border-color);
}
.custom-input {
  background-color: var(--background-color);
  color: var(--text-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
}
.custom-input::placeholder {
  color: var(--light-text-color);
}
.text-danger {
  font-size: 0.85rem;
}
</style>