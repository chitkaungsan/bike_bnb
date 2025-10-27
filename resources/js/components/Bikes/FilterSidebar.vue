<template>
  <div class="filter-sidebar-wrapper fix-top">
    <button v-if="!showSidebar" class="btn toggle-sidebar-btn d-lg-none" @click="openSidebar">
      <font-awesome-icon :icon="faFilter" class="me-2" /> Filters
    </button>

    <div v-if="showSidebar" class="sidebar-overlay" @click="closeSidebar"></div>

    <aside class="filter-sidebar" :class="{ open: showSidebar }">
      <IslandBackground :max-icons="4" />
      <button class="btn-close d-lg-none" @click="closeSidebar">&times;</button>

      <div class="filter-content">
        <FilterCity />
        <div class="divider"></div>
        <FilterDatepicker />
        <div class="divider"></div>
        <FilterCategory />
        <div class="divider"></div>
        <FilterPrice />
        <div class="divider"></div>
        <FilterRating />
        <div class="divider"></div>
      </div>

      <div class="filter-actions">
        <button class="btn btn-ride w-100" @click="searchBikes">Show Bikes</button>
        <button class="btn btn-outline-danger w-100 mt-2" @click="reset">Reset</button>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { faFilter } from "@fortawesome/free-solid-svg-icons";
import IslandBackground from "../islandBackground.vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import FilterCategory from "./FilterComponents/FilterCategory.vue";
import FilterPrice from "./FilterComponents/FilterPrice.vue";
import FilterRating from "./FilterComponents/FilterRating.vue";
import FilterCity from "./FilterComponents/FilterCity.vue";
import FilterDatepicker from "./FilterComponents/FilterDatepicker.vue";

const router = useRouter();
const store = useStore();
const showSidebar = ref(false);
const openSidebar = () => {
  showSidebar.value = true;
  document.body.style.overflow = "hidden";
};
const closeSidebar = () => {
  showSidebar.value = false;
  document.body.style.overflow = "";
};
const searchBikes = async () => {
  console.log("Search Bikes");
  await store.dispatch("homeFilter/searchBikesFilter",router);
}
const reset = async () => {
  console.log("Reset");
  await store.dispatch("homeFilter/resetFilter");
}
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
  border-radius: var(--border-radius-md);
  border: 1px solid var(--border-color);
  width: 240px;
  min-height: 100%;
  padding: 1rem; /* Reduced from 1.5rem */
  transition: left 0.3s ease;
  z-index: 1100;
}

/*  Island background */
.filter-sidebar .island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.6;
  pointer-events: none;
}
.btn-ride {
  background-color: #6c757d; /* your requested color */
  border: none;
  color: #fff;
  font-weight: 600;
  padding: 0.55rem 1rem;
  border-radius: 8px;
  transition: all 0.25s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

/* Filter content */
.filter-content {
  position: relative;
  z-index: 2;
  margin-top: 0.5rem;
}

/* Filter sections */
.filter-section {
  margin-bottom: 1.2rem; /* reduced from 2rem */
}

/* Titles */
.filter-title {
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 0.6rem; /* tighter */
  color: var(--text-color);
  padding-bottom: 0.3rem;
  border-bottom: 1px solid var(--border-color);
}

/* Mobile drawer */
.filter-sidebar {
  position: fixed;
  top: 0;
  left: -260px;
  height: 100vh;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.filter-sidebar.open {
  left: 0;
  background-color: var(--section-bg-color);
}

/* Overlay */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.35);
  z-index: 1000;
}

/* Toggle button */
.toggle-sidebar-btn {
  position: fixed;
  top: 4.5rem;
  left: 1rem;
  z-index: 1200;
  background-color: var(--primary-color);
  color: #fff;
  padding: 0.4rem 0.9rem;
  border-radius: 6px;
  display: flex;
  align-items: center;
  font-weight: 600;
  font-size: 0.85rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.toggle-sidebar-btn:hover {
  background-color: var(--primary-hover-color);
}

/* Close button */
.btn-close {
  background: none;
  border: none;
  font-size: 1.6rem;
  color: var(--text-color);
  position: absolute;
  top: 0.6rem;
  right: 0.6rem;
}

/* Divider (soft + compact) */
.divider {
  height: 1px;
  width: 100%;
  background: linear-gradient(
    to right,
    transparent,
    var(--border-color),
    transparent
  );
  margin: 0.7rem 0;
  opacity: 0.6;
  border-radius: 2px;
}

/* Filter actions */
.filter-actions button {
  margin-top: 0.4rem;
  padding: 0.4rem 0.8rem;
  font-size: 0.85rem;
}

/* Desktop */
@media (min-width: 992px) {
  .filter-sidebar {
    position: relative;
    left: 0;
    height: auto;
    box-shadow: none;
    width: 240px;
    padding: 0.9rem;
  }

  .toggle-sidebar-btn,
  .btn-close,
  .sidebar-overlay {
    display: none;
  }
}
</style>
