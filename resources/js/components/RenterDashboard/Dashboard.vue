<template>
  <div class="owner-dashboard">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <font-awesome-icon :icon="faBicycle" class="logo-icon" />
        <h2 class="brand-name">BikeDash</h2>
        <span>Renter Panel</span>
      </div>
      <nav class="sidebar-nav">
        <a href="#" @click.prevent="setActiveView('Overview')" :class="{ active: activeView === 'Overview' }">
          <font-awesome-icon :icon="faChartLine" />
          <span>Overview</span>
        </a>
        <!-- <a href="#" @click.prevent="setActiveView('BikeManagement')" :class="{ active: activeView === 'BikeManagement' }">
          <font-awesome-icon :icon="faCog" />
          <span>Bike Management</span>
        </a>
        <a href="#" @click.prevent="setActiveView('BookingManagement')" :class="{ active: activeView === 'BookingManagement' }">
          <font-awesome-icon :icon="faCalendarCheck" />
          <span>Bookings</span>
        </a>
        <a href="#" @click.prevent="setActiveView('Payouts')" :class="{ active: activeView === 'Payouts' }">
          <font-awesome-icon :icon="faWallet" />
          <span>Payouts</span>
        </a> -->
      </nav>
       <div class="sidebar-footer">
         <a href="#">
            <font-awesome-icon :icon="faSignOutAlt" />
            <logout-button />
         </a>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
      <header class="main-header">
        <h1>{{ currentTitle }}</h1>
        <div class="header-actions">
           <button class="btn btn-primary"><font-awesome-icon :icon="faPlus" /> Add New Bike</button>
        </div>
      </header>
      
      <!-- Dynamic Component View -->
      <div class="content-area">
        <component :is="views[activeView]" />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faChartLine, faCog, faCalendarCheck, faWallet, faPlus, faBicycle, faSignOutAlt } from '@fortawesome/free-solid-svg-icons';
import LogoutButton from '../../components/LogoutButton.vue';

// --- State for active view ---
const activeView = ref('Overview');

const setActiveView = (view) => {
  activeView.value = view;
};

// --- Dynamic Component Imports ---
// This approach lazy-loads the components, which is good for performance.
 const views = {
  Overview: defineAsyncComponent(() => import('./components/Overview.vue')),
  // BikeManagement: defineAsyncComponent(() => import('./components/BikeManagementView.vue')),
  // BookingManagement: defineAsyncComponent(() => import('./components/BookingManagementView.vue')),
  // Payouts: defineAsyncComponent(() => import('./components/PayoutsView.vue')),
};

// --- Computed property for the header title ---
const currentTitle = computed(() => {
  switch(activeView.value) {
    case 'BikeManagement': return 'Bike Management';
    case 'BookingManagement': return 'Booking Management';
    case 'Payouts': return 'Payouts';
    default: return 'Dashboard';
  }
});
</script>

<style scoped>
/* Using global variables for theming */
.owner-dashboard {
  display: flex;
  min-height: 100vh;
  background-color: var(--background-color);
  color: var(--text-color);
}

/* --- Sidebar --- */
.sidebar {
  width: 260px;
  background-color: var(--section-bg-color);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  transition: background-color 0.3s, border-color 0.3s;
}

.sidebar-header {
  text-align: center;
  margin-bottom: 2rem;
}
.sidebar-header .logo-icon {
  font-size: 2.5rem;
  color: var(--primary-color);
}
.sidebar-header .brand-name {
  font-weight: 700;
  margin: 0.5rem 0 0.2rem;
}
.sidebar-header span {
    font-size: 0.8rem;
    color: var(--light-text-color);
    text-transform: uppercase;
    letter-spacing: 1px;
}


.sidebar-nav a {
  display: flex;
  align-items: center;
  padding: 0.9rem 1rem;
  border-radius: var(--border-radius-md);
  color: var(--light-text-color);
  font-weight: 500;
  margin-bottom: 0.5rem;
  transition: background-color 0.2s, color 0.2s;
}

.sidebar-nav a:hover {
  background-color: var(--background-color);
  color: var(--text-color);
}

.sidebar-nav a.active {
  background-color: var(--primary-color);
  color: white;
  box-shadow: 0 4px 10px rgba(99, 193, 162, 0.3);
}

.sidebar-nav a svg {
  margin-right: 1rem;
  width: 20px;
  text-align: center;
}

.sidebar-footer {
    margin-top: auto;
}

/* --- Main Content --- */
.main-content {
  flex: 1;
  padding: 2rem;
  overflow-y: auto;
}

.main-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 1rem;
}

.main-header h1 {
  font-weight: 700;
}

/* --- Responsive Design --- */
@media (max-width: 768px) {
  .owner-dashboard {
    flex-direction: column;
  }
  .sidebar {
    width: 100%;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    height: auto;
  }
  .sidebar-header {
    margin-bottom: 0;
  }
  .sidebar-nav {
    display: flex;
  }
  .sidebar-nav a {
    margin-bottom: 0;
    margin-left: 0.5rem;
  }
   .sidebar-nav a span {
    display: none; /* Hide text on mobile nav */
  }
  .main-content {
    padding: 1rem;
  }
  .main-header h1 {
    font-size: 1.5rem;
  }
}
</style>
