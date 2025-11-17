<template>
  <div class="container-fluid">
    <HomeNav />
    <div>
      <MyBookingLayout />
    </div>
  </div>
</template>

<script setup>
import { onMounted, nextTick } from 'vue';
import HomeNav from '../../components/Layout/HomeNav.vue';
import MyBookingLayout from './MyBookingLayout.vue';

// Scroll to top when entering page
onMounted(async () => {
  await nextTick();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});
</script>
