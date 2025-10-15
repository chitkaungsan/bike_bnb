<template>
  <div class="container mx-auto flex justify-center">
    <div class="card stepper-container w-full max-w-[50rem]">
      <Stepper value="1" class="custom-stepper">
        <StepList>
          <Step value="1">Rent Information</Step>
          <Step value="2">Payment</Step>
          <Step value="3">Confirm</Step>
        </StepList>

        <StepPanels>
          <StepPanel v-slot="{ activateCallback }" value="1">
            <div class="step-content">
              <RenterInfo  :bike="bike" ref="renterRef" />
            </div>
            <div class="step-nav">
              <div></div>
              <Button
                label="Next"
                icon="pi pi-arrow-right"
                iconPos="right"
                @click="activateCallback('2')"
                class="step-btn"
              />
            </div>
          </StepPanel>

          <StepPanel v-slot="{ activateCallback }" value="2">
            <div class="step-content">
              Content II
            </div>
            <div class="step-nav">
              <Button
                label="Back"
                severity="secondary"
                icon="pi pi-arrow-left"
                @click="activateCallback('1')"
                class="step-btn-secondary"
              />
              <Button
                label="Next"
                icon="pi pi-arrow-right"
                iconPos="right"
                @click="activateCallback('3')"
                class="step-btn"
              />
            </div>
          </StepPanel>

          <StepPanel v-slot="{ activateCallback }" value="3">
            <div class="step-content">
              Content III
            </div>
            <div class="step-nav">
              <Button
                label="Back"
                severity="secondary"
                icon="pi pi-arrow-left"
                @click="activateCallback('2')"
                class="step-btn-secondary"
              />
              <Button
                label="Confirm Booking"
                severity="secondary"
                icon="pi pi-check"
                @click="submit"
                class="step-btn"
              />
            </div>
          </StepPanel>
        </StepPanels>
      </Stepper>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted,ref } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import Stepper from 'primevue/stepper';
import StepList from 'primevue/steplist';
import StepPanels from 'primevue/steppanels';
import Step from 'primevue/step';
import StepPanel from 'primevue/steppanel';
import Button from 'primevue/button';
import RenterInfo from './RenterInfo.vue';
import renter from "../../router/renter";

const store = useStore();
const route = useRoute();
const renterRef = ref(null);
const bike = computed(() => store.getters['bikes/bike_details']);

const submit =async () => {
  renterRef.value.saveRenterInfo();
  console.log('renterRef', renterRef.value.user_name);
  // await store.dispatch('booking/submitBooking', {
    // bike_id: bike.value.id,
    // user_name: renterRef.value.user_name,
    // user_phone: renterRef.value.user_phone,
    // user_email: renterRef.value.user_email,
  // });
};
onMounted(async () => {
  const bike_id = Number(route.query.bike_id);
  await store.dispatch('bikes/getBike', bike_id);
});
</script>

<style scoped>
/*
  NOTE: No modifications are needed here for dark mode.
  This component correctly uses CSS variables (e.g., var(--section-bg-color)).
  The global CSS file handles the theme switching by redefining these variables
  when the `data-bs-theme="dark"` attribute is active on the root element.
*/

/* Container card */
.stepper-container {
  padding: 1rem;
  border-radius: var(--border-radius-lg);
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s, color 0.3s;
}

/* Step content */
.step-content {
  flex: 1;
  min-height: 12rem;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 2px dashed var(--border-color);
  border-radius: var(--border-radius-md);
  background-color: var(--background-color);
  color: var(--text-color);
  font-weight: 500;
  padding: 0.5rem;
}

/* Navigation buttons */
.step-nav {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.step-btn {
  background-color: var(--primary-color);
  color: var(--text-color);
  border-radius: var(--border-radius-md);
  width: 6rem;
}

.step-btn:hover {
  background-color: var(--primary-hover-color);
}

.step-btn-secondary {
  background-color: transparent;
  color: var(--text-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  width: 6rem;
}

.step-btn-secondary:hover {
  background-color: var(--border-color);
}

/* PrimeVue Stepper adjustments */
.custom-stepper {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
}

.custom-stepper .p-step {
  color: var(--text-color);
}

.custom-stepper .p-step .p-step-title {
  color: var(--text-color);
}

.custom-stepper .p-steppanel {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-md);
  border: 1px solid var(--border-color);
  padding: 1rem;
  color: var(--text-color);
}

/* Responsive */
@media (max-width: 640px) {
  .stepper-container {
    padding: 0.75rem;
  }
  .step-btn,
  .step-btn-secondary {
    width: 100%;
  }
}
</style>