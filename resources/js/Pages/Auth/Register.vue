<template>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <!-- UPDATED: Use `t` instead of `$t` -->
          <h2 class="card-title">{{ t('register_form.title') }}</h2>
        </div>
        <div class="card-body">
          <div v-if="errors" class="alert alert-danger">
            <ul class="mb-0">
              <li v-for="(error, key) in errors" :key="key">{{ error[0] }}</li>
            </ul>
          </div>
          <form @submit.prevent="handleRegister">
            <div class="mb-3">
              <label for="name" class="form-label">{{ t('register_form.name') }}</label>
              <input type="text" v-model="form.name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">{{ t('register_form.email') }}</label>
              <input type="email" v-model="form.email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">{{ t('register_form.password') }}</label>
              <input type="password" v-model="form.password" class="form-control" id="password" required>
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">{{ t('register_form.confirm_password') }}</label>
              <input type="password" v-model="form.password_confirmation" class="form-control" id="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? t('register_form.loading_button') : t('register_form.submit_button') }}
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <LanguageSwitcher />
            </div>
            <div class="col-md-6">
                <ThemeSwitcher />
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
// --- STEP 1: Import the useI18n hook ---
import { useI18n } from 'vue-i18n';
import LanguageSwitcher from '../../components/LanguageSwitcher.vue';
import ThemeSwitcher from '../../components/ThemeSwitcher.vue';

const store = useStore();
const router = useRouter();
// --- STEP 2: Get the translation function `t` ---
const { t } = useI18n();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const loading = ref(false);
const errors = ref(null);

const handleRegister = async () => {
  loading.value = true;
  errors.value = null;
  try {
    await store.dispatch('auth/register', form.value);
    router.push({ name: 'Dashboard' }); // Or wherever you want to redirect after register
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      console.error("An unexpected error occurred:", error);
    }
  } finally {
    loading.value = false;
  }
};
</script>

