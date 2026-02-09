<template>
  <div class="renter-dashboard" :class="{ 'sidebar-collapsed': isSidebarCollapsed }">
    <!-- Overlay (mobile) -->
    <div
      class="sidebar-overlay"
      v-show="!isSidebarCollapsed"
      @click="isSidebarCollapsed = true"
    ></div>

    <!-- Sidebar -->
    <RenterSidebar
      :collapsed="isSidebarCollapsed"
      @toggle="isSidebarCollapsed = !isSidebarCollapsed"
    />

    <!-- Main -->
    <main class="main-content">
      <RenterHeader
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
import RenterSidebar from "@/components/Renter/RenterSidebar.vue";
import RenterHeader from "@/components/Renter/RenterHeader.vue";

const isSidebarCollapsed = ref(false);
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
.renter-dashboard {
  display: flex;
  min-height: 100vh;
  background-color: var(--background-color);
  color: var(--text-color);
}

/* Main content */
.main-content {
  flex: 1;
  padding: 1rem 1.5rem;
  overflow-y: auto;
  margin-left: 260px;
  transition: margin-left 0.3s ease;
}

/* Collapse effect */
.renter-dashboard.sidebar-collapsed .main-content {
  margin-left: 0;
}

/* Overlay */
.sidebar-overlay {
  display: none;
}

@media (max-width: 767px) {
  .main-content {
    margin-left: 0;
    padding: 1rem;
  }

  .sidebar-overlay {
    display: block;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 998;
  }

  .renter-dashboard.sidebar-collapsed .sidebar-overlay {
    opacity: 0;
    pointer-events: none;
  }
}
</style>
