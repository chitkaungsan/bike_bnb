<template>
  <div class="owner-dashboard">
    <OwnerSidebar />

    <main class="main-content">
      <OwnerHeader :title="$route.meta.title || 'Dashboard'" />
      <div class="content-area">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
import OwnerSidebar from "../Owner/OwnerSidebar.vue";
import OwnerHeader from "../Owner/OwnerHeader.vue";
</script>

<style scoped>
.owner-dashboard {
  display: flex;
  min-height: 100vh;
  background-color: var(--background-color);
  color: var(--text-color);
}

.main-content {
  flex: 1;
  padding: 1rem;
  overflow-y: auto;
}

/* Responsive Design */
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
  .sidebar-nav {
    display: flex;
  }
  .sidebar-nav a span {
    display: none;
  }
  .main-content {
    padding: 1rem;
  }
  .main-header h1 {
    font-size: 1.5rem;
  }
}
</style>
