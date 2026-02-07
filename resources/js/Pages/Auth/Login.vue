<template>
  <div
    class="container d-flex justify-content-center align-items-center min-vh-100 py-5"
  >
    <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-5">
      <div class="custom-card text-center">
        <div class="mb-4">
          <div
            class="logo-icon-wrapper d-inline-flex align-items-center justify-content-center mb-2"
          >
            <font-awesome-icon icon="bicycle" class="logo-icon text-white" />
          </div>
          <div class="app-name fw-bold">{{ t("app_name") }}</div>
        </div>

        <h2 class="login-title mb-4">{{ t("login_title") }}</h2>

        <div v-if="errors" class="alert alert-danger text-start">
          {{ errors }}
        </div>

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

          <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                id="rememberMe"
                v-model="form.remember"
              />
              <label class="form-check-label" for="rememberMe">
                {{ t("remember_me") }}
              </label>
            </div>
            <a href="#" class="text-primary mt-0 mt-sm-0">{{ t("forgot_password") }}</a>
          </div>

          <button type="submit" class="btn btn-primary w-100" :disabled="loading">
            <span v-if="loading">
              <span
                class="spinner-border spinner-border-sm me-2"
                role="status"
                aria-hidden="true"
              ></span>
              {{ t("login_button") }}...
            </span>
            <span v-else>{{ t("login_button") }}</span>
          </button>
        </form>

        <div class="social-login-divider my-4">{{ t("or_continue_with") }}</div>
        <div class="d-flex justify-content-center gap-3">
          <button class="btn btn-outline-primary social-btn" @click="handleGoogleLogin">
            <font-awesome-icon :icon="['fab', 'google']" />
          </button>
          <button class="btn btn-outline-primary social-btn">
            <font-awesome-icon :icon="['fab', 'facebook-f']" />
          </button>
        </div>

        <div class="mt-4 text-muted">
          {{ t("no_account") }}
          <router-link to="/register" class="fw-bold text-primary">{{
            t("create_account")
          }}</router-link>
        </div>

        <theme-switcher class="mt-3"></theme-switcher>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import ThemeSwitcher from "../../components/ThemeSwitcher.vue";
import { checkRedirect } from "../../service/checkRedirect";

const store = useStore();
const router = useRouter();
const { t } = useI18n();

const form = ref({ email: "", password: "", remember: false });
const showPassword = ref(false);
const loading = ref(false);
const errors = ref(null);

const handleGoogleLogin = async () => {
  const redirect = checkRedirect() || window.location.pathname;
 const path =  localStorage.setItem('oauth_redirect', JSON.stringify(redirect));
  if(path === undefined) {
    window.location.href = `${import.meta.env.VITE_API_URL}/auth/google/redirect`;
  }
};
const handleLogin = async () => {
  loading.value = true;
  errors.value = null;

  try {
    await store.dispatch("auth/login", form.value);

    // Redirect based on role
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
    if (
      error.response &&
      (error.response.status === 401 || error.response.status === 422)
    ) {
      errors.value = error.response.data.message || t("login_form.invalid_credentials");
    } else {
      errors.value = t("login_form.unexpected_error");
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
  /* Responsive Padding: Less on mobile, more on desktop */
  padding: 1.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, color 0.3s;
}

/* Tablet and up: Increase padding */
@media (min-width: 768px) {
  .custom-card {
    padding: 2.5rem;
  }
}

.logo-icon-wrapper {
  /* Smaller logo on mobile */
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

/* Larger logo on tablet/desktop */
@media (min-width: 768px) {
  .logo-icon-wrapper {
    width: 80px;
    height: 80px;
  }
}

/* Adjust Font Awesome size manually if needed, or rely on wrapper flex */
.logo-icon {
  font-size: 1.5rem;
}
@media (min-width: 768px) {
  .logo-icon {
    font-size: 2rem; /* fa-2x equivalent */
  }
}

.app-name {
  font-size: 1.25rem;
}

.login-title {
  font-size: 1.5rem;
  font-weight: 700;
}

/* Inputs */
.form-control {
  padding: 0.75rem 1rem; /* Comfortable touch target */
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
  color: var(--light-text-color);
  font-size: 1.1rem;
  z-index: 10; /* Ensure it's above input */
}
.password-toggle:hover {
  color: var(--primary-color);
}

/* Social Buttons */
.social-btn {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s;
}

.social-btn:hover {
  transform: translateY(-3px);
}
</style>
