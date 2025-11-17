<template>
  <div class="container-fluid">
    <section class="bike-section position-relative py-4">
  <IslandBackground :max-icons="2" />

  <div class="container-fluid position-relative">
    <div class="col-12 text-center mb-2">
      <h3 class="text-center bike-title">{{ $t("bike") }}</h3>
    </div>

    <div class="bike-carousel-wrapper">
      <button class="carousel-btn btn-left" @click="scrollLeft">
        <font-awesome-icon icon="chevron-left" />
      </button>

      <div class="bike-cards-container" ref="containerRef">
        <template v-if="loading">
          <div
            class="bike-card-wrapper"
            v-for="n in skeletonCount"
            :key="'skeleton-' + n"
          >
            <BikeCardSkeleton />
          </div>
        </template>

        <template v-else>
          <div
            class="bike-card-wrapper"
            v-for="bike in bikes"
            :key="bike.id"
          >
            <BikeCard
              :id="bike.bike_id"
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
  </div>
</section>
    <!-- scooter section -->
    <section class="bike-section position-relative py-4">

      <IslandBackground :max-icons="2"/>

      <div class="col-12 text-center">
        <h3 class="text-center bike-title">{{ $t("scooter") }}</h3>
      </div>
      <div class="col-12">
        <div class="bike-carousel-wrapper">
          <button class="carousel-btn btn-left" @click="scrollScoLeft">
            <font-awesome-icon icon="chevron-left" />
          </button>

          <div class="bike-cards-container" ref="containerScoRef">
            <!-- Show skeletons when loading -->
            <template v-if="loading">
              <div
                class="bike-card-wrapper"
                v-for="n in skeletonCount"
                :key="'skeleton-' + n"
              >
                <BikeCardSkeleton />
              </div>
            </template>

            <!-- Show real bikes when loaded -->
            <template v-else>
              <div
                class="bike-card-wrapper"
                v-for="bike in bikes.slice().reverse()"
                :key="bike.id"
              >
                <BikeCard
                  :id="bike.bike_id"
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

          <button class="carousel-btn btn-right" @click="scrollScoRight">
            <font-awesome-icon icon="chevron-right" />
          </button>
        </div>
      </div>
    </section>
    <!-- end scooter section -->
    <!-- Adventure section -->
    <section class="bike-section position-relative py-4">
      <IslandBackground :max-icons="2"/>
      <div class="col-12 text-center">
        <h3 class="text-center bike-title">{{ $t("adventure") }}</h3>
      </div>
      <div class="col-12">
        <div class="bike-carousel-wrapper">
          <button class="carousel-btn btn-left" @click="scrollAdvLeft">
            <font-awesome-icon icon="chevron-left" />
          </button>

          <div class="bike-cards-container" ref="containerAdvRef">
            <!-- Show skeletons when loading -->
            <template v-if="loading">
              <div
                class="bike-card-wrapper"
                v-for="n in skeletonCount"
                :key="'skeleton-' + n"
              >
                <BikeCardSkeleton />
              </div>
            </template>

            <!-- Show real bikes when loaded -->
            <template v-else>
              <div class="bike-card-wrapper" v-for="bike in bikes" :key="bike.id">
                <BikeCard
                  :id="bike.bike_id"
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

          <button class="carousel-btn btn-right" @click="scrollAdvRight">
            <font-awesome-icon icon="chevron-right" />
          </button>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import BikeCard from "./BikeCard.vue";
import { useI18n } from "vue-i18n";
import BikeCardSkeleton from "../loader/BikeCardSkeleton.vue";
import axios from "../../service/axios";
import IslandBackground from "../islandBackground.vue";
const t = useI18n();
const bikes = ref([]);
const loading = ref(true);
const skeletonCount = 7; // number of skeleton cards to show
const containerRef = ref(null);
const containerAdvRef = ref(null);
const containerScoRef = ref(null);

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

const scrollAdvLeft = () => {
  containerAdvRef.value?.scrollBy({ left: -scrollAmount, behavior: "smooth" });
};
const scrollAdvRight = () => {
  containerAdvRef.value?.scrollBy({ left: scrollAmount, behavior: "smooth" });
};

const scrollScoLeft = () => {
  containerScoRef.value?.scrollBy({ left: -scrollAmount, behavior: "smooth" });
};
const scrollScoRight = () => {
  containerScoRef.value?.scrollBy({ left: scrollAmount, behavior: "smooth" });
};
</script>
<style scoped>
.bike-carousel-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  gap: 1rem;
  /* overflow: hidden; */
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
.bike-title {
  font-size: 3rem;
  font-weight: 700;
  background: linear-gradient(90deg, #63c1a2, #63c1a2); /* gradient text */
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  display: inline-block;
  margin-bottom: 2rem;
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
  width: 100px; /* expand underline on hover */
}

.bike-title {
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}
.bike-section {
  position: relative;
  overflow: hidden;
  isolation: isolate; /* keep background z-index local */
  background-color: transparent; /* make sure icons show */
}

.bike-section::before {
  content: "";
  position: absolute;
  inset: 0;
  z-index: -2; /* sits behind the icons */
}

/* ensure the floating icons layer is visible */
.island-background {
  position: absolute;
  inset: 0;
  opacity: 0.6;
  z-index: -1;
  pointer-events: none;
}
</style>
