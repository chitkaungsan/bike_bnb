<template>
  <div class="owner-dashboard" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
    <div
      class="sidebar-overlay"
      @click="isSidebarCollapsed = true"
      v-if="!isSidebarCollapsed"
    ></div>

    <OwnerSidebar
      :collapsed="isSidebarCollapsed"
      @toggle="isSidebarCollapsed = !isSidebarCollapsed"
    />

    <main class="main-content">
      <OwnerHeader
        :title="$route.meta.title || 'Dashboard'"
        @toggle-sidebar="isSidebarCollapsed = !isSidebarCollapsed"
      />
      <div class="content-area">
        <Toast />
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from "vue";
import { useRoute } from "vue-router";
import OwnerSidebar from "../../components/Owner/OwnerSidebar.vue";
import OwnerHeader from "../../components/Owner/OwnerHeader.vue";

const isSidebarCollapsed = ref(true);
const route = useRoute();

watch(route, () => {
  if (window.innerWidth < 768) {
    isSidebarCollapsed.value = true;
  }
});

const handleResize = () => {
  if (window.innerWidth < 768) {
    isSidebarCollapsed.value = true;
  }
};

onMounted(() => {
  window.addEventListener("resize", handleResize);
  handleResize();
});

onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});
</script>

<style scoped>
.owner-dashboard {
  display: flex;
  min-height: 100vh;
  background-color: var(--background-color);
  color: var(--text-color);
  transition: padding-left 0.3s ease;
}

.main-content {
  flex: 1;
  padding: 1rem 1.5rem;
  overflow-y: auto;
  transition: margin-left 0.3s ease;
  margin-left: 260px; /* Default sidebar width */
}

/* --- MODIFICATION START --- */
/* When sidebar is collapsed, content margin is now 0 to make it full screen */
.owner-dashboard.sidebar-collapsed .main-content {
  margin-left: 0; /* CHANGED from 80px */
}
/* --- MODIFICATION END --- */

.sidebar-overlay {
  display: none;
}

@media (max-width: 767px) {
  .main-content {
    margin-left: 0;
    padding: 1rem;
    width: 100%;
  }

  .sidebar-overlay {
    display: block;
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 998;
    opacity: 1;
    transition: opacity 0.3s ease;
  }

  .owner-dashboard.sidebar-collapsed .sidebar-overlay {
    opacity: 0;
    pointer-events: none;
  }
}
</style>
