<template>
  <div class="container-fluid">
    <!-- Bike Section -->
    <div class="row mb-5">
      <div class="row g-3">
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
        <div
          v-else
          class="col-6 col-sm-6 col-md-4 col-lg-5th"
          v-for="n in skeletonCount"
          :key="'skeleton-' + n"
          v-show="loading"
        >
          <BikeCardSkeleton />
        </div>
        <div class="col-12 justify-content-center">
          <Bootstrap5Pagination :data="bikes" @pagination-change-page="getResults" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import axios from "../../service/axios";
import BikeCard from "./BikeCard.vue";
import { useStore } from "vuex";
import BikeCardSkeleton from "../loader/BikeCardSkeleton.vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
const skeletonCount = 20; //

const store = useStore();
const { t } = useI18n();
const bikes = ref([]);
const loading = ref(true);
const getResults = async (page = 1) => {
  const res = await store.dispatch("bikes/fetchAllBikes", page);
  bikes.value = res.data || [];
};

onMounted(async () => {
  try {
    const res = await store.dispatch("bikes/fetchAllBikes");
    console.log("res", res.data.data);
    bikes.value = res.data || [];
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
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
@media (min-width: 992px) {
  .col-lg-5th {
    flex: 0 0 20%;
    max-width: 20%;
  }
}
</style>
