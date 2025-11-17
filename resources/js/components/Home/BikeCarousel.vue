<template>
  <div class="row py-4">
    <div class="col-12 text-center">
      <h3 class="text-center vehicle-title">{{ title }}</h3>
    </div>
    <div class="col-12">
      <div class="carousel-container">
        <div
          class="carousel-wrapper"
          :class="{ 'mask-start': !isAtStart, 'mask-end': !isAtEnd }"
        >
          <button class="carousel-btn btn-left" @click="scroll(-1)">
            <font-awesome-icon icon="chevron-left" />
          </button>

          <div class="cards-container" ref="containerRef" @scroll="checkScrollPosition">
            <template v-if="loading">
              <div class="card-wrapper" v-for="n in 6" :key="'skeleton-' + n">
                <BikeCardSkeleton />
              </div>
            </template>

            <template v-else>
              <div class="card-wrapper" v-for="vehicle in vehicles" :key="vehicle.id">
                <BikeCard
                  :image="vehicle.photo"
                  :title="vehicle.title"
                  :model="`${vehicle.model}, ${vehicle.year}`"
                  :price="vehicle.price"
                  :store_id="vehicle.store_id"
                  :store_logo="vehicle.store_logo"
                  :store_name="vehicle.store_name"
                  :review="4.5"
                />
              </div>
            </template>
          </div>

          <button class="carousel-btn btn-right" @click="scroll(1)">
            <font-awesome-icon icon="chevron-right" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import BikeCard from "./BikeCard.vue";
import BikeCardSkeleton from "../loader/BikeCardSkeleton.vue";

defineProps({
  title: { type: String, required: true },
  vehicles: { type: Array, required: true },
  loading: { type: Boolean, required: true },
});

const containerRef = ref(null);
const isAtStart = ref(true);
const isAtEnd = ref(false);

const scroll = (direction) => {
  if (containerRef.value) {
    const scrollAmount = containerRef.value.clientWidth * 0.8 * direction;
    containerRef.value.scrollBy({ left: scrollAmount, behavior: "smooth" });
  }
};

const checkScrollPosition = () => {
  if (containerRef.value) {
    const { scrollLeft, scrollWidth, clientWidth } = containerRef.value;
    isAtStart.value = scrollLeft < 10;
    isAtEnd.value = scrollWidth - scrollLeft - clientWidth < 10;
  }
};

onMounted(() => {
  setTimeout(() => {
    checkScrollPosition();
    containerRef.value?.addEventListener("scroll", checkScrollPosition);
  }, 100);
});

onUnmounted(() => {
  containerRef.value?.removeEventListener("scroll", checkScrollPosition);
});
</script>

<style scoped>
.carousel-container {
  position: relative;
  padding: 0 40px;
}

.carousel-wrapper {
  position: relative;
}

.cards-container {
  display: flex;
  gap: 1.5rem;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding: 1rem 2px;
  scroll-padding: 0 1rem;
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.cards-container::-webkit-scrollbar {
  display: none;
}

.carousel-wrapper::before,
.carousel-wrapper::after {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  width: 50px;
  z-index: 5;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.3s;
}
.carousel-wrapper::before {
  left: 0;
  background: linear-gradient(to right, var(--background-color), transparent);
}
.carousel-wrapper::after {
  right: 0;
  background: linear-gradient(to left, var(--background-color), transparent);
}
.carousel-wrapper.mask-start::before {
  opacity: 1;
}
.carousel-wrapper.mask-end::after {
  opacity: 1;
}

/* === MODIFICATION START === */
/* REMOVED the responsive media queries for card sizing. */
/* ADDED a single rule to make cards a fixed size on all screens. */
.card-wrapper {
  flex: 0 0 300px; /* Fixed width of 300px */
}
/* === MODIFICATION END === */

.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  background: var(--section-bg-color);
  border: 1px solid var(--border-color);
  color: var(--text-color);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  display: none;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s, opacity 0.3s;
}
.carousel-btn:hover {
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}
@media (min-width: 768px) {
  .carousel-btn {
    display: flex;
  }
}

.btn-left {
  left: -65px;
}
.btn-right {
  right: -65px;
}
.carousel-wrapper.mask-start ~ .btn-left,
.carousel-wrapper:not(.mask-start) .btn-left {
  opacity: 1;
}
.carousel-wrapper:not(.mask-start) .btn-left {
  opacity: 0.3;
  pointer-events: none;
}
.carousel-wrapper.mask-end ~ .btn-right,
.carousel-wrapper:not(.mask-end) .btn-right {
  opacity: 1;
}
.carousel-wrapper:not(.mask-end) .btn-right {
  opacity: 0.3;
  pointer-events: none;
}

.vehicle-title {
  font-size: 2.5rem;
  font-weight: 700;
  background: linear-gradient(90deg, #63c1a2, #52a88a);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  display: inline-block;
  margin-bottom: 1rem;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}
.vehicle-title::after {
  content: "";
  display: block;
  width: 60px;
  height: 4px;
  background: #63c1a2;
  margin: 0.5rem auto 0;
  border-radius: 2px;
  transition: width 0.3s;
}
.vehicle-title:hover::after {
  width: 100px;
}
</style>
