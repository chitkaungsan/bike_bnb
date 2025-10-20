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
          <BikeList :bikes="bikes" />
        </div>
        <footer class="page-footer mt-2">
          <HomeFooter />
        </footer>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref } from "vue";
import FilterSidebar from "../../components/Bikes/FilterSidebar.vue";
import BikeList from "../../components/Bikes/BikeList.vue";
import HomeNav from "../../components/Layout/HomeNav.vue";
import IslandBackground from "../../components/islandBackground.vue"; 
import HomeFooter from "../../components/Layout/HomeFooter.vue";
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
  grid-template-columns: 280px 1fr;
  gap: 2rem;
}

@media (max-width: 992px) {
  .layout-wrapper {
    grid-template-columns: 1fr;
  }
}
</style>
