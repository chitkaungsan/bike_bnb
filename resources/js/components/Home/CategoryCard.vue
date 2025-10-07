<template>
  <div class="col-6 col-md-3" @click="$emit('click')">
    <div class="category-card text-center" :class="{ selected: selected }">
      <div class="category-icon">
        <font-awesome-icon :icon="icon" />
      </div>
      <h5>{{ title }}</h5>
    </div>
  </div>
</template>

<script setup>
defineProps({
  icon: Array, // ['fas', 'mountain-sun']
  title: String,
  selected: Boolean, // is this card selected
});
</script>

<style scoped>
.category-card {
  padding: 1.5rem;
  border-radius: var(--border-radius-md);
  border: 1px solid var(--border-color);
  transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
  background-color: var(--section-bg-color);
  cursor: pointer;
}
.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.07);
}
.category-card.selected {
  border-color: var(--primary-color);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}
.category-icon {
  font-size: 2.5rem;
  color: var(--primary-color);
  margin-bottom: 1rem;
}
h5 {
  font-weight: 600;
  color: var(--text-color);
}
</style>
