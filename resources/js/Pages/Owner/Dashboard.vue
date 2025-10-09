<template>
  <div class="dashboard-page">
    <div class="card">
      <h2>Welcome to your Dashboard</h2>
      <p>
        For the first step you need to add a store first and then add a bike. You can make
        in Store tab and Bikes tab.
      </p>
    </div>
  </div>
</template>

<script setup></script>

<style scoped>
.dashboard-page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.card {
  background: var(--section-bg-color);
  padding: 2rem;
  border-radius: var(--border-radius-md);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
</style>
