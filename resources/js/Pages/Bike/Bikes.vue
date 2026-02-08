<template>
  <div class="bike-list-page-wrapper">

    <IslandBackground :max-icons="5" />

    <div class="bike-list-page">
      <div class="container-fluid content-layer">
        <header class="page-header text-center mb-4">
          <HomeNav />
        </header>

        <div class="layout-wrapper">
          <FilterSidebar />
          <BikeList
            :bikes="bikes"
            :loading="loading"
            @update:bikes="bikes = $event"
          />
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
import { useRoute } from "vue-router";
import { useStore } from "vuex";

import FilterSidebar from "../../components/Bikes/FilterSidebar.vue";
import BikeList from "../../components/Bikes/BikeList.vue";
import HomeNav from "../../components/Layout/HomeNav.vue";
import IslandBackground from "../../components/islandBackground.vue";
import HomeFooter from "../../components/Layout/HomeFooter.vue";

const route = useRoute();
const store = useStore();

const bikes = ref({
  data: [],
});

const loading = ref(true);

const fetchBikes = async () => {
  loading.value = true;

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

  const res = hasFilter
    ? await store.dispatch("homeFilter/fetchFilterBikes", params)
    : await store.dispatch("bikes/fetchAllBikes");

  bikes.value = res.data;
  loading.value = false;
};

onMounted(fetchBikes);

watch(
  () => route.query,
  fetchBikes,
  { deep: true }
);
</script>

<style scoped>
.bike-list-page-wrapper {
  position: relative;
}

.bike-list-page {
  position: relative;
  z-index: 2;
}

.layout-wrapper {
  display: grid;
  grid-template-columns: 250px 1fr;
}

@media (max-width: 991px) {
  .layout-wrapper {
    grid-template-columns: 1fr;
  }
}
</style>
