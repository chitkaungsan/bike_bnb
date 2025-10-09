<template>
  <aside :class="['sidebar', { 'sidebar-collapsed': collapsed }]">
    <div class="sidebar-header">
      <router-link to="/" class="brand-logo">
        <font-awesome-icon :icon="faBicycle" class="logo-icon" />
        <h2 class="brand-name" v-if="!collapsed">BikeDash</h2>
      </router-link>
    </div>

    <nav class="sidebar-nav">
      <router-link
        to="/owner/dashboard"
        class="nav-link"
        v-tooltip.right="{ value: 'Overview', disabled: !collapsed }"
      >
        <font-awesome-icon :icon="faChartLine" />
        <span v-if="!collapsed">Overview</span>
      </router-link>

      <router-link
        to="/owner/stores"
        class="nav-link"
        v-tooltip.right="{ value: 'Stores', disabled: !collapsed }"
      >
        <font-awesome-icon :icon="faStore" />
        <span v-if="!collapsed">Stores</span>
      </router-link>

      <router-link
        to="/owner/bikes"
        class="nav-link"
        v-tooltip.right="{ value: 'Bikes', disabled: !collapsed }"
      >
        <font-awesome-icon :icon="faBicycle" />
        <span v-if="!collapsed">Bikes</span>
      </router-link>

      <router-link
        to="/owner/bookings"
        class="nav-link"
        v-tooltip.right="{ value: 'Bookings', disabled: !collapsed }"
      >
        <font-awesome-icon :icon="faCalendarCheck" />
        <span v-if="!collapsed">Bookings</span>
      </router-link>

      <router-link
        to="/owner/payouts"
        class="nav-link"
        v-tooltip.right="{ value: 'Payouts', disabled: !collapsed }"
      >
        <font-awesome-icon :icon="faWallet" />
        <span v-if="!collapsed">Payouts</span>
      </router-link>
    </nav>

    <div class="sidebar-footer">
      <div class="nav-link collapse-toggle" @click="emit('toggle')">
        <font-awesome-icon :icon="collapsed ? faChevronRight : faChevronLeft" />
        <span v-if="!collapsed">Collapse</span>
      </div>
      <logout-button :collapsed="collapsed" />
    </div>
  </aside>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faChartLine,
  faBicycle,
  faCalendarCheck,
  faWallet,
  faStore,
  faChevronLeft,
  faChevronRight,
} from "@fortawesome/free-solid-svg-icons";
import LogoutButton from "../LogoutButton.vue";

defineProps({
  collapsed: Boolean,
});
const emit = defineEmits(["toggle"]);
</script>

<style scoped>
.sidebar {
  width: 260px;
  background-color: var(--section-bg-color);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  padding: 1.5rem 1rem;
  transition: transform 0.3s ease; /* CHANGED: Now transitioning transform */
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  z-index: 1000;
}

/* --- MODIFICATION START --- */
/* Instead of shrinking, the sidebar now slides completely off-screen */
.sidebar-collapsed {
  transform: translateX(-100%);
}
/* --- MODIFICATION END --- */

.sidebar-header {
  margin-bottom: 2rem;
  padding: 0 0.5rem;
}
.brand-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  text-decoration: none;
  color: var(--text-color);
}
.logo-icon {
  font-size: 1.8rem;
  color: var(--primary-color);
}
.brand-name {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
  white-space: nowrap;
}
.sidebar-nav {
  display: flex;
  flex-direction: column;
  /* MODIFIED: Align items to the start for better look when sliding */
  align-items: flex-start;
}
.nav-link {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 0.9rem 1.5rem;
  border-radius: var(--border-radius-md);
  color: var(--light-text-color);
  font-weight: 500;
  margin-bottom: 0.5rem;
  text-decoration: none;
  transition: background-color 0.2s, color 0.2s;
  cursor: pointer;
  white-space: nowrap;
}

.nav-link:hover {
  background-color: var(--background-color);
  color: var(--text-color);
}
.router-link-active.nav-link {
  background-color: var(--primary-color);
  color: white;
  box-shadow: 0 4px 10px rgba(99, 193, 162, 0.3);
}
.nav-link svg {
  min-width: 24px;
  font-size: 1.1rem;
  margin-right: 1rem;
}

.sidebar-footer {
  margin-top: auto;
  display: flex;
  flex-direction: column;
  align-items: flex-start; /* MODIFIED */
}

@media (max-width: 767px) {
  /* This rule is now covered by the main .sidebar-collapsed rule */
  .sidebar {
    transform: translateX(-100%);
  }
  .sidebar:not(.sidebar-collapsed) {
    transform: translateX(0);
    width: 260px;
  }
  .collapse-toggle {
    display: none;
  }
}
</style>
