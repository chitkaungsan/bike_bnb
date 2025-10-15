<template>
  <aside class="sidebar">
    <div class="sidebar-header">
      <a href="/" class="brand-logo text-decoration-none text-dark" >
      <font-awesome-icon :icon="faBicycle" class="logo-icon" />
      <h2 class="brand-name">BikeDash</h2>
      <span>Renter Panel</span>
      </a>
    </div>

    <nav class="sidebar-nav">
      <router-link 
        to="/owner/dashboard"
        class="nav-link"
        :class="{ active: $route.name === 'owner.dashboard' }">
        <font-awesome-icon :icon="faChartLine" />
        <span>Overview</span>
      </router-link>

      <router-link 
        to="/owner/bikes"
        class="nav-link"
        :class="{ active: $route.name === 'owner.bikes' }">
        <font-awesome-icon :icon="faBicycle" />
        <span>Bikes</span>
      </router-link>

      <router-link 
        to="/owner/bookings"
        class="nav-link"
        :class="{ active: $route.name === 'owner.bookings' }">
        <font-awesome-icon :icon="faCalendarCheck" />
        <span>Bookings</span>
      </router-link>

      <router-link 
        to="/owner/payouts"
        class="nav-link"
        :class="{ active: $route.name === 'owner.payouts' }">
        <font-awesome-icon :icon="faWallet" />
        <span>Payouts</span>
      </router-link>
    </nav>

    <div class="sidebar-footer">
      <a href="#">
        <logout-button />
      </a>
    </div>
  </aside>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { 
  faChartLine, faBicycle, faCalendarCheck, faWallet 
} from '@fortawesome/free-solid-svg-icons';
import LogoutButton from '../LogoutButton.vue';
</script>

<style scoped>
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
