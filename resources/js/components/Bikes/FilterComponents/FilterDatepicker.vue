<template>
  <div class="filter-section text-start airbnb-date">
    <h5 class="filter-title">Pick your ride days</h5>

    <DatePicker
      v-model="dates"
      selectionMode="range"
      :manualInput="false"
      dateFormat="d M y"
      placeholder="When will you ride?"
      class="p-fluid airbnb-datepicker"
      @update:modelValue="updateSelectedDates"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import DatePicker from "primevue/datepicker";
import { useStore } from "vuex";

const store = useStore();
const route = useRoute();

const dates = ref(null);
const selectedDate = ref({
  start_date: null,
  end_date: null,
});

//  Load only from URL query when page loads
onMounted(() => {
  const { start_date, end_date } = route.query;

  if (start_date && end_date) {
    const start = new Date(start_date);
    const end = new Date(end_date);

    if (!isNaN(start) && !isNaN(end)) {
      dates.value = [start, end];
      selectedDate.value.start_date = start;
      selectedDate.value.end_date = end;
    }
  }

});

//  When user changes date in picker, update local state only
function updateSelectedDates(value) {
  if (Array.isArray(value) && value.length === 2) {
    selectedDate.value.start_date = value[0];
    selectedDate.value.end_date = value[1];
  } else {
    selectedDate.value.start_date = null;
    selectedDate.value.end_date = null;
  }

  store.dispatch("homeFilter/updateSelectedDate", selectedDate.value);
}

//  Helper for displaying readable dates
const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};
</script>


<style scoped>
/* 🏝️ Container */
.airbnb-date {
  font-family: "Inter", sans-serif;
}

/* 🌿 Airbnb compact input style */
:deep(.p-inputtext) {
  width: 100%;
  border-radius: 12px !important;
  padding: 0.6rem 0.8rem !important;
  font-size: 0.9rem !important;
  font-weight: 500;
  border: 1px solid var(--border-color) !important;
  background-color: var(--section-bg-color) !important;
  color: var(--text-color) !important;
  transition: all 0.2s ease;
}

:deep(.p-inputtext:hover) {
  border-color: var(--primary-color) !important;
}

:deep(.p-inputtext:focus) {
  border-color: var(--primary-color) !important;
  box-shadow: 0 0 0 3px rgba(99, 193, 162, 0.25) !important;
}

/* 📅 Calendar Panel (Airbnb vibe) */
:deep(.p-datepicker-panel) {
  border-radius: 16px !important;
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
  border: none !important;
  padding: 0.75rem 1rem;
}

/* 🗓️ Date cell style */
:deep(.p-datepicker-calendar td > span) {
  width: 2.4rem;
  height: 2.4rem;
  line-height: 2.4rem;
  border-radius: 50%;
  font-weight: 500;
  transition: all 0.2s ease;
}

/* Hover date */
:deep(.p-datepicker-calendar td > span:hover) {
  background: rgba(99, 193, 162, 0.15);
  color: var(--primary-color);
}

/* Selected date range style */
:deep(.p-highlight) {
  background: var(--primary-color) !important;
  color: #fff !important;
}

/* Date range middle highlight (Airbnb style) */
:deep(.p-highlight-range) {
  background: rgba(99, 193, 162, 0.15) !important;
}

/* 🌙 Dark mode tune */
:root[data-bs-theme="dark"] :deep(.p-datepicker-panel) {
  box-shadow: 0 12px 24px rgba(255, 255, 255, 0.05);
}

/* 🌺 Range text */
.selected-range {
  margin-top: 0.6rem;
  text-align: left;
}

.range-text {
  font-size: 0.85rem;
  color: var(--text-color);
  background: rgba(99, 193, 162, 0.1);
  padding: 0.4rem 0.75rem;
  border-radius: 8px;
  display: inline-block;
  font-weight: 500;
}

/* ✨ Smooth fade for range display */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
/* 🖥️ 1600px – 1920px (Large desktop / 27"–32") */
@media (min-width: 1600px) and (max-width: 1920px) {
  .filter-title {
    font-size: 1.1rem;
    font-weight: bold;
  }
  :deep(.p-inputtext) {
    border-radius: 12px !important;
    padding: 0.45rem 0.75rem !important;
    font-size: 0.9rem !important;
    font-weight: 500;
  }
}

/* 🖥️ 1440px – 1599px (Standard laptop / desktop) */
@media (min-width: 1440px) and (max-width: 1599px) {
  .filter-title {
    font-size: 1.0rem;
    font-weight: bold;
  }
  :deep(.p-inputtext) {
    border-radius: 12px !important;
    padding: 0.4rem 0.6rem !important;
    font-size: 0.8rem !important;
    font-weight: 500;
  }
}

/* 💻 1300px – 1439px (Mid laptop) */
@media (min-width: 1300px) and (max-width: 1439px) {
  .filter-title {
    font-size: 0.95rem;
    font-weight: bold;
  }
  :deep(.p-inputtext) {
    border-radius: 10px !important;
    padding: 0.35rem 0.55rem !important;
    font-size: 0.75rem !important;
    font-weight: 500;
  }
}

/* 💻 1200px – 1299px (Small laptop) */
@media (min-width: 1200px) and (max-width: 1299px) {
  .filter-title {
    font-size: 0.9rem;
    font-weight: bold;
  }
  :deep(.p-inputtext) {
    border-radius: 10px !important;
    padding: 0.3rem 0.5rem !important;
    font-size: 0.7rem !important;
    font-weight: 500;
  }
}

/* 💻 992px – 1199px (Tablet landscape / small MacBook Air) */
@media (min-width: 992px) and (max-width: 1199px) {
  .filter-title {
    font-size: 0.85rem;
    font-weight: bold;
  }
  :deep(.p-inputtext) {
    border-radius: 8px !important;
    padding: 0.25rem 0.45rem !important;
    font-size: 0.65rem !important;
    font-weight: 500;
  }
}

</style>
