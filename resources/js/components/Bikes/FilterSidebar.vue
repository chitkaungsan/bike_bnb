<template>
  <div class="filter-sidebar-wrapper fix-top" >
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
  await store.dispatch("homeFilter/searchBikesFilter",router);
}
const reset = async () => {
  await store.dispatch("homeFilter/resetFilter");
}
</script>

<style scoped>
/* General wrapper */
.filter-sidebar-wrapper {
  position: relative;
  z-index: 10;
  padding-top: 0.4rem; /* ↓ smaller top space */
}

/* Sidebar core */
.filter-sidebar {
  position: fixed;
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-md);
  border: 1px solid var(--border-color);
  width: 240px;
  min-height: 100%;
  padding: 0.7rem 0.8rem; /* ↓ reduced padding */
  transition: left 0.3s ease;
  z-index: 1100;
  overflow-y: auto;
  overflow-x: hidden;
  scrollbar-width: thin;
  scrollbar-color: var(--border-color) transparent;
}

/* Island background */
.filter-sidebar .island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.55; /* ↓ slightly lighter */
  pointer-events: none;
}

/* Filter content */
.filter-content {
  position: relative;
  z-index: 2;
  margin-top: 0.4rem; /* ↓ less gap above filters */
}

/* Filter section spacing */
.filter-section {
  margin-bottom: 0.8rem; /* ↓ compact sections */
}

/* Titles */
.filter-title {
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 0.4rem; /* ↓ reduced */
  color: var(--text-color);
  padding-bottom: 0.25rem; /* ↓ tighter */
  border-bottom: 1px solid var(--border-color);
}

/* Buttons */
.btn-ride {
  background-color: #6c757d;
  border: none;
  color: #fff;
  font-weight: 600;
  padding: 0.45rem 0.8rem; /* ↓ smaller */
  border-radius: 6px;
  transition: all 0.25s ease;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
}

.filter-actions {
  margin-top: 0.7rem; /* ↓ less space from filters */
  display: flex;
  flex-direction: column;
  gap: 0.4rem; /* ↓ smaller gap */
}

/* Divider */
.divider {
  height: 1px;
  width: 100%;
  background: linear-gradient(to right, transparent, var(--border-color), transparent);
  margin: 0.55rem 0; /* ↓ tighter spacing */
  opacity: 0.6;
  border-radius: 2px;
}

/* Mobile drawer */
.filter-sidebar {
  top: 0;
  left: -260px;
  height: 100vh;
  box-shadow: 0 5px 12px rgba(0, 0, 0, 0.25);
}

.filter-sidebar.open {
  left: 0;
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
  top: 4.2rem; /* ↓ from 4.5rem */
  left: 1rem;
  z-index: 1200;
  background-color: var(--primary-color);
  color: #fff;
  padding: 0.35rem 0.8rem; /* ↓ smaller */
  border-radius: 5px;
  display: flex;
  align-items: center;
  font-weight: 600;
  font-size: 0.82rem;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Close button */
.btn-close {
  background: none;
  border: none;
  font-size: 1.4rem; /* ↓ smaller */
  color: var(--text-color);
  position: absolute;
  top: 0.45rem; /* ↓ tighter */
  right: 0.5rem;
}

/* Desktop adjustments */
@media (min-width: 992px) {
  .filter-sidebar {
    position: relative;
    left: 0;
    height: auto;
    box-shadow: none;
    width: 240px;
    padding: 0.7rem 0.8rem; /* ↓ smaller padding */
  }

  .toggle-sidebar-btn,
  .btn-close,
  .sidebar-overlay {
    display: none;
  }

  .filter-sidebar-wrapper {
    padding-top: 0.7rem; /* ↓ from 1rem */
  }
}

/* Desktop width fine-tuning */
@media (min-width: 1440px) and (max-width: 1600px) {
  .filter-sidebar {
    width: 210px;
    padding: 0.75rem;
  }
}

@media (min-width: 1200px) and (max-width: 1439px) {
  .filter-sidebar {
    width: 190px;
    padding: 0.7rem;
  }
}

@media (min-width: 900px) and (max-width: 1199px) {
  .filter-sidebar {
    width: 180px;
    padding: 0.65rem;
  }
}

</style>
