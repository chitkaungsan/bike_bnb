<template>
  <div class="px-3 py-2 language-switcher">
    <select class="form-select form-select-sm" v-model="locale" aria-label="Language switcher">
      <option value="en">English</option>
      <option value="th">ไทย</option>
    </select>
  </div>
</template>

<script setup>
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';

// Reactive locale
const { locale } = useI18n();

// Save to localStorage when changed
watch(locale, (newLocale) => {
  localStorage.setItem('locale', newLocale);
});
</script>

<style scoped>
.language-switcher {
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.language-switcher label {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--light-text-color);
}

.language-switcher select {
  width: 100%;
  font-size: 0.875rem;
  padding: 0.25rem 1.5rem 0.25rem 0.5rem; /* add right padding for arrow */
  border-radius: 0.25rem;
  border: 1px solid var(--border-color);
  background-color: var(--section-bg-color);
  color: var(--text-color);
  appearance: none; /* remove default arrow */
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 4 5' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%236c757d' d='M2 0L0 2h4L2 0zm0 5L0 3h4l-2 2z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.5rem center;
  background-size: 0.65rem auto;
  transition: all 0.2s;
}

.language-switcher select:focus {
  border-color: var(--primary-color);
  outline: none;
  box-shadow: 0 0 0 0.15rem rgba(99, 193, 162, 0.25);
}

/* Dark mode support */
body.dark .language-switcher select {
  background-color: var(--search-bg, #2b2b2b);
  color: var(--text-color);
  border-color: var(--border-color);
}
</style>
