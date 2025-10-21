<template>
  <div class="filter-sidebar-wrapper fix-top">
    <!-- Toggle button (only small screens) -->
    <button
  v-if="!showSidebar"
  class="btn toggle-sidebar-btn d-lg-none"
  @click="openSidebar"
>
  <font-awesome-icon :icon="faFilter" class="me-2" />
  Filters
</button>

    <!-- Overlay -->
    <div
  v-if="showSidebar"
  class="sidebar-overlay"
  @click="closeSidebar"
></div>


    <!-- Sidebar -->
   <aside class="filter-sidebar" :class="{ open: showSidebar }">
  <IslandBackground :max-icons="4" />

      <!-- Close button -->
       <button class="btn-close d-lg-none" @click="closeSidebar">&times;</button>

      <!-- Content -->
      <div class="filter-content">
        <div class="filter-section">
          <h5 class="filter-title">Bike Category</h5>
          <div class="form-check" v-for="cat in categories" :key="cat.id">
            <input class="form-check-input" type="checkbox" :id="cat.id" />
            <label class="form-check-label" :for="cat.id">{{ cat.label }}</label>
          </div>
        </div>

        <div class="filter-section">
          <h5 class="filter-title">Price Range</h5>
          <div class="price-input">
            <input
              type="range"
              class="form-range"
              min="0"
              max="200"
              step="5"
              id="priceRange"
            />
            <div class="price-label">Up to $150 / day</div>
          </div>
        </div>

        <div class="filter-section">
          <h5 class="filter-title">Rating</h5>
          <div class="rating-filter">
            <font-awesome-icon
              v-for="n in 5"
              :key="n"
              :icon="faStar"
              class="star-icon"
            />
            <span>4 stars & up</span>
          </div>
        </div>

        <div class="filter-actions">
          <button class="btn btn-primary w-100">Apply Filters</button>
          <button class="btn btn-outline-secondary w-100 mt-2">Reset</button>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { faFilter, faStar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

//  Correct import path (adjust case if needed)
import IslandBackground from "../islandBackground.vue";

const categories = [
  { id: "catMountain", label: "Mountain" },
  { id: "catRoad", label: "Road" },
  { id: "catCity", label: "City" },
  { id: "catElectric", label: "Electric" },
];


const showSidebar = ref(false);

const openSidebar = () => {
  showSidebar.value = true;
  document.body.style.overflow = "hidden"; // prevent scroll
};

const closeSidebar = () => {
  showSidebar.value = false;
  document.body.style.overflow = ""; // restore scroll
};

</script>

<style scoped>
/* 🌴 General wrapper */
.filter-sidebar-wrapper {
  position: relative;
  z-index: 10;
}

/* Sidebar core */
.filter-sidebar {
  position: fixed;
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
  border: 1px solid var(--border-color);
  width: 260px;
  min-height: 100%;
  padding: 1.5rem;
  transition: left 0.3s ease;
  z-index: 1100;
}

/*  Make island background visible inside */
.filter-sidebar .island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.6;
  pointer-events: none;
}

.filter-content {
  position: relative;
  z-index: 2; /* sits above background */
}

/*  Dark mode tuning */
:root[data-bs-theme="dark"] .filter-sidebar .island-background {
  opacity: 0.6;
  filter: brightness(0.7);
}

/* Mobile drawer behavior */
.filter-sidebar {
  position: fixed;
  top: 0;
  left: -280px;
  height: 100vh;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.filter-sidebar.open {
  left: 0;
  background-color: var(--section-bg-color);
}

/* ✅ Overlay for mobile */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1000;
}


/* ✅ Toggle button (mobile only) */
.toggle-sidebar-btn {
  position: fixed;
  top: 5rem;
  left: 1rem;
  z-index: 1200;
  background-color: var(--primary-color);
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  display: flex;
  align-items: center;
  font-weight: 600;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
}
.toggle-sidebar-btn:hover {
  background-color: var(--primary-hover-color);
}

/* ✅ Close button */
.btn-close {
  background: none;
  border: none;
  font-size: 2rem;
  color: var(--text-color);
  position: absolute;
  top: 1rem;
  right: 1rem;
}

/* ✅ Filter styling */
.filter-section {
  margin-bottom: 2rem;
}

.filter-title {
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--text-color);
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--border-color);
}

.form-check-label {
  color: var(--secondary-text-color);
}

.price-label {
  text-align: center;
  margin-top: 0.5rem;
  font-weight: 500;
  color: var(--text-color);
}

.rating-filter {
  display: flex;
  align-items: center;
  color: var(--secondary-text-color);
  cursor: pointer;
}
.rating-filter .star-icon {
  color: #ffc107;
  margin-right: 0.25rem;
}
.rating-filter span {
  margin-left: 0.5rem;
}

/* ✅ Buttons */
.filter-actions button {
  margin-top: 0.5rem;
}

/* ✅ Desktop layout */
@media (min-width: 992px) {
  .filter-sidebar {
    position: relative;
    left: 0;
    height: auto;
    box-shadow: none;
    width: 260px;
  }

  .toggle-sidebar-btn,
  .btn-close,
  .sidebar-overlay {
    display: none;
  }
}
</style>
