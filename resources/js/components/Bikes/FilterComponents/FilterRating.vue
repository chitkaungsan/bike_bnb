<template>
  <div class="filter-section text-start">
    <h5 class="filter-title">Rider rating</h5>

    <div class="rating-selector">
      <font-awesome-icon
        v-for="i in 5"
        :key="i"
        :icon="faStar"
        class="star-icon"
        :class="{ active: i <= selectedRating }"
        @click="selectRating(i)"
      />
      <span v-if="selectedRating" class="rating-label">
        {{ selectedRating }} star{{ selectedRating > 1 ? 's' : '' }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { faStar } from "@fortawesome/free-solid-svg-icons";

const selectedRating = ref(0);

const selectRating = (value) => {
  // toggle if same star clicked
  selectedRating.value = selectedRating.value === value ? 0 : value;
  // Optional: emit('update:rating', selectedRating.value)
};
</script>

<style scoped>
.rating-selector {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  justify-content: center;
  margin-top: 0.5rem;
  cursor: pointer;
}

/* ⭐ Default star */
.star-icon {
  font-size: 1.4rem;
  color: var(--border-color);
  transition: all 0.25s ease;
}

/*  Active stars (highlighted) */
.star-icon.active {
  color: #ffc107;
  transform: scale(1.1);
}

/*  Hover feedback */
.star-icon:hover,
.star-icon:hover ~ .star-icon {
  color: #ffdd67;
}

/* 🌿Label */
.rating-label {
  margin-left: 0.6rem;
  font-weight: 500;
  color: var(--text-color);
  font-size: 0.95rem;
}

/* 🏝️ Centered and breathing */
.filter-section {
  text-align: center;
}
</style>
