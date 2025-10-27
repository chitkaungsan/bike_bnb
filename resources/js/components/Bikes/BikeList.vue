<template>
  <section class="bike-list-section">
    <!-- 🌴 Island Background Layer -->
    <islandBackground :max-icons="16" />

    <!-- 🏍️ Bike Section -->
    <div class="container-fluid content-layer">
      <div class="row mb-5">
        <div class="row g-3">
          <!-- Bikes Loaded -->
          <div
            v-for="bike in bikes.data"
            :key="bike.id"
            class="col-6 col-sm-6 col-md-4 col-lg-5th"
            v-if="!loading"
          >
            <BikeCard
              :id="bike.id"
              :image="bike.photo"
              :title="bike.title"
              :model="bike.model + ', ' + bike.year"
              :price="bike.price"
              :store_id="bike.store_id"
              :store_logo="bike.store_logo"
              :store_name="bike.store_name"
              :review="4.5"
            />
          </div>

          <!-- Skeleton Loading -->
          <div
            v-else
            class="col-6 col-sm-6 col-md-4 col-lg-5th"
            v-for="n in skeletonCount"
            :key="'skeleton-' + n"
            v-show="loading"
          >
            <BikeCardSkeleton />
          </div>

          <!-- Pagination -->
          <div class="col-12 justify-content-center mt-4">
            <Bootstrap5Pagination
              :data="bikes"
              @pagination-change-page="getResults"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { defineProps } from "vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import BikeCard from "./BikeCard.vue";
import BikeCardSkeleton from "../loader/BikeCardSkeleton.vue";
import islandBackground from "../islandBackground.vue";

const props = defineProps({
  bikes: {
    type: Array,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const skeletonCount = 20;
const getResults = async (page = 1) => {
  const hasFilter =
    route.query.location_id ||
    route.query.start_date ||
    route.query.end_date ||
    (route.query.category_id && route.query.category_id !== "all");

  const params = { ...route.query, page };
  const res = hasFilter
    ? await store.dispatch("homeFilter/fetchFilterBikes", params)
    : await store.dispatch("bikes/fetchAllBikes", page);

  bikes.value = res.data || [];
};


</script>

<style scoped>
/* 🌴 Section Wrapper */
.bike-list-section {
  position: relative;
  min-height: 100vh;
  /* overflow: hidden; */
  background-color: transparent;
}

.island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.6;
  pointer-events: none;
}

/* Foreground Content */
.content-layer {
  position: relative;
  z-index: 2;
}

/* 🏍️ Bike Title Decoration */
.bike-title {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(90deg, #63c1a2, #63c1a2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
  margin-bottom: 2rem;
  position: relative;
  display: inline-block;
}

.bike-title::after {
  content: "";
  display: block;
  width: 60px;
  height: 4px;
  background: #63c1a2;
  margin: 0.5rem auto 0;
  border-radius: 2px;
  transition: width 0.3s;
}
.bike-title:hover::after {
  width: 100px;
}

/* 🧱 Grid Fix */
@media (min-width: 992px) {
  .col-lg-5th {
    flex: 0 0 20%;
    max-width: 20%;
  }
}
</style>
