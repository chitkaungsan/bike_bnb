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
                @input="showLocationDropdown = true" :placeholder="t('hero.search.location_placeholder')" style="color: ;" />
            </div>


            <!-- Location Dropdown -->
            <div v-if="showLocationDropdown && filteredLocations.length" class="location-dropdown">
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
            </div>
          </div>

          <div class="search-field search-field-last" :class="{ active: activeField === 'category' }"
            @click.self="setActiveField('category')">
            <div class="category-main-field" @click="setActiveField('category')">
              <label for="category">{{ t("hero.search.category_label") }}</label>
              <div class="input-wrapper">
                <font-awesome-icon :icon="faShapes" class="field-icon" />
                <span class="form-control-display">{{ selectedCategory.text }}</span>
              </div>
              <div v-if="activeField === 'category'" class="category-dropdown">
                <ul>
                  <li v-for="cat in categories" :key="cat.value" @click="selectCategory(cat)">
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


const locationQuery = ref("");
const showLocationDropdown = ref(false);
const locations = ref([
  {
    name: "Koh Samui",
    description: "Tropical island with beaches and motorbike adventures",
    image: "https://content.r9cdn.net/rimg/dimg/75/a7/44202281-city-56280-1767207b463.jpg?width=1366&height=768&xhint=2611&yhint=2498&crop=true",
    type: "nearby",
  },
  {
    name: "Bangkok",
    description: "Vibrant city life, temples, and night markets",
    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR7xbOt6lVS3x90_whZQtI0ZdyRSOYvUqjNbw&s",
  },
  {
    name: "Chiang Mai",
    description: "Mountain city full of culture and coffee vibes",
    image: "https://res.klook.com/image/upload/fl_lossy.progressive,q_60/Mobile/City/cswglxpstphljdourpfu.jpg",
  },
  {
    name: "Phuket",
    description: "Island with clear water and nightlife fun",
    image: "https://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Phuket_Aerial.jpg/1200px-Phuket_Aerial.jpg",
  },
  {
    name: "Pattaya",
    description: "Seaside city near Bangkok, good for quick trips",
    image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSPRE4mm3nN3BNk4Sv20moVjyZ9Unj54KdjYg&s",
  },
]);

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
onMounted(() => {
  document.addEventListener("mousedown", handleClickOutside);
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