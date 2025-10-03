<template>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">{{ t('login_form.title') }}</h2>
        </div>
        <div class="card-body">
          <div v-if="errors" class="alert alert-danger">
            {{ errors }}
          </div>
          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <label for="email" class="form-label">{{ t('login_form.email') }}</label>
              <input type="email" v-model="form.email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">{{ t('login_form.password') }}</label>
              <input type="password" v-model="form.password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? t('login_form.loading_button') : t('login_form.submit_button') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';

const store = useStore();
const router = useRouter();
const { t } = useI18n();

const form = ref({
  email: '',
  password: '',
});

const loading = ref(false);
const errors = ref(null);

const handleLogin = async () => {
  loading.value = true;
  errors.value = null;
  try {
    await store.dispatch('auth/login', form.value);
    router.push({ name: 'Dashboard' }); // Redirect on successful login
  } catch (error) {
    if (error.response && (error.response.status === 401 || error.response.status === 422)) {
      errors.value = error.response.data.message || 'Invalid credentials.';
    } else {
      errors.value = 'An unexpected error occurred. Please try again.';
      console.error("An unexpected error occurred:", error);
    }
  } finally {
    loading.value = false;
  }
};
</script>

