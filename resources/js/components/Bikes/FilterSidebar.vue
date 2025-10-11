<template>
  <div class="filter-sidebar-wrapper fix-top">
    <!-- Toggle button only visible on small screens -->
    <button class="btn toggle-sidebar-btn d-lg-none" @click="showSidebar = true">
      <font-awesome-icon :icon="faFilter" class="me-2" />
      Filters
    </button>

    <!-- Overlay for drawer effect -->
    <div v-if="showSidebar" class="sidebar-overlay" @click="showSidebar = false"></div>

    <!-- Sidebar -->
    <aside class="filter-sidebar" :class="{ open: showSidebar }">
      <!-- Close button for mobile -->
      <button class="btn-close d-lg-none" @click="showSidebar = false">&times;</button>

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
          <font-awesome-icon v-for="n in 5" :key="n" :icon="faStar" class="star-icon" />
          <span>4 stars & up</span>
        </div>
      </div>

      <div class="filter-actions">
        <button class="btn btn-primary w-100">Apply Filters</button>
        <button class="btn btn-outline-secondary w-100 mt-2">Reset</button>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { faFilter, faStar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const categories = [
  { id: "catMountain", label: "Mountain" },
  { id: "catRoad", label: "Road" },
  { id: "catCity", label: "City" },
  { id: "catElectric", label: "Electric" },
];

const showSidebar = ref(false);
</script>

<style scoped>
/* Toggle button (hamburger) */
.toggle-sidebar-btn {
  position: fixed;
  top: 5rem; /* was 1rem */
  left: 1rem;
  z-index: 1100;
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
  background-color: var(--primary-color-dark);
}

/* Overlay behind sidebar on mobile */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1000;
  transition: opacity 0.3s ease;
}

/* Sidebar */
.filter-sidebar {
  background-color: var(--section-bg-color);
  padding: 1.5rem;
  border-radius: var(--border-radius-lg);
  border: 1px solid var(--border-color);
  height: 100%;
  width: 250px;
  position: fixed;
  top: 0;
  left: -300px;
  z-index: 1100;
  overflow-y: auto;
  transition: left 0.3s ease;
}

/* Open state for mobile */
.filter-sidebar.open {
  left: 0;
}

/* Close button for mobile */
.btn-close {
  background: none;
  border: none;
  font-size: 2rem;
  color: var(--text-color);
  position: absolute;
  top: 1rem;
  right: 1rem;
}

/* Keep all existing sidebar styling */
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

.filter-actions button {
  margin-top: 0.5rem;
}

/* Large screen: always show sidebar */
@media (min-width: 992px) {
  .filter-sidebar {
    position: relative;
    left: 0 !important;
    height: fit-content;
    width: auto;
    box-shadow: none;
  }

  .toggle-sidebar-btn,
  .btn-close {
    display: none;
  }

  .sidebar-overlay {
    display: none;
  }
}
</style>
