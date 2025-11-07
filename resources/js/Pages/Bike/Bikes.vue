<template>
  <div class="bike-list-page-wrapper">
    <IslandBackground :max-icons="5" />
    <div class="bike-list-page">
      <div class="container-fluid content-layer" >
        <header class="page-header text-center mb-4">
          <HomeNav />
        </header>
        <div class="layout-wrapper">
          <FilterSidebar />
          <BikeList :bikes="bikes" :loading="loading" />
        </div>
        <footer class="page-footer mt-2">
          <HomeFooter />
        </footer>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import FilterSidebar from "../../components/Bikes/FilterSidebar.vue";
import BikeList from "../../components/Bikes/BikeList.vue";
import HomeNav from "../../components/Layout/HomeNav.vue";
import IslandBackground from "../../components/islandBackground.vue";
import HomeFooter from "../../components/Layout/HomeFooter.vue";
import { useRoute } from 'vue-router';
import { useStore } from "vuex";

const route = useRoute();
const store = useStore();
const bikes = ref([]); // bikes data for child component
const loading = ref(true);

//  fetch filtered bikes if params exist
const fetchFilterBikes = async () => {
  const params = {
    location: route.query.location,
    location_id: route.query.location_id,
    start_date: route.query.start_date,
    end_date: route.query.end_date,
    category_id: route.query.category_id,
  };

  const hasFilter =
    params.location_id ||
    params.start_date ||
    params.end_date ||
    (params.category_id && params.category_id !== "all");

  let res;
  if (hasFilter) {
    // If filters exist, call filter API
    res = await store.dispatch("homeFilter/fetchFilterBikes", params);
  } else {
    // Otherwise fetch all
    res = await store.dispatch("bikes/fetchAllBikes");
  }

  bikes.value = res.data || [];
  loading.value = false;
};

//  Load when page opens
onMounted(fetchFilterBikes);

// Re-fetch when query changes (ex: user selects new filter)
watch(
  () => route.query,
  async () => {
    loading.value = true;
    await fetchFilterBikes();
  },
  { deep: true }
);
</script>


<style scoped>
.bike-list-page-wrapper {
  position: relative;
}

.island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.5;
  pointer-events: none;
}
.island-background { border: 1px solid red; }

.bike-list-page {
  position: relative;
  z-index: 2;
}

/* Foreground content */
.content-layer {
  position: relative;
  z-index: 2;
}

/* Existing styles */
.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.layout-wrapper {
  display: grid;
  grid-template-columns: 250px 1fr;
}

/* @media (max-width: 992px) {
  .layout-wrapper {
    grid-template-columns: 1fr;
  }
} */
@media (min-width: 970px) and (max-width: 1300px) {
  .layout-wrapper {
    grid-template-columns: 190px 1fr;
  }
}
@media (min-width: 1299px) and (max-width: 1600px) {
  .layout-wrapper {
    grid-template-columns: 220px 1fr;
  }
}
</style>
