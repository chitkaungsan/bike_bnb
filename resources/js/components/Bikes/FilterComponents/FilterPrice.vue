<template>
  <div class="filter-section text-start compact-price">
    <h5 class="filter-title">Budget per day</h5>
    <div class="slider-wrapper">
      <input
        type="range"
        class="form-range"
        :min="min"
        :max="max"
        :step="step"
        v-model="selectedPrice"
        @input="updateLabel"
      />
      <div class="price-bubble" :style="{ left: bubblePosition + '%' }">
        ${{ selectedPrice }}
      </div>
    </div>
    <div class="price-labels">
      <span>${{ min }}</span>
      <span>${{ max }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";

const min = 0;
const max = 200;
const step = 5;
const selectedPrice = ref(100);
const bubblePosition = ref(0);

const updateLabel = () => {
  const percent = ((selectedPrice.value - min) / (max - min)) * 100;
  bubblePosition.value = Math.min(Math.max(percent, 5), 95);
};

onMounted(updateLabel);
watch(selectedPrice, updateLabel);
</script>

<style scoped>
.compact-price {
  padding: 0.25rem 0 0;
}

/* Title smaller */
.filter-title {
  margin-bottom: 0.3rem;
}

/* Slider wrapper */
.slider-wrapper {
  position: relative;
  width: 100%;
}

/* Slider bar */
.form-range {
  width: 100%;
  height: 4px;
  border-radius: 4px;
  background: var(--border-color);
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  margin: 0.4rem 0;
}

/* Thumb */
.form-range::-webkit-slider-thumb {
  appearance: none;
  height: 12px;
  width: 12px;
  border-radius: 50%;
  background: var(--primary-color);
  cursor: pointer;
  transition: all 0.2s ease;
}
.form-range::-webkit-slider-thumb:hover {
  transform: scale(1.1);
  background: var(--primary-hover-color);
}

/* Bubble */
.price-bubble {
  position: absolute;
  top: -1.3rem;
  transform: translateX(-50%);
  background: var(--primary-color);
  color: #fff;
  padding: 0.15rem 0.4rem;
  border-radius: 10px;
  font-size: 1.0rem;
  font-weight: 600;
  line-height: 1;
  white-space: nowrap;
  transition: left 0.15s ease;
}

/* Labels below */
.price-labels {
  display: flex;
  justify-content: space-between;
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: var(--secondary-text-color);
}

/* Dark mode bubble */
:root[data-bs-theme="dark"] .price-bubble {
  background-color: var(--primary-hover-color);
}
</style>
