<template>
  <div class="container my-5">
    <div class="row g-5">
      <div class="col-lg-7">
        <div class="main-content">
          <h1>Entire rental unit in Bangkok, Thailand</h1>
          <p class="lead">4 guests · 1 bedroom · 2 beds · 1 bath</p>
          <hr />
          <h3>Brandnew spacious 1bedroom</h3>
          <p>
            RIGHT on the BTS N7 station, Saphankhwai. 5 mins walk to Chatuchak Market...
            (Your listing description goes here)
          </p>
          <p v-for="n in 30" :key="n" class="py-3">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
            nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
          </p>
        </div>
      </div>

      <div class="col-lg-5">
        <div ref="rightColumnRef">
          <BookingPanel :price-per-night="2671" :parent-container="rightColumnRef" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import BookingPanel from "./BookingPanel.vue";

// Create a ref for the parent container of the booking panel
const rightColumnRef = ref(null);
</script>

<style scoped>
.main-content {
  /* This ensures the parent container on the right has a defined height to scroll against */
  min-height: 200vh;
}
</style>
