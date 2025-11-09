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

/* 🖥️ 1600px – 1920px (Large desktop / 27"–32") */
@media (min-width: 1600px) and (max-width: 1920px) {
  .filter-title {
    font-size: 1.1rem;
  }
  .form-range {
    height: 4px;
  }
  .form-range::-webkit-slider-thumb {
    height: 12px;
    width: 12px;
  }
  .price-bubble {
    top: -1.4rem;
    font-size: 1.0rem;
  }
  .price-labels {
    margin-top: 0.25rem;
    font-size: 0.8rem;
  }
}

/* 🖥️ 1440px – 1599px (Standard laptop / desktop) */
@media (min-width: 1440px) and (max-width: 1599px) {
  .filter-title {
    font-size: 1.0rem;
  }
  .form-range {
    height: 3px;
  }
  .form-range::-webkit-slider-thumb {
    height: 10px;
    width: 10px;
  }
  .price-bubble {
    top: -1.2rem;
    font-size: 0.9rem;
  }
  .price-labels {
    margin-top: 0.2rem;
    font-size: 0.7rem;
  }
}

/* 💻 1300px – 1439px (Mid laptop) */
@media (min-width: 1300px) and (max-width: 1439px) {
  .filter-title {
    font-size: 0.95rem;
  }
  .form-range {
    height: 3px;
  }
  .form-range::-webkit-slider-thumb {
    height: 9px;
    width: 9px;
  }
  .price-bubble {
    top: -1.1rem;
    font-size: 0.85rem;
  }
  .price-labels {
    margin-top: 0.2rem;
    font-size: 0.68rem;
  }
}

/* 💻 1200px – 1299px (Small laptop) */
@media (min-width: 1200px) and (max-width: 1299px) {
  .filter-title {
    font-size: 0.9rem;
  }
  .form-range {
    height: 2.8px;
  }
  .form-range::-webkit-slider-thumb {
    height: 8px;
    width: 8px;
  }
  .price-bubble {
    top: -1.0rem;
    font-size: 0.8rem;
  }
  .price-labels {
    margin-top: 0.15rem;
    font-size: 0.65rem;
  }
}

/* 💻 992px – 1199px (Tablet landscape / small MacBook Air) */
@media (min-width: 992px) and (max-width: 1199px) {
  .filter-title {
    font-size: 0.85rem;
  }
  .form-range {
    height: 2.5px;
  }
  .form-range::-webkit-slider-thumb {
    height: 7px;
    width: 7px;
  }
  .price-bubble {
    top: -0.9rem;
    font-size: 0.75rem;
  }
  .price-labels {
    margin-top: 0.1rem;
    font-size: 0.6rem;
  }
}

</style>
