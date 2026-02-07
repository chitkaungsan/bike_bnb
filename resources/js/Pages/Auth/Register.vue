<template>
  <div
    class="container d-flex justify-content-center align-items-center min-vh-100 py-2"
  >
    <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-5">
      <div class="custom-card text-center">
        <!-- Logo -->
        <div class="mb-4">
          <div class="logo-icon-wrapper">
            <font-awesome-icon icon="bicycle" class="logo-icon text-white" />
          </div>
          <div class="app-name fw-bold">{{ t("app_name") }}</div>
        </div>

        <h4 class="register-title mb-2">{{ t("register_form.title") }}</h4>
        <p class="text-muted mb-4 subtitle">{{ t("register_form.subtitle") }}</p>
        <form @submit.prevent="handleRegister">
          <!-- Role Selector -->
          <div class="role-selector mb-3">
            <div class="form-check p-0">
              <input
                class="form-check-input"
                type="radio"
                name="role"
                id="roleTraveler"
                value="renter"
                v-model="form.role"
              />
              <label class="form-check-label" for="roleTraveler">
                <div class="role-icon">
                  <font-awesome-icon :icon="['fas', 'map-location-dot']" />
                </div>
                <span>{{ t("register_form.traveler") }}</span>
              </label>
            </div>
            <div class="form-check p-0">
              <input
                class="form-check-input"
                type="radio"
                name="role"
                id="roleOwner"
                value="owner"
                v-model="form.owner"
              />
              <label class="form-check-label" for="roleOwner">
                <div class="role-icon">
                  <font-awesome-icon :icon="['fas', 'store']" />
                </div>
                <span>{{ t("register_form.owner") }}</span>
              </label>
            </div>
          </div>

          <!-- Form Fields -->
          <!-- Note: Removed 'form-control-sm' to improve mobile touch targets -->
          <div class="mb-3 text-start">
            <input
              type="text"
              class="form-control"
              v-model="form.name"
              :placeholder="t('register_form.name_placeholder')"
              required
            />
              <small class="text-muted" v-if="errors && errors.name">{{ errors.name }}</small>
          </div>

          <div class="mb-3 text-start">
            <input
              type="email"
              class="form-control"
              v-model="form.email"
              :placeholder="t('register_form.email_placeholder')"
              required
            />
                <small class="text-muted" v-if="errors && errors.email">{{ errors.email[0] }}</small>
          </div>

          <div class="mb-3 text-start position-relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              class="form-control"
              v-model="form.password"
              :placeholder="t('register_form.password_placeholder')"
              required
            />

            <span class="password-toggle" @click="showPassword = !showPassword">
              <font-awesome-icon :icon="['fas', showPassword ? 'eye-slash' : 'eye']" />
            </span>
                <small class="text-muted" v-if="errors && errors.password">{{ errors.password[0] }}</small>
          </div>

          <div class="mb-4 text-start position-relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              class="form-control"
              v-model="form.password_confirmation"
              :placeholder="t('register_form.confirm_password_placeholder')"
              required
            />
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-3 py-2" :disabled="loading">
            <span v-if="loading">
              <span
                class="spinner-border spinner-border-sm me-2"
                role="status"
                aria-hidden="true"
              ></span>
              {{ t("register_form.loading_button") }}
            </span>
            <span v-else>
              {{ t("register_form.submit_button") }}
            </span>
          </button>
        </form>

        <!-- Social Login -->
        <div class="social-login-divider my-3">{{ t("or_continue_with") }}</div>
        <div class="d-flex justify-content-center gap-3 mb-3">
          <button class="btn btn-outline-primary social-btn" @click="handleGoogleLogin">
            <font-awesome-icon :icon="['fab', 'google']" />
          </button>
          <button class="btn btn-outline-primary social-btn">
            <font-awesome-icon :icon="['fab', 'facebook-f']" />
          </button>
        </div>

        <div class="text-center mt-4">
          <p class="text-muted mb-0">
            {{ t("register_form.already_account") }}
            <router-link to="/login" class="fw-bold text-primary">
              {{ t("register_form.login") }}
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { checkRedirect } from "../../service/checkRedirect";
const store = useStore();
const router = useRouter();
const { t } = useI18n();

