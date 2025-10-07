<template>
  <aside :class="['sidebar', { 'sidebar-collapsed': collapsed }]">
    <div class="sidebar-header">
      <div class="sidebar-toggle" @click="collapsed = !collapsed">
        <span>&#9776;</span>
      </div>
      <font-awesome-icon :icon="faBicycle" class="logo-icon" />
      <h2 class="brand-name" v-if="!collapsed">BikeDash</h2>
      <span v-if="!collapsed">Owner Panel</span>
    </div>

    <nav class="sidebar-nav" v-if="!collapsed">
      <router-link
        to="/owner/dashboard"
        class="nav-link"
        :class="{ active: $route.name === 'owner.dashboard' }"
      >
        <font-awesome-icon :icon="faChartLine" />
        <span>Overview</span>
      </router-link>

      <router-link
        to="/owner/stores"
        class="nav-link"
        :class="{ active: $route.path.startsWith('/owner/stores') }"
      >
        <font-awesome-icon :icon="faStore" />
        <span>Stores</span>
      </router-link>

      <router-link
        to="/owner/bikes"
        class="nav-link"
        :class="{ active: $route.path.startsWith('/owner/bikes') }"
      >
        <font-awesome-icon :icon="faBicycle" />
        <span>Bikes</span>
      </router-link>

      <router-link
        to="/owner/bookings"
        class="nav-link"
        :class="{ active: $route.name === 'owner.bookings' }"
      >
        <font-awesome-icon :icon="faCalendarCheck" />
        <span>Bookings</span>
      </router-link>

      <router-link
        to="/owner/payouts"
        class="nav-link"
        :class="{ active: $route.name === 'owner.payouts' }"
      >
        <font-awesome-icon :icon="faWallet" />
        <span>Payouts</span>
      </router-link>
    </nav>

    <div class="sidebar-footer" v-if="!collapsed">
      <logout-button />
    </div>
  </aside>
</template>
<script setup>
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faChartLine,
  faBicycle,
  faCalendarCheck,
  faWallet,
  faStore,
} from "@fortawesome/free-solid-svg-icons";
import LogoutButton from "../LogoutButton.vue";

const collapsed = ref(false);
</script>
<style scoped>
.sidebar {
  width: 260px;
  background-color: var(--section-bg-color);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  transition: all 0.3s ease;
  position: relative;
}

/* Collapsed state */
.sidebar-collapsed {
  width: 60px;
  padding: 1rem 0.5rem;
}

.sidebar-header {
  text-align: center;
  margin-bottom: 2rem;
  position: relative;
}

.sidebar-toggle {
  display: none;
  position: absolute;
  top: 0.5rem;
  left: 0.5rem;
  font-size: 1.5rem;
  cursor: pointer;
}

/* Show toggle on mobile */
@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    top: 0;
    left: -100%;
    height: 100%;
    z-index: 999;
    transition: all 0.3s ease;
  }
  .sidebar.sidebar-collapsed {
    left: 0;
  }
  .sidebar-toggle {
    display: block;
  }
}

.sidebar-nav .nav-link {
  display: flex;
  align-items: center;
  padding: 0.9rem 1rem;
  border-radius: var(--border-radius-md);
  color: var(--light-text-color);
  font-weight: 500;
  margin-bottom: 0.5rem;
  text-decoration: none;
  transition: background-color 0.2s, color 0.2s;
}

.sidebar-nav .nav-link:hover {
  background-color: var(--background-color);
  color: var(--text-color);
}

.sidebar-nav .nav-link.active {
  background-color: var(--primary-color);
  color: white;
  box-shadow: 0 4px 10px rgba(99, 193, 162, 0.3);
}

.sidebar-nav .nav-link svg {
  margin-right: 1rem;
  width: 20px;
  text-align: center;
}

.sidebar-footer {
  margin-top: auto;
}
</style>
