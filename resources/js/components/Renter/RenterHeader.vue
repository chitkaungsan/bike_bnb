<template>
  <header class="main-header">
    <h1>{{ title }}</h1>
    <div class="header-actions">
      <button class="btn btn-primary">
        <font-awesome-icon :icon="faPlus" /> Add New Bike
      </button>
    </div>
  </header>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';

defineProps({
  title: String
});
</script>

<style scoped>
.main-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 1rem;
}
.main-header h1 {
  font-weight: 700;
}
</style>