const form = ref({
  role: "renter",
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const errors = ref(null);

const loading = ref(false);
const showPassword = ref(false);

const handleRegister = async () => {
  loading.value = true;
  try {
    await store.dispatch("auth/register", form.value);
    const user = store.state.auth.user;
    const redirectPath = checkRedirect();
    if (redirectPath) {
      router.push(redirectPath);
      return;
    }
    if (user.role === "owner" && redirectPath === null) {
      router.push({ name: "owner.dashboard" });
    } else if(user.role === "renter" && redirectPath === null) {
      router.push({ name: "Home" });
    }
  } catch (error) {
    console.error('register error',error);
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors;
    } else {
      errors.value = { general: [t("register_form.generic_error")] };
    }
  } finally {
    loading.value = false;
  }
};
const handleGoogleLogin = async () => {
  const redirect = checkRedirect() || window.location.pathname;
  localStorage.setItem('oauth_redirect', JSON.stringify(redirect));
  window.location.href = `${import.meta.env.VITE_API_URL}/auth/google/redirect`;

};
onMounted(() => {
  let roleParam = new URLSearchParams(window.location.search).get("role");
  if (roleParam === "owner" || roleParam === "renter") {
    form.value.role = roleParam;
  }
});
</script>
<style scoped>
/* Base Card Style */
.custom-card {
  background-color: var(--section-bg-color);
  border-radius: var(--border-radius-lg);
  padding: 1.5rem; /* Mobile first padding */
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid var(--border-color);
  transition: padding 0.3s ease;
}

/* Tablet/Desktop Padding */
@media (min-width: 768px) {
  .custom-card {
    padding: 2.5rem;
  }
}

/* Logo Animation & Sizing */
.logo-icon-wrapper {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background-color: var(--primary-color);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.5rem;
  transition: all 0.3s ease;
}

@media (min-width: 768px) {
  .logo-icon-wrapper {
    width: 80px;
    height: 80px;
  }
}

.logo-icon {
  font-size: 1.5rem;
  color: white;
}
@media (min-width: 768px) {
  .logo-icon {
    font-size: 2rem;
  }
}

.app-name {
  font-size: 1.25rem;
}

.subtitle {
  font-size: 0.9rem;
}

/* Role Selector - Grid for perfect alignment */
.role-selector {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.role-selector .form-check-input {
  display: none; /* Hide the actual radio circle */
}

/* The clickable box */
.role-selector .form-check-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1rem 0.5rem;
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  height: 100%;
}

.role-selector .form-check-label:hover {
  border-color: var(--primary-color-light, #a5d6a7); /* Lighter green on hover */
  background-color: var(--bg-light, #f8f9fa);
}

/* Selected State */
.role-selector .form-check-input:checked + .form-check-label {
  border-color: var(--primary-color);
  background-color: rgba(var(--primary-color-rgb, 66, 185, 131), 0.1); /* Subtle background tint */
  color: var(--primary-color);
  font-weight: 600;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.role-icon {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

/* Inputs & Form - Removing SM for better Mobile UX */
.form-control {
  padding: 0.75rem 1rem;
  font-size: 1rem;
}
.form-control:focus {
  box-shadow: 0 0 0 0.25rem rgba(var(--primary-color-rgb), 0.25);
  border-color: var(--primary-color);
}

/* Password Toggle */
.position-relative {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 1.1rem;
  color: var(--light-text-color);
  z-index: 5;
  padding: 5px; /* Increase hit area */
}

.password-toggle:hover {
  color: var(--primary-color);
}

/* Social Buttons - Consistent with Login */
.social-btn {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s;
  font-size: 1.1rem;
}

.social-btn:hover {
  transform: translateY(-2px);
}

.social-login-divider {
  font-size: 0.85rem;
  color: var(--secondary-text-color);
  position: relative;
}

</style>
