<template>
  <div class="filter-section city-filter">
    <h5 class="filter-title">Ride destination</h5>

    <!--  Sidebar content -->
    <div v-if="selectedCity" class="selected-city">
      <img :src="selectedCity.image" alt="selected city" class="selected-city-img" />
      <div class="selected-city-info">
        <h6 class="city-name">{{ selectedCity.name }}</h6>
        <p class="city-description">{{ selectedCity.description }}</p>
      </div>
    </div>

    <div v-else class="no-city">
      <p>No place? choose one below</p>
    </div>

    <button class="btn btn-ride w-100 mt-2" @click="showModal = true">
      {{ selectedCity ? 'Ride somewhere new' : 'Find place to ride' }}
    </button>

    <!--  Modal -->
    <div v-if="showModal" class="city-modal-overlay" @click.self="closeModal">
      <div class="city-modal">
        <!--  Search bar -->
        <div class="modal-header">
          <h5 class="modal-title">Pick your paradise</h5>
          <input type="text" class="form-control city-search" placeholder="Search city..." v-model="searchTerm" />
        </div>

        <!--  City Grid -->
        <div class="city-grid">
          <div v-for="city in filteredCities" :key="city.id" class="city-card" @click="selectCity(city)">
            <img v-if="city.image" :src="city.image" :alt="city.name" class="city-image" />
            <div v-else class="city-placeholder"></div>

            <!-- info below image -->
            <div class="city-info">
              <h6 class="city-name">{{ city.name }}</h6>
              <p class="city-description">{{ city.description }}</p>
            </div>
          </div>
        </div>

        <button class="btn btn-outline-secondary w-100 mt-3" @click="closeModal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, watch } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const route = useRoute();
const store = useStore();

const selectedCity = ref(null);
const showModal = ref(false);
const searchTerm = ref("");

const cities = computed(() => store.getters["cities/allCities"]);

const filteredCities = computed(() => {
  const term = searchTerm.value.toLowerCase();
  return cities.value.filter((city) =>
    city.name.toLowerCase().includes(term)
  );
});

const selectCity = (city) => {
  selectedCity.value = city;
  showModal.value = false;
  store.dispatch("homeFilter/setSelectedCity", city.id);
};

const closeModal = () => {
  showModal.value = false;
};

// Wait until cities are fetched, THEN set selectedCity
onMounted(async () => {
  await store.dispatch("cities/fetchCities");

  const locationId = Number(route.query.location_id);
  if (locationId) {
    selectedCity.value = cities.value.find((c) => c.id === locationId) || null;
  }
});

//  If route changes later (user navigates), update selection
watch(
  () => route.query.location_id,
  (newId) => {
    const id = Number(newId);
    if (id) {
      selectedCity.value = cities.value.find((c) => c.id === id) || null;
    } else {
      selectedCity.value = null;
    }
  }
);
</script>


<style scoped>
/*  Sidebar Selected City */
.selected-city {
  text-align: left;
  width: 100%;
}

.selected-city-img {
  width: 100%;
  height: 120px;
  border-radius: 10px;
  object-fit: cover;
  margin-bottom: 0.5rem;
}

.selected-city-info {
  padding: 0 0.25rem;
}

.city-name {
  font-weight: 600;
  color: var(--text-color);
  margin-bottom: 0.25rem;
}

.city-description {
  font-size: 0.85rem;
  color: var(--secondary-text-color);
  margin: 0;
}

.no-city {
  text-align: center;
  color: var(--secondary-text-color);
  font-size: 0.9rem;
}

/*  Modal Overlay */
.city-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1200;
}

/*  Modal Box */
.city-modal {
  background: var(--section-bg-color);
  border-radius: 16px;
  padding: 1.5rem;
  width: 90%;
  max-width: 800px;
  max-height: 85vh;
  overflow-y: auto;
  animation: fadeIn 0.3s ease;
}

/*  Search */
.modal-header {
  margin-bottom: 1.2rem;
}

.modal-title {
  text-align: center;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.city-search {
  border-radius: 8px;
  border: 1px solid var(--border-color);
  padding: 0.5rem 0.75rem;
  font-size: 0.9rem;
  width: 100%;
}

/*  City Grid */
.city-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 1rem;
}

.city-card {
  background-color: var(--background-color);
  border: 1px solid var(--border-color);
  border-radius: 14px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.city-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
}

.city-image {
  width: 100%;
  height: 130px;
  object-fit: cover;
}

.city-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 130px;
  background: linear-gradient(135deg, #c8f0e2, #e0f7fa);
  font-size: 1.8rem;
}

/*  City info below image */
.city-info {
  padding: 0.75rem;
  text-align: left;
}

.city-tagline {
  font-size: 0.85rem;
  color: var(--primary-color);
  margin: 0.2rem 0 0.3rem;
  font-weight: 500;
}

.city-description {
  font-size: 0.8rem;
  color: var(--secondary-text-color);
  margin: 0;
  line-height: 1.3;
}

/* ✨ Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }

  to {
    opacity: 1;
    transform: scale(1);
  }
}

.btn-ride {
  background-color: #6c757d;
  /* your requested color */
  border: none;
  color: #fff;
  font-weight: 600;
  padding: 0.55rem 1rem;
  border-radius: 8px;
  transition: all 0.25s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.btn-ride:hover {
  background-color: #5a6268;
  /* slightly darker on hover */
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
}

@media (min-width: 1600px) and (max-width: 1920px) {
  /* .layout-wrapper {
    grid-template-columns: 220px 1fr;
  } */
}

@media (min-width: 1440px) and (max-width: 1599px) {
  .filter-title {
    font-size: 1.0rem;
    font-weight: bold;
  }
  .no-city {
    font-size: 0.75rem;
  }
  .btn-ride {
    font-size: 0.8rem;
    padding: 0.5rem 1.0rem;
  }
  .selected-city-img {
    width: 100%;
    height: 100px;
    border-radius: 8px;
    margin-bottom: 0.4rem;
  }
  .city-name {
    font-weight: 600;
    margin-bottom: 0.2rem;
    font-size: 0.8rem;
  }

  .city-description {
    font-size: 0.7rem;
    margin: 0;
  }
}
</style>
