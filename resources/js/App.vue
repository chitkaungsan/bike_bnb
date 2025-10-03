<!-- App.vue -->
<template>
  <div id="app">
    <!-- Example navigation -->
    <nav class="p-4 bg-gray-800 text-white flex gap-4">
      <RouterLink to="/">Home</RouterLink>
      <RouterLink to="/login">Login</RouterLink>
    </nav>

    <!-- This is where your Home.vue (or any route) will render -->
    <router-view />
  </div>
</template>

<script setup>
</script>
