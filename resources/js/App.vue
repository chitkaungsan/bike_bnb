<!-- App.vue -->
<template>
  <Toast />
  <div id="app">
    <router-view />
  </div>
</template>

<script setup>
import { watch } from 'vue';
import { useStore } from 'vuex';
import '../css/main.css'
import router from './router';
const store = useStore();
watch(
  () => store.getters['theme/currentTheme'],
  (newTheme) => {
    if (newTheme === 'dark') {
      document.body.classList.add('dark');
    } else {
      document.body.classList.remove('dark');
    }
  },
  { immediate: true } // Apply on initial load
);

// if (localStorage.getItem('token')) {
//   store.dispatch('auth/fetchUser').then(() => {
//     const role = store.getters['auth/userRole'];
//     if (role === 'admin') router.push({ name: 'Dashboard' });
//     else router.push({ name: 'Home' });
//   });
// }
</script>
