<template>
  <aside
    v-if="user && user.role === 'renter'"
    :class="['sidebar', { 'sidebar-collapsed': collapsed }]"
  >
    <!-- Header -->
    <div class="sidebar-header">
      <a href="/" class="brand-logo">
        <font-awesome-icon :icon="faBicycle" class="logo-icon" />
        <h2 class="brand-name" v-if="!collapsed">BikeDash</h2>
      </a>
      <small>Renter Panel</small>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <router-link to="/renter/dashboard" class="nav-link">
        <font-awesome-icon :icon="faChartLine" />
        <span v-if="!collapsed">Overview</span>
      </router-link>

      <router-link to="/renter/bikes" class="nav-link">
        <font-awesome-icon :icon="faBicycle" />
        <span v-if="!collapsed">Bikes</span>
      </router-link>

      <router-link to="/renter/bookings" class="nav-link">
        <font-awesome-icon :icon="faCalendarCheck" />
        <span v-if="!collapsed">Bookings</span>
      </router-link>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
      <div class="nav-link collapse-toggle" @click="emit('toggle')">
        <font-awesome-icon :icon="collapsed ? faChevronRight : faChevronLeft" />
        <span v-if="!collapsed">Collapse</span>
      </div>

      <LogoutButton :collapsed="collapsed" />
    </div>
  </aside>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faChartLine,
  faBicycle,
  faCalendarCheck,
  faChevronLeft,
  faChevronRight,
} from "@fortawesome/free-solid-svg-icons";
import LogoutButton from "../LogoutButton.vue";

defineProps({
  collapsed: Boolean,
});

const emit = defineEmits(["toggle"]);

const store = useStore();
const user = computed(() => store.state.auth.user);
</script>

<style scoped>
.sidebar {
  width: 260px;
  background-color: var(--section-bg-color);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  padding: 1.5rem 1rem;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  transition: transform 0.3s ease;
  z-index: 1000;
}

.sidebar-collapsed {
  transform: translateX(-100%);
}

/* Header */
.sidebar-header {
  margin-bottom: 2rem;
  text-align: center;
}

.brand-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  color: var(--text-color);
  text-decoration: none;
}

.logo-icon {
  font-size: 1.8rem;
  color: var(--primary-color);
}

.brand-name {
  font-size: 1.4rem;
  font-weight: 700;
}

/* Nav */
.sidebar-nav {
  display: flex;
  flex-direction: column;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.9rem 1.2rem;
  border-radius: var(--border-radius-md);
  color: var(--light-text-color);
  text-decoration: none;
  margin-bottom: 0.5rem;
}

.nav-link:hover {
  background-color: var(--background-color);
  color: var(--text-color);
}

.router-link-active.nav-link {
  background-color: var(--primary-color);
  color: white;
}

.nav-link svg {
  margin-right: 1rem;
}

/* Footer */
.sidebar-footer {
  margin-top: auto;
}

.collapse-toggle {
  cursor: pointer;
}

/* Mobile */
@media (max-width: 767px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar:not(.sidebar-collapsed) {
    transform: translateX(0);
  }

  .collapse-toggle {
    display: none;
  }
}
</style>
