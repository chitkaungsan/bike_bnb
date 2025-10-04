<template>
  <button 
    @click="toggleTheme" 
    class="btn d-flex align-items-center justify-content-center rounded-circle shadow-sm theme-toggle-btn"
    :class="currentTheme === 'light' ? 'btn-dark' : 'btn-light'"
    aria-label="Toggle Theme"
  >
    <!-- Dark mode icon -->
    <i 
      v-if="currentTheme === 'light'" 
      class="bi bi-moon-fill fs-6 text-light"
    ></i>
    
    <!-- Light mode icon -->
    <i 
      v-else 
      class="bi bi-sun-fill fs-6 text-dark"
    ></i>
  </button>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const currentTheme = computed(() => store.getters['theme/currentTheme'])

const toggleTheme = () => {
  store.dispatch('theme/toggleTheme')
}
</script>

<style scoped>
.theme-toggle-btn {
  width: 2rem;
  height: 2rem;
  transition: all 0.3s ease-in-out;
}
.theme-toggle-btn:hover {
  transform: rotate(15deg) scale(1.1);
}
</style>
