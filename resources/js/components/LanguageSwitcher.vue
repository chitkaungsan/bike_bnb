<template>
  <div class="dropdown">
    <select class="form-select form-select-sm" v-model="locale" aria-label="Language switcher">
      <option value="en">English</option>
      <option value="th">ไทย</option>
    </select>
  </div>
</template>

<script setup>
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';

// Get the reactive 'locale' variable from vue-i18n
const { locale } = useI18n();

// This is the key part: when the user selects a new language,
// we save their choice in the browser's storage.
watch(locale, (newLocale) => {
  localStorage.setItem('locale', newLocale);
});
</script>

<style scoped>
/* Scoped styles ensure this CSS only applies to this component */
.form-select-sm {
  width: auto; /* Make dropdown only as wide as its content */
}
</style>

