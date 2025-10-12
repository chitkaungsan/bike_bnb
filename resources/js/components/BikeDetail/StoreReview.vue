<template>
  <div
    class="info-card d-flex align-items-center justify-content-between p-3 border rounded shadow-sm"
  >
    <!-- Left Section: Icon + Text -->
    <div class="d-flex align-items-center gap-2">
      <img
        :src="store_logo"
        alt="Store Logo"
        class="bike-logo rounded-circle"
        width="40"
        height="40"
      />
      <div class="d-flex flex-column">
        <span class="fw-semibold">{{ store_name }}</span>
        <span class="text-muted small">{{ address }}</span>
      </div>
    </div>

    <!-- Right Section: Rating -->
    <div class="d-flex align-items-center gap-3">
      <div class="text-center">
        <div class="fw-bold">4.94</div>
        <div class="text-warning">
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
          <i class="bi bi-star-fill"></i>
        </div>
      </div>
      <div class="text-muted small">278 Reviews</div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  store_logo: {
    type: String,
    required: true,
  },
  store_name: {
    type: String,
    required: true,
  },
  address: {
    type: String,
    required: true,
  },
});
</script>
<style scoped>
.info-card {
  background-color: var(--section-bg-color);
  color: var(--text-color);
  gap: 1rem;
  transition: background-color 0.3s, color 0.3s;
}
.info-card i {
  vertical-align: middle;
}
.bike-logo {
  width: 50px;
  height: 50px;
}
</style>
