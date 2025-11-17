<template>
  <div class="owner-dashboard">
    <AdminSidebar />

    <main class="main-content">
      <AdminHeader :title="$route.meta.title || 'Dashboard'" />
      <div class="content-area">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
import AdminSidebar from '../Admin/AdminSidebar.vue';
import AdminHeader from '../Admin/AdminHeader.vue';
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
  padding: 2rem;
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
