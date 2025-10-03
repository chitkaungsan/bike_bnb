<template>
  <button @click="logout" class="btn btn-danger">
    <span v-if="loading">
      <span class="spinner-border spinner-border-sm me-2" role="status"></span>
      Logging out...
    </span>
    <span v-else>
      Logout
    </span>
  </button>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();
const loading = ref(false);

const logout = async () => {
  loading.value = true;
  try {
    await store.dispatch('auth/logout');
    router.push({ name: 'Home' }); // Redirect to login after logout
  } catch (error) {
    console.error('Logout failed:', error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.spinner-border {
  width: 1rem;
  height: 1rem;
}
</style>
