<template>
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">{{ t("hero.title") }}</h1>
      <p class="lead">{{ t("hero.subtitle") }}</p>

      <div class="search-bar-container" ref="searchBarRef">
        <form class="search-bar" @submit.prevent="performSearch">
          <div class="search-field" :class="{ active: activeField === 'location' }"
            @mousedown.stop="setActiveField('location')">
            <label for="location">{{ t("hero.search.location_label") }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faLocationDot" class="field-icon" />
              <input type="text" id="location" class="form-control" v-model="locationQuery" @focus="onFocusLocation"
                @input="showLocationDropdown = true" :placeholder="t('hero.search.location_placeholder')" />
              <button v-if="locationQuery" type="button" class="btn-clear" @click="clearLocation">
                &times;
              </button>
            </div>


            <!-- Location Dropdown -->
            <div v-if="showLocationDropdown && filteredLocations && filteredLocations.length" class="location-dropdown">
              <!-- Nearby Section -->
              <div v-if="filteredLocations.some(l => l.type === 'nearby')" class="dropdown-section">
                <h6 class="dropdown-section-title">{{ t('hero.search.nearby') || 'Nearby' }}</h6>
                <ul>
                  <li v-for="loc in filteredLocations.filter(l => l.type === 'nearby')" :key="loc.name"
                    @click="selectLocation(loc)" class="location-item">
                    <img :src="loc.image" alt="" class="location-img" />
                    <div class="location-info">
                      <strong>{{ loc.name }}</strong>
                      <p>{{ loc.description }}</p>
                    </div>
                  </li>
                </ul>
              </div>

              <!-- Other Locations -->
              <div class="dropdown-section">
                <h6 class="dropdown-section-title">{{ t('hero.search.other_locations') || 'Other locations' }}</h6>
                <ul>
                  <li v-for="loc in filteredLocations.filter(l => !l.type)" :key="loc.name" @click="selectLocation(loc)"
                    class="location-item">
                    <img :src="loc.image" alt="" class="location-img" loading="lazy" />
                    <div class="location-info">
                      <strong>{{ loc.name }}</strong>
                      <p>{{ loc.description }}</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

          </div>


          <div class="search-field" :class="{ active: activeField === 'dates' }" @click="setActiveField('dates')">
            <label for="dates">{{ t("hero.search.dates_label") }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faCalendarDays" class="field-icon" />
              <DatePicker v-model="dates" selectionMode="range" :placeholder="t('hero.search.dates_placeholder')"
                dateFormat="M dd" class="date-picker-control" />
              <span class="form-control-display">{{ formattedDates }}</span>

              <button v-if="dates && dates.length" type="button" class="btn-clear" @click="clearDates">
                &times;
              </button>
            </div>

          </div>

          <div class="search-field search-field-last" :class="{ active: activeField === 'category' }"
            @click.self="setActiveField('category')">
            <div class="category-main-field" @click="setActiveField('category')">
              <label for="category">{{ t("hero.search.category_label") }}</label>
              <div class="input-wrapper">
                <font-awesome-icon :icon="faShapes" class="field-icon" />
                <span class="form-control-display">{{ selectedCategory.text }}</span>
                <button v-if="selectedCategory.value !== 'all'" type="button" class="btn-cat mx-1"
                  @click="clearCategory">
                  &times;
                </button>
              </div>

              <div class="category-dropdown" v-if="activeField === 'category'">
                <ul>
                  <li v-for="cat in categories" :key="cat.value" @mousedown.stop.prevent="selectCategory(cat)">
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
import "../../../css/hero_section.css";
import { useI18n } from "vue-i18n";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faLocationDot,
  faCalendarDays,
  faShapes, // Changed from faBicycle
  faMagnifyingGlass,
} from "@fortawesome/free-solid-svg-icons";
import { useStore } from "vuex";
import DatePicker from "primevue/datepicker";

const { t } = useI18n();
const store = useStore();
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


const locationQuery = ref("");
const showLocationDropdown = ref(false);
const locations = computed(() => store.getters["cities/allCities"]);
// Filter suggestions based on user input
const filteredLocations = computed(() => {
  if (!locationQuery.value) return locations.value;
  return locations.value.filter((l) =>
    l.name.toLowerCase().includes(locationQuery.value.toLowerCase())
  );
});

const selectLocation = (loc) => {
  locationQuery.value = loc.name;
  showLocationDropdown.value = false;
};

const onFocusLocation = () => {
  showLocationDropdown.value = true;
  setActiveField("location");
};

// METHODS
const setActiveField = (field) => {
  activeField.value = field;
};


const selectCategory = (category) => {
  selectedCategory.value = category;
  activeField.value = null;
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
    showLocationDropdown.value = false;
  }
};

const clearDates = () => {
  dates.value = [];
};
const clearLocation = () => {
  locationQuery.value = "";
  showLocationDropdown.value = false;
};
const clearCategory = () => {
  selectedCategory.value = { text: t("hero.search.category_all"), value: "all" };
};

onMounted(async () => {
  document.addEventListener("mousedown", handleClickOutside);

  await store.dispatch("cities/fetchCities");
});

onBeforeUnmount(() => {
  document.removeEventListener("mousedown", handleClickOutside);
});
</script>
<style scoped>
.hero-section {
  position: relative;
  background-color: var(--background-color);
  padding: 5rem 0;
}

.content {
  position: relative;
  z-index: 0;
}
</style>