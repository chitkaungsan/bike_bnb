<template>
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">{{ t("hero.title") }}</h1>
      <p class="lead">{{ t("hero.subtitle") }}</p>

      <div class="search-bar-container" ref="searchBarRef">
        <form class="search-bar" @submit.prevent="performSearch">
          <div
            class="search-field"
            :class="{ active: activeField === 'location' }"
            @click="setActiveField('location')"
          >
            <label for="location">{{ t("hero.search.location_label") }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faLocationDot" class="field-icon" />
              <input
                type="text"
                id="location"
                class="form-control"
                :placeholder="t('hero.search.location_placeholder')"
              />
            </div>
          </div>

          <div
            class="search-field"
            :class="{ active: activeField === 'dates' }"
            @click="setActiveField('dates')"
          >
            <label for="dates">{{ t("hero.search.dates_label") }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faCalendarDays" class="field-icon" />
              <DatePicker
                v-model="dates"
                selectionMode="range"
                :placeholder="t('hero.search.dates_placeholder')"
                dateFormat="M dd"
                class="date-picker-control"
              />
              <span class="form-control-display">{{ formattedDates }}</span>
            </div>
          </div>

          <div
            class="search-field search-field-last"
            :class="{ active: activeField === 'category' }"
            @click.self="setActiveField('category')"
          >
            <div class="category-main-field" @click="setActiveField('category')">
              <label for="category">{{ t("hero.search.category_label") }}</label>
              <div class="input-wrapper">
                <font-awesome-icon :icon="faShapes" class="field-icon" />
                <span class="form-control-display">{{ selectedCategory.text }}</span>
              </div>
              <div v-if="activeField === 'category'" class="category-dropdown">
                <ul>
                  <li
                    v-for="cat in categories"
                    :key="cat.value"
                    @click="selectCategory(cat)"
                  >
                    {{ cat.text }}
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="search-main-field" @click="setActiveField('category')">
            <div class="input-wrapper">
              <button type="submit" class="btn-search-circular search-button mx-2">
                <font-awesome-icon :icon="faMagnifyingGlass" class="search-icon" />
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faLocationDot,
  faCalendarDays,
  faShapes, // Changed from faBicycle
  faMagnifyingGlass,
} from "@fortawesome/free-solid-svg-icons";
import DatePicker from "primevue/datepicker";

const { t } = useI18n();

// STATE MANAGEMENT
const activeField = ref(null);
const searchBarRef = ref(null);

// --- Dates State ---
const dates = ref();
const formattedDates = computed(() => {
  if (!dates.value || dates.value.length === 0) {
    return t("hero.search.dates_placeholder");
  }
  const [start, end] = dates.value;
  const options = { month: "short", day: "numeric" };
  const startDate = start.toLocaleDateString("en-US", options);
  if (!end) {
    return startDate;
  }
  const endDate = end.toLocaleDateString("en-US", options);
  return `${startDate} - ${endDate}`;
});

// --- Category State ---
const selectedCategory = ref({ text: t("hero.search.category_all"), value: "all" });
const categories = [
  { text: t("hero.search.category_all"), value: "all" },
  { text: t("hero.search.category_mountain"), value: "mountain" },
  { text: t("hero.search.category_road"), value: "road" },
  { text: t("hero.search.category_city"), value: "city" },
  { text: t("hero.search.category_electric"), value: "electric" },
];

// METHODS
const setActiveField = (field) => {
  activeField.value = field;
};

const selectCategory = (category) => {
  selectedCategory.value = category;
};

const performSearch = () => {
  console.log("Performing search with:", {
    location: document.getElementById("location").value,
    dates: dates.value,
    category: selectedCategory.value.value,
  });
  activeField.value = null;
};

// --- Click Outside Handler ---
const handleClickOutside = (event) => {
  if (searchBarRef.value && !searchBarRef.value.contains(event.target)) {
    activeField.value = null;
  }
};

onMounted(() => {
  document.addEventListener("mousedown", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("mousedown", handleClickOutside);
});
</script>

<style scoped>
/* Scoped styles ensure these changes don't affect other parts of your app. */
.hero-section {
  /* Using a generic background for demonstration. Replace with your actual banner. */
  background: url("/public/banner.jpg") no-repeat center center;
  background-size: cover;
  padding: 7rem 0;
  color: white;
  text-align: center;
  position: relative;
}

.hero-section::before {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  z-index: 1;
}

.hero-section .container {
  position: relative;
  z-index: 2;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.lead {
  font-size: 1.25rem;
  font-weight: 300;
}

/* =======================================
   SEARCH BAR - THEME-AWARE AND REDESIGNED
========================================== */
.search-bar-container {
  max-width: 850px;
  margin: 2.5rem auto 0;
}

.search-bar {
  display: flex;
  background-color: var(--section-bg-color); /* THEME: Changed */
  border-radius: 50px;
  box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
  width: 100%;
  height: 70px;
  transition: all 0.3s ease;
  border: 1px solid var(--border-color); /* THEME: Changed */
}

.search-field {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 0 25px;
  text-align: left;
  position: relative;
  cursor: pointer;
  border-radius: 50px;
  transition: background-color 0.2s ease, box-shadow 0.2s ease;
}

.search-field:not(:last-child)::after {
  content: "";
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 1px;
  height: 30px;
  background-color: var(--border-color); /* THEME: Changed */
}

.search-field:hover {
  background-color: rgba(0, 0, 0, 0.04); /* THEME: Using rgba for universal hover */
}
:root[data-bs-theme="dark"] .search-field:hover {
  background-color: rgba(255, 255, 255, 0.06); /* THEME: Dark mode hover */
}

.search-field.active {
  background-color: var(--section-bg-color); /* THEME: Changed */
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.search-field label {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--text-color); /* THEME: Changed */
  margin: 0;
  padding: 0;
}

.input-wrapper {
  display: flex;
  align-items: center;
  margin-top: 2px;
}

.field-icon {
  color: var(--primary-color); /* THEME: Changed */
  margin-right: 10px;
  font-size: 1rem;
}

.form-control,
.form-control-display {
  border: none;
  background: none;
  padding: 0;
  color: var(--light-text-color); /* THEME: Changed */
  font-size: 0.9rem;
  width: 100%;
  outline: none;
}
.form-control::placeholder {
  color: var(--light-text-color); /* THEME: Changed */
}
input.form-control {
  color: var(--text-color); /* THEME: Changed */
}

.form-control-display {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* --- DatePicker Customization --- */
.date-picker-control {
  position: absolute;
  inset: 0;
  opacity: 0;
  cursor: pointer;
}

/* --- Category Customization (REDESIGNED) --- */
.search-field-last {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10px 0 25px; /* Adjust padding for button */
  flex-grow: 1;
}

.category-main-field {
  flex-grow: 3;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative; /* For dropdown positioning */
}

.search-main-field {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative; /* For dropdown positioning */
}

.category-dropdown {
  position: absolute;
  top: 110%;
  left: -25px; /* Align with field start */
  background-color: var(--section-bg-color); /* THEME: Changed */
  border-radius: var(--border-radius-lg); /* THEME: Using variable */
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  padding: 0.5rem 0;
  min-width: 250px;
  z-index: 10;
  border: 1px solid var(--border-color); /* THEME: Changed */
}
.category-dropdown ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
.category-dropdown li {
  padding: 0.75rem 1.5rem;
  font-size: 0.9rem;
  color: var(--text-color); /* THEME: Changed */
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.category-dropdown li:hover {
  background-color: rgba(0, 0, 0, 0.04); /* THEME: Using rgba for universal hover */
}
:root[data-bs-theme="dark"] .category-dropdown li:hover {
  background-color: rgba(255, 255, 255, 0.06); /* THEME: Dark mode hover */
}

/* --- Search Button (REDESIGNED) --- */
.btn-search-circular {
  background: var(--primary-color); /* THEME: Changed */
  color: white;
  border: none;
  border-radius: 50%;
  height: 50px;
  width: 50px;
  min-width: 50px; /* Prevent squishing */
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}
.btn-search-circular:hover {
  background: var(--primary-hover-color); /* THEME: Changed */
}
.btn-search-circular .search-icon {
  font-size: 1.2rem;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  .search-bar {
    flex-direction: column;
    border-radius: var(--border-radius-lg); /* THEME: Using variable */
    padding: 1rem;
    height: auto;
    align-items: stretch;
    background-color: var(--section-bg-color); /* THEME: Changed */
    border: none;
  }
  .search-field {
    background-color: var(--background-color); /* THEME: Changed */
    border: 1px solid var(--border-color); /* THEME: Changed */
    border-radius: var(--border-radius-md); /* THEME: Using variable */
    margin-bottom: 0.75rem;
    padding: 0.75rem 1rem;
    height: 60px;
  }
  .search-field:not(:last-child)::after {
    display: none;
  }
  .search-field-last {
    padding: 0;
    margin-bottom: 0;
    border: none;
    background-color: transparent;
  }
  .search-field.active {
    box-shadow: 0 0 0 2px var(--primary-color);
  }
  .category-dropdown {
    width: 100%;
    left: 0;
    top: 105%;
  }

  /* Mobile layout for last field */
  .search-field-last {
    display: block;
    background-color: transparent;
  }
  .category-main-field {
    background-color: var(--background-color); /* THEME: Changed */
    border: 1px solid var(--border-color); /* THEME: Changed */
    border-radius: var(--border-radius-md); /* THEME: Using variable */
    padding: 0.75rem 1rem;
    height: 60px;
    width: 100%;
  }
  .btn-search-circular {
    width: 100%;
    border-radius: var(--border-radius-md); /* THEME: Using variable */
    margin-top: 0.75rem;
    height: 50px;
  }
}
</style>
