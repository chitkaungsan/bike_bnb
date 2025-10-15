<template>
  <div class="container-fluid">
    <div class="row">
      <!-- Left: Bike Info + DateSelector -->
      <div class="col-sm-7 col-md-7 col-lg-7">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              {{ bike.title }} - {{ bike.model }}
            </h5>
            <div class="row">
              <div class="col-md-5 col-sm-5 col-lg-5">
                <Image
                  :src="bike.photo"
                  class="img-fluid rounded-start p-2 bike-image"
                  alt="Bike Image"
                  width="100%"
                  preview
                />
              </div>
              <div class="col-md-7 col-sm-7 col-lg-7">
                <DateSelector v-model="selectedDates" :bookedDates="bookedDates"  />
                <div class="mt-3 p-3 border border-dashed rounded d-flex justify-content-between align-items-center shadow-sm">
                    <p class="mb-0 fw-semibold text-secondary">Price Per Day</p>
                    <p class="mb-0 fw-bold text-success">{{ bike.price }} THB</p>
                </div>
                <div class="mt-3 p-3 border border-dashed rounded d-flex justify-content-between align-items-center shadow-sm">
                    <p class="mb-0 fw-semibold text-secondary">Days</p>
                    <p class="mb-0 fw-bold text-success">{{ days }}</p>
                </div>

                <div class="mt-3 p-3 border border-dashed rounded d-flex justify-content-between align-items-center shadow-sm">
                    <p class="mb-0 fw-semibold text-secondary">Total</p>
                    <p class="mb-0 fw-bold text-success">{{ formatPrice(totalPrice) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: User Form -->
      <div class="col-sm-5 col-md-5 col-lg-5">
        <div class="card">
          <div class="card-body">
              <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input
                  type="text"
                  v-model="user_name"
                  class="form-control custom-input"
                  id="fullName"
                  placeholder="Enter your full name"
                />
              </div>
              <div class="mb-3">
                <label for="fullName" class="form-label">Phone</label>
                <vue-tel-input v-model="user_phone"></vue-tel-input>
              </div>
              <div class="mb-3">
                <label for="fullName" class="form-label">E-mail</label>
                <input
                  type="text"
                  v-model="user_email"
                  class="form-control custom-input"
                  id="fullName"
                  placeholder="Enter your full name"
                />
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import Image from "primevue/image";
import DateSelector from "../BookingStep/DateSelector.vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { reactive } from "vue";

const selectedDates = ref([]);
const route = useRoute();
const store = useStore();
const days = ref(0);

const bookedDates = ref([
  new Date(2025, 9, 20),
  new Date(2025, 9, 25),
  new Date(2025, 10, 1),
]);

defineProps({
  bike: { type: Object, required: true },
});
const user_name = ref("");
const user_phone = ref("");
const user_email = ref("");
const saveRenterInfo = () => {
//   store.dispatch("renter/saveRenterInfo", {
//     user_name: user_name.value,
//     user_phone: user_phone.value,
//   });
    console.log("Renter Info Saved:", {
        user_name: user_name.value,
        user_phone: user_phone.value,
        user_email: user_email.value,
    });
}
//  Format price function
const formatPrice = (value) => {
  if (!value) return "0";
  return new Intl.NumberFormat("en-TH", {
    style: "currency",
    currency: "THB",
    minimumFractionDigits: 0,
  }).format(value);
};

// Computed for total price
const totalPrice = computed(() => days.value * (route.query.price_per_day || 0));

onMounted(async() => {
  const start_date = route.query.start_date;
  const end_date = route.query.end_date;

  if (start_date && end_date) {
    const [sYear, sMonth, sDay] = start_date.split("-").map(Number);
    const [eYear, eMonth, eDay] = end_date.split("-").map(Number);

    selectedDates.value = [
      new Date(sYear, sMonth - 1, sDay),
      new Date(eYear, eMonth - 1, eDay),
    ];
  }

  days.value = route.query.days ? parseInt(route.query.days) : 0;
  let userData = await store.dispatch('auth/fetchUser');
    user_name.value = userData.name || "";
    user_phone.value = userData.phone || "";
    user_email.value = userData.email || "";
});

defineExpose({
  saveRenterInfo,
    user_name,
});
</script>

<style scoped>
.custom-card {
  margin-top: 20px;
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  color: var(--text-color);
  border-radius: var(--border-radius-md);
  transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}

.bike-image {
  background-color: var(--background-color);
  transition: background-color 0.3s;
}

/* Input styles */
.custom-input {
  background-color: var(--background-color);
  color: var(--text-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}

.custom-input::placeholder {
  color: var(--light-text-color);
}

/* Responsive margin adjustments */
@media (min-width: 768px) {
  .custom-card {
    margin-top: 40px;
  }
}

@media (min-width: 992px) {
  .custom-card {
    margin-top: 50px;
  }
}
</style>
