<template>
  <div class="bike-list-page">
    <div class="container-fluid">
      <!-- Page Header -->
      <header class="page-header text-center mb-4">
        <HomeNav />
      </header>
      <div class="layout-wrapper">
        <FilterSidebar />
        <BikeList :bikes="bikes" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import FilterSidebar from "../components/Bikes/FilterSidebar.vue";
import BikeList from "../components/Bikes/BikeList.vue";
import HomeNav from "../components/Layout/HomeNav.vue";
</script>

<style scoped>
.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-title {
  font-weight: 700;
  font-size: 2rem;
  color: var(--text-color);
}

.page-subtitle {
  font-size: 1rem;
  color: var(--secondary-text-color);
  margin-top: 0.5rem;
}

.layout-wrapper {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 2rem;
}

@media (max-width: 992px) {
  .layout-wrapper {
    grid-template-columns: 1fr;
  }
}
</style>
