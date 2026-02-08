<template>
  <section class="bike-list-section">
    <IslandBackground :max-icons="16" />

    <div class="container-fluid content-layer">
      <div class="row g-2">
        <!-- Loaded Bikes -->
        <template v-if="!loading">
          <div
            v-for="bike in bikes.data"
            :key="bike.id"
            class="col-6 col-sm-6 col-md-3 col-lg-2"
          >
            <BikeCard
              :id="bike.id"
              :image="bike.photo"
              :title="bike.title"
              :model="bike.model + ', ' + bike.year"
              :price="bike.price"
              :store_id="bike.store.id"
              :store_logo="bike.store.logo"
              :store_name="bike.store.name"
              :review="4.5"
            />
          </div>
        </template>

        <!-- Skeleton -->
        <template v-else>
          <div
            v-for="n in skeletonCount"
            :key="n"
            class="col-6 col-sm-6 col-md-3 col-lg-2"
          >
            <BikeCardSkeleton />
          </div>
        </template>

        <!-- Pagination -->
        <div class="col-12 mt-4 d-flex justify-content-center">
          <Bootstrap5Pagination
            :data="bikes"
            @pagination-change-page="getResults"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

import BikeCard from "./BikeCard.vue";
import BikeCardSkeleton from "../loader/BikeCardSkeleton.vue";
import IslandBackground from "../islandBackground.vue";

const props = defineProps({
  bikes: {
    type: Object,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:bikes"]);

const route = useRoute();
const store = useStore();

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

  emit("update:bikes", res.data);
};
</script>

<style scoped>
.bike-list-section {
  position: relative;
  min-height: 100vh;
}

.content-layer {
  position: relative;
  z-index: 2;
}
</style>
