<template>
  <div class="row py-4">
    <div class="col-12 text-center">
      <h3 class="text-center vehicle-title">{{ title }}</h3>
    </div>
    <div class="col-12">
      <div class="carousel-wrapper">
        <button class="carousel-btn btn-left" @click="scroll(-1)">
          <font-awesome-icon icon="chevron-left" />
        </button>

        <div class="cards-container" ref="containerRef">
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
</template>

<script setup>
import { ref } from "vue";
import BikeCard from "../components/Home/BikeCard.vue";
import BikeCardSkeleton from "../components/loader/BikeCardSkeleton.vue";

// Props allow the parent to send data to this component
defineProps({
  title: {
    type: String,
    required: true,
  },
  vehicles: {
    type: Array,
    required: true,
  },
  loading: {
    type: Boolean,
    required: true,
  },
});

const containerRef = ref(null);

// A single, dynamic scroll function
const scroll = (direction) => {
  if (containerRef.value) {
    // Scroll by the visible width of the container for a "page" effect
    const scrollAmount = containerRef.value.clientWidth * direction;
    containerRef.value.scrollBy({ left: scrollAmount, behavior: "smooth" });
  }
};
</script>

<style scoped>
.carousel-wrapper {
  position: relative;
}

.cards-container {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding: 1rem 0;
  /* Hide scrollbar */
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE and Edge */
}

.cards-container::-webkit-scrollbar {
  display: none; /* Chrome, Safari, and Opera */
}

/* =================================== */
/* == MOBILE-FIRST RESPONSIVE CARDS == */
/* =================================== */

/* Mobile (sm): 2 cards visible */
.card-wrapper {
  flex-shrink: 0;
  flex-basis: calc(50% - 0.5rem); /* 2 cards, accounting for 1rem gap */
}

/* Tablet (md): 4 cards visible */
@media (min-width: 768px) {
  .card-wrapper {
    flex-basis: calc(25% - 0.75rem); /* 4 cards */
  }
}

/* Desktop (lg): 6 cards visible */
@media (min-width: 1200px) {
  .card-wrapper {
    flex-basis: calc(16.666% - 0.84rem); /* 6 cards */
  }
}

/* Carousel Buttons */
.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  background: var(--section-bg-color, #fff);
  border: 1px solid var(--border-color, #ddd);
  width: 45px;
  height: 45px;
  border-radius: 50%;
  cursor: pointer;
  display: none; /* Hide buttons on mobile by default */
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}
.carousel-btn:hover {
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Show buttons on larger screens where there's space */
@media (min-width: 768px) {
  .carousel-btn {
    display: flex;
  }
}

.btn-left {
  left: -22px;
}
.btn-right {
  right: -22px;
}

/* Title Styling */
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
