<template>
  <div class="bike-carousel-wrapper">
    <button class="carousel-btn btn-left" @click="scrollLeft">
      <font-awesome-icon icon="chevron-left" />
    </button>

    <div class="bike-cards-container" ref="containerRef">
      <!-- Show skeletons when loading -->
      <template v-if="loading">
        <div class="bike-card-wrapper" v-for="n in skeletonCount" :key="'skeleton-' + n">
          <BikeCardSkeleton />
        </div>
      </template>

      <!-- Show real bikes when loaded -->
      <template v-else>
        <div class="bike-card-wrapper" v-for="bike in bikes" :key="bike.id">
          <BikeCard
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
      </template>
    </div>

    <button class="carousel-btn btn-right" @click="scrollRight">
      <font-awesome-icon icon="chevron-right" />
    </button>
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import BikeCard from "./BikeCard.vue";
import BikeCardSkeleton from "../loader/BikeCardSkeleton.vue";
import axios from "../../service/axios";

const bikes = ref([]);
const loading = ref(true);
const skeletonCount = 7; // number of skeleton cards to show
const containerRef = ref(null);

onMounted(async () => {
  try {
    const res = await axios.get("home/bikes");
    bikes.value = res.data || [];
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
});

// Scroll logic
const scrollAmount = 300;
const scrollLeft = () => {
  containerRef.value?.scrollBy({ left: -scrollAmount, behavior: "smooth" });
};
const scrollRight = () => {
  containerRef.value?.scrollBy({ left: scrollAmount, behavior: "smooth" });
};
</script>
<style scoped>
.bike-carousel-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  gap: 1rem;
  overflow: hidden;
}

.bike-cards-container {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding: 1rem 0;
}

.bike-card-wrapper {
  flex: 0 0 300px; /* width of each card */
}

.bike-cards-container::-webkit-scrollbar {
  display: none;
}

.carousel-btn {
  z-index: 10;
  background: var(--background-color);
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}
.btn-left {
  left: 0.5rem;
}
.btn-right {
  right: 0.5rem;
}
</style>
