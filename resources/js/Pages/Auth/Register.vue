<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100 my-container">
    <div class="col-md-4 col-lg-4 col-xl-4">
      <div class="custom-card text-center">
        <!-- Logo -->
        <div class="mb-4">
          <div class="logo-icon-wrapper">
            <font-awesome-icon icon="bicycle" class="text-white fa-2x" />
          </div>
          <div class="app-name fw-bold">{{ t('app_name') }}</div>
        </div>

        <h4 class="register-title mb-3">{{ t('register_form.title') }}</h4>
        <p class="text-muted mb-3">{{ t('register_form.subtitle') }}</p>

        <form @submit.prevent="handleRegister">
          <!-- Role Selector -->
          <div class="role-selector mb-3">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="role" id="roleTraveler" value="renter"
                v-model="form.role">
              <label class="form-check-label" for="roleTraveler">
                <div class="role-icon">
                  <font-awesome-icon :icon="['fas', 'map-location-dot']" class="fa-lg" />
                </div>
                {{ t('register_form.traveler') }}
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="role" id="roleOwner" value="owner" v-model="form.role">
              <label class="form-check-label" for="roleOwner">
                <div class="role-icon">
                  <font-awesome-icon :icon="['fas', 'store']" class="fa-lg" />
                </div>
                {{ t('register_form.owner') }}
              </label>
            </div>
          </div>

          <!-- Form Fields -->
          <div class="mb-2 text-start">
            <input type="text" class="form-control form-control-sm" v-model="form.name"
              :placeholder="t('register_form.name_placeholder')" required>
          </div>

          <div class="mb-2 text-start">
            <input type="email" class="form-control form-control-sm" v-model="form.email"
              :placeholder="t('register_form.email_placeholder')" required>
          </div>

          <div class="mb-2 text-start position-relative">
            <input :type="showPassword ? 'text' : 'password'" class="form-control form-control-sm"
              v-model="form.password" :placeholder="t('register_form.password_placeholder')" required>
            <span class="password-toggle" @click="showPassword = !showPassword">
              <font-awesome-icon :icon="['fas', showPassword ? 'eye-slash' : 'eye']" />
            </span>
          </div>

          <div class="mb-3 text-start position-relative">
            <input :type="showPassword ? 'text' : 'password'" class="form-control form-control-sm"
              v-model="form.password_confirmation" :placeholder="t('register_form.confirm_password_placeholder')"
              required>
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-3" :disabled="loading">
            <span v-if="loading">
              <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ t('register_form.loading_button') }}
            </span>
            <span v-else>
              {{ t('register_form.submit_button') }}
            </span>
          </button>

        </form>

        <!-- Social Login -->
        <div class="social-login-divider my-3">{{ t('or_continue_with') }}</div>
        <div class="d-flex justify-content-center gap-2 mb-3">
          <button class="btn btn-outline-primary btn-sm">
            <font-awesome-icon :icon="['fab', 'google']" />
          </button>
          <button class="btn btn-outline-primary btn-sm">
            <font-awesome-icon :icon="['fab', 'facebook-f']" />
          </button>
        </div>

        <div class="text-center">
          <p class="text-muted mb-0">{{ t('register_form.already_account') }}
            <router-link to="/login" class="fw-bold text-primary">
              {{ t('register_form.login') }}
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';

const store = useStore();
const router = useRouter();
const { t } = useI18n();

const form = ref({
  role: 'renter',
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const loading = ref(false);
const showPassword = ref(false);

const handleRegister = async () => {
  console.log('form', form.value);
  loading.value = true;
  try {
    await store.dispatch('auth/register', form.value);
     const user = store.state.auth.user;
    if (user.role === 'owner') {
      router.push({ name: 'Dashboard' });
    } else {
      router.push({ name: 'Home' });
    }
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};
onMounted(() => {
  let roleParam = new URLSearchParams(window.location.search).get('role');
  if (roleParam === 'owner' || roleParam === 'renter') {
    form.value.role = roleParam;
  }
});
</script>

<style scoped>
.custom-card {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
  padding: 1.8rem 1.5rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid var(--border-color);
}

.logo-icon-wrapper {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background-color: var(--primary-color);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.25rem;
}

.role-selector {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.role-selector .form-check-input {
  display: none;
}

.role-selector .form-check-label {
  display: block;
  padding: 0.6rem;
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  text-align: center;
  cursor: pointer;
  transition: border-color 0.2s, background-color 0.2s;
  font-size: 0.875rem;
}

.role-selector .form-check-label:hover {
  border-color: var(--primary-color);
}

.role-selector .form-check-input:checked+.form-check-label {
  border-color: var(--primary-color);
  background-color: #eaf8f3;
  color: var(--primary-color);
  font-weight: 600;
}

.role-icon {
  font-size: 1.1rem;
  margin-bottom: 0.25rem;
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
  font-size: 1rem;
  color: var(--light-text-color);
}

.password-toggle:hover {
  color: var(--primary-color);
}

.form-control-sm {
  padding: 0.4rem 0.6rem;
  font-size: 0.875rem;
}

.btn {
  font-size: 0.875rem;
  padding: 0.5rem 0;
}

.social-login-divider {
  font-size: 0.8rem;
  color: var(--secondary-text-color);
}

.btn-sm {
  padding: 0.35rem 0.6rem;
  font-size: 0.85rem;
}
</style>
