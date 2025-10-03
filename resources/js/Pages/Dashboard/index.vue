<template>
  <div class="container py-4">
    <div v-if="user" class="card">
      <div class="card-header">
        <h2 class="card-title">{{ t('dashboard.title') }}</h2>
      </div>
      <div class="card-body">
        <h4 class="card-text">{{ t('dashboard.welcome', { name: user.name }) }}</h4>
        <p>{{ t('dashboard.info') }}</p>
        <p><strong>{{ t('dashboard.email_label') }}</strong> {{ user.email }}</p>
      </div>
    </div>
    <div v-else class="text-center">
      <p>{{ t('dashboard.loading') }}</p>
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useI18n } from 'vue-i18n';

const store = useStore();
const { t } = useI18n();

// Get the user from the Vuex store
const user = computed(() => store.getters['auth/user']);

// When the component is mounted, dispatch an action to fetch the user data
// This ensures the data is fresh, especially on a page refresh.
onMounted(() => {
  // Only fetch if we don't already have the user details
  if (!user.value) {
    store.dispatch('auth/fetchUser');
  }
});
</script>
