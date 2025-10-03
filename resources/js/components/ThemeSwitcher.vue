<template>
  <button @click="toggleTheme" class="btn btn-secondary">
    <!-- Show a sun icon for light mode, moon for dark mode -->
    <span v-if="currentTheme === 'light'">🌙</span>
    <span v-else>☀️</span>
  </button>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// Get the current theme reactively from the Vuex store's 'theme' module
const currentTheme = computed(() => store.getters['theme/currentTheme']);

// Method to dispatch the action that toggles the theme
const toggleTheme = () => {
  store.dispatch('theme/toggleTheme');
};
</script>
