<template>
  <div class="position-relative d-inline-block">
    <!-- Toggle button -->
    <button
      @click="toggleDropdown"
      class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle shadow-sm theme-toggle-btn border-theme-btn"
    >
      <!-- Not logged in -->
      <i v-if="!isAuthenticated" class="bi bi-list"></i>

      <!-- Logged in with avatar -->
      <img
        v-else-if="user?.avatar"
        :src="`${user.avatar}`"
        alt="avatar"
        loading="lazy"
        class="rounded-circle avatar-img"
      />

      <!-- Logged in without avatar -->
      <span v-else class="avatar-text">
        {{ firstLetter }}
      </span>
    </button>

    <!-- Dropdown menu -->
    <ul
      v-show="isOpen"
      class="dropdown-menu show position-absolute mt-2 shadow responsive-align"
      style="min-width: 200px"
    >
      <!-- Profile -->
      <li v-if="isAuthenticated">
        <a class="dropdown-item" href="#">{{ t("profile") }}</a>
      </li>

      <!-- Theme Switcher -->
      <li>
        <a
          class="dropdown-item d-flex justify-content-between align-items-center"
          href="#"
          @click.prevent="toggleTheme"
        >
          {{ currentTheme === "light" ? t("theme.dark_mode") : t("theme.light_mode") }}
          <i
            class="bi"
            :class="
              currentTheme === 'light'
                ? 'bi-moon-fill text-dark'
                : 'bi-sun-fill text-warning'
            "
          ></i>
        </a>
      </li>
      <li v-if="isAuthenticated && user && user.role === 'owner'">
        <router-link class="dropdown-item" :to="{ name: 'owner.dashboard' }">
          {{ t("nav.dashboard") }}
        </router-link>
      </li>
      <li v-if="isAuthenticated && user && user.role === 'renter'">
        <router-link class="dropdown-item" :to="{ name: 'renter.dashboard' }">
          {{ t("nav.dashboard") }}
        </router-link>
      </li>

      <!-- Admin Dashboard -->
      <li v-if="isAuthenticated && user && user.role === 'admin'">
        <router-link class="dropdown-item" :to="{ name: 'admin.dashboard' }">
          {{ t("nav.dashboard") }}
        </router-link>
      </li>

      <!-- user  -->
      <li v-if="isAuthenticated && user && user.role === 'user'">
        <span class="dropdown-item" @click="visible = true">Dashboard</span>
      </li>

      <li>
        <hr class="dropdown-divider" />
      </li>

      <!-- Logout / Login -->
      <li>
        <a class="dropdown-item" v-if="isAuthenticated" @click.prevent="logout" href="#">
          {{ t("nav.logout") }}
        </a>
        <a
          class="dropdown-item"
          v-else
          @click.prevent="router.push({ name: 'Login' })"
          href="#"
        >
          {{ t("nav.login") }}
        </a>
      </li>
    </ul>
  </div>

  <Dialog
    v-model:visible="visible"
    modal
    header="You use this website as a"
    :style="{ width: '25rem' }"
  >
    <div class="role-selector mb-3" @click="selectRole">
      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          name="role"
          id="roleTraveler"
          value="renter"
          v-model="role"
        />
        <label class="form-check-label" for="roleTraveler">
          <div class="role-icon">
            <font-awesome-icon :icon="['fas', 'map-location-dot']" class="fa-lg" />
          </div>
          {{ t("register_form.traveler") }}
        </label>
      </div>
      <div class="form-check">
        <input
          class="form-check-input"
          type="radio"
          name="role"
          id="roleOwner"
          value="owner"
          v-model="role"
        />
        <label class="form-check-label" for="roleOwner">
          <div class="role-icon">
            <font-awesome-icon :icon="['fas', 'store']" class="fa-lg" />
          </div>
          {{ t("register_form.owner") }}
        </label>
      </div>
    </div>
  </Dialog>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import Dialog from "primevue/dialog";
const store = useStore();
const router = useRouter();
const { t } = useI18n();
const isOpen = ref(false);
const visible = ref(false);
// Authenticated state & user
const isAuthenticated = computed(() => store.getters["auth/isAuthenticated"]);
const user = computed(() => store.getters["auth/user"]);

// First letter for Google-style avatar
const firstLetter = computed(() =>
  user.value?.name ? user.value.name.charAt(0).toUpperCase() : ""
);

const role = ref("");
// Toggle dropdown
const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest(".position-relative")) {
    isOpen.value = false;
  }
};
document.addEventListener("click", handleClickOutside);

// Logout function
const logout = async () => {
  try {
    await store.dispatch("auth/logout");
    router.push({ name: "Home" });
  } catch (err) {
    console.error("Logout failed:", err);
  }
};
const selectRole = async () => {
  setTimeout(async () => {
    visible.value = false;
    const response = await store.dispatch("auth/setUserRole", {
      role: role.value,
      userId: user.value.id,
    });
    const res = await getData();
    if (user.value.role === "owner") {
      router.push({ name: "owner.dashboard" });
    } else {
      router.push({ name: "Home" });
    }
  }, 200);
};

const getData = async () => {
  const response = await store.dispatch("auth/fetchUser");
  return response;
};
// Theme toggle
const currentTheme = computed(() => store.getters["theme/currentTheme"]);
const toggleTheme = () => store.dispatch("theme/toggleTheme");

// Fetch user on mount
onMounted(async () => {
  if (isAuthenticated.value) getData();
});
</script>

<style scoped>
.theme-toggle-btn {
  width: 2.5rem;
  height: 2.5rem;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease-in-out;
}

.avatar-img {
  width: 2rem;
  height: 2rem;
  object-fit: cover;
  border-radius: 50%;
}

.avatar-text {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  font-weight: 600;
  font-size: 1rem;
  color: white;
  background-color: var(--primary-color);
  border-radius: 50%;
  text-transform: uppercase;
}

.theme-toggle-btn i {
  font-size: 1rem;
}

.theme-toggle-btn:hover {
  transform: rotate(10deg) scale(1.1);
}

/* Add border */
.border-theme-btn {
  border: 1px solid var(--border-color);
  background-color: var(--section-bg-color);
  color: var(--text-color);
}

/* Dropdown */
.dropdown-menu {
  background-color: var(--section-bg-color);
  color: var(--text-color);
  border-radius: 0.5rem;
  padding: 0.5rem 0;
  transition: all 0.3s;
  z-index: 999;
}

.dropdown-menu a {
  color: var(--text-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.dropdown-menu a:hover {
  background-color: var(--primary-color);
  color: #fff;
}

.dropdown-divider {
  border-top: 1px solid var(--border-color);
}
.dropdown-item {
  cursor: pointer;
}
.p-dialog {
  z-index: 9999 !important;
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

.role-selector .form-check-input:checked + .form-check-label {
  border-color: var(--primary-color);
  background-color: #eaf8f3;
  color: var(--primary-color);
  font-weight: 600;
  border: 2px solid var(--primary-color);
}

.role-icon {
  font-size: 1.1rem;
  margin-bottom: 0.25rem;
}
.responsive-align {
  right: auto;
  left: 0;
}

/* Large screens (left aligned) */
@media (min-width: 992px) {
  .responsive-align {
    left: auto;
    right: 0;
  }
}
</style>
