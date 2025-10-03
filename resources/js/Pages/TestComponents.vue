<template>
  <div id="main-app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <router-link to="/" class="navbar-brand">MyApp</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul v-if="isAuthenticated" class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
            </li>
            <li class="nav-item">
              <a @click="logout" class="nav-link" style="cursor: pointer;">Logout</a>
            </li>
          </ul>
          <ul v-else class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link to="/login" class="nav-link">Login</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/register" class="nav-link">Register</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container mt-4">
      <div class="alert alert-success" role="alert">
        Bootstrap 5 is working!
      </div>
    </main>
  </div>
</template>

<script setup>
// ... your script setup remains the same
import { computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();
const isAuthenticated = computed(() => store.getters['users/isAuthenticated']);

const logout = async () => {
  await store.dispatch('users/logout');
  router.push({ name: 'Login' });
};
</script>

<style>
/* You can remove the old scoped styles or keep them.
  For this example, let's just use a global style block
  to let Bootstrap handle most things.
*/
body {
  background-color: #f8f9fa;
}
</style>