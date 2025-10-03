<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100 my-container">
    <div class="col-md-4 col-lg-4 col-xl-4">
      <div class="custom-card text-center">
        <!-- Logo -->
        <div class="mb-4">
          <div class="logo-icon-wrapper d-inline-flex align-items-center justify-content-center mb-2">
            <font-awesome-icon icon="bicycle" class="text-white fa-2x" />
          </div>
          <div class="app-name fw-bold">{{ t('app_name') }}</div>
        </div>

        <h2 class="login-title mb-4">{{ t('login_title') }}</h2>

        <!-- Error Message -->
        <div v-if="errors" class="alert alert-danger text-start">
          {{ errors }}
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin">
          <div class="mb-3 text-start">
            <input
              type="email"
              class="form-control"
              v-model="form.email"
              :placeholder="t('email_placeholder')"
              required
            />
          </div>

          <div class="mb-3 text-start position-relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              class="form-control"
              v-model="form.password"
              :placeholder="t('password_placeholder')"
              required
            />
            <span class="password-toggle" @click="showPassword = !showPassword">
              <font-awesome-icon :icon="['fas', showPassword ? 'eye-slash' : 'eye']" />
            </span>
          </div>

          <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe" v-model="form.remember" />
              <label class="form-check-label" for="rememberMe">
                {{ t('remember_me') }}
              </label>
            </div>
            <a href="#" class="text-primary">{{ t('forgot_password') }}</a>
          </div>

          <button type="submit" class="btn btn-primary w-100" :disabled="loading">
            <span v-if="loading">
              <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ t('login_button') }}...
            </span>
            <span v-else>{{ t('login_button') }}</span>
          </button>
        </form>

        <!-- Social Login -->
        <div class="social-login-divider my-4">{{ t('or_continue_with') }}</div>
        <div class="d-flex justify-content-center gap-3">
          <button class="btn btn-outline-primary">
            <font-awesome-icon :icon="['fab', 'google']" />
          </button>
          <button class="btn btn-outline-primary">
            <font-awesome-icon :icon="['fab', 'facebook-f']" />
          </button>
        </div>

        <div class="mt-4">
          {{ t('no_account') }}
          <router-link to="/register" class="fw-bold text-primary">{{ t('create_account') }}</router-link>
        </div>

        <theme-switcher class="mt-3"></theme-switcher>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import ThemeSwitcher from '../../components/ThemeSwitcher.vue';

const store = useStore();
const router = useRouter();
const { t } = useI18n();

const form = ref({ email: '', password: '', remember: false });
const showPassword = ref(false);
const loading = ref(false);
const errors = ref(null);

const handleLogin = async () => {
  loading.value = true;
  errors.value = null;

  try {
    await store.dispatch('auth/login', form.value);

    // Redirect based on role
    const user = store.state.auth.user;
    router.push({ name: 'Home' });

  } catch (error) {
    if (error.response && (error.response.status === 401 || error.response.status === 422)) {
      errors.value = error.response.data.message || t('login_form.invalid_credentials');
    } else {
      errors.value = t('login_form.unexpected_error');
      console.error(error);
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.custom-card {
  background-color: var(--section-bg-color);
  color: var(--text-color);
  border-radius: var(--border-radius-lg);
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, color 0.3s;
}

.position-relative {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: var(--light-text-color);
  font-size: 1.1rem;
}
.password-toggle:hover {
  color: var(--primary-color);
}

.logo-icon-wrapper {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background-color: var(--primary-color);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.5rem;
}
</style>
