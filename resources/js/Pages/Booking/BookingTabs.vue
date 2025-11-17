<template>
  <ul class="nav nav-tabs booking-tabs">
    <li class="nav-item">
      <a
        class="nav-link"
        :class="{ active: activeTab === 'upcoming' }"
        @click="$emit('update:activeTab', 'upcoming')"
      >Upcoming</a>
    </li>
    <li class="nav-item">
      <a
        class="nav-link"
        :class="{ active: activeTab === 'past' }"
        @click="$emit('update:activeTab', 'past')"
      >Past</a>
    </li>
  </ul>
</template>

<script setup>
defineProps({
  activeTab: String
});
</script>

<style scoped>
.booking-tabs {
  border-bottom: 2px solid var(--border-color);
  margin-bottom: 2rem;
}
.booking-tabs .nav-link {
  color: var(--light-text-color);
  font-weight: 500;
  border: none;
  padding-bottom: 1rem;
}
.booking-tabs .nav-link.active {
  color: var(--primary-color);
  border-bottom: 2px solid var(--primary-color);
  background: transparent;
}
.nav-link{
  cursor: pointer;
}
</style>
