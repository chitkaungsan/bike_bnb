<template>
  <div class="position-relative d-inline-block">
    <!-- Toggle button -->
    <button @click="toggleDropdown"
      class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle shadow-sm theme-toggle-btn border-theme-btn">
      <!-- Not logged in -->
      <i v-if="!isAuthenticated" class="bi bi-list"></i>

      <!-- Logged in with avatar -->
      <img v-else-if="user?.avatar" :src="`${user.avatar}`" alt="avatar" loading="lazy"
        class="rounded-circle avatar-img" />

      <!-- Logged in without avatar -->
      <span v-else class="avatar-text">
        {{ firstLetter }}
      </span>
    </button>

    <!-- Dropdown menu -->
    <ul v-show="isOpen" class="dropdown-menu show position-absolute end-0 mt-2 shadow" style="min-width: 200px;">
      <!-- Profile -->
      <li v-if="isAuthenticated">
        <a class="dropdown-item" href="#">{{ t('profile') }}</a>
      </li>

      <!-- Theme Switcher -->
      <li>
        <a class="dropdown-item d-flex justify-content-between align-items-center" href="#"
          @click.prevent="toggleTheme">
          {{ currentTheme === 'light' ? t('theme.dark_mode') : t('theme.light_mode') }}
          <i class="bi" :class="currentTheme === 'light' ? 'bi-moon-fill text-dark' : 'bi-sun-fill text-warning'"></i>
        </a>
      </li>
      <li v-if="isAuthenticated && user && user.role === 'owner'">
        <router-link class="dropdown-item" :to="{ name: 'owner.dashboard' }">
          {{ t('nav.dashboard') }}
        </router-link>
      </li>
      <li v-if="isAuthenticated && user && user.role === 'renter'">
        <router-link class="dropdown-item" :to="{ name: 'renter.dashboard' }">
          {{ t('nav.dashboard') }}
        </router-link>
      </li>

      <li v-if="isAuthenticated && user && user.role === 'admin'">
        <router-link class="dropdown-item" :to="{ name: 'admin.dashboard' }">
          {{ t('nav.dashboard') }}
        </router-link>
      </li>
      <li>
        <hr class="dropdown-divider">
      </li>

      <!-- Logout / Login -->
      <li>
        <a class="dropdown-item" v-if="isAuthenticated" @click.prevent="logout" href="#">
          {{ t('nav.logout') }}
        </a>
        <a class="dropdown-item" v-else @click.prevent="router.push({ name: 'Login' })" href="#">
          {{ t('nav.login') }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'

const store = useStore()
const router = useRouter()
const { t } = useI18n()
const isOpen = ref(false)

// Authenticated state & user
const isAuthenticated = computed(() => store.getters['auth/isAuthenticated'])
const user = computed(() => store.getters['auth/user'])

// First letter for Google-style avatar
const firstLetter = computed(() => user.value?.name ? user.value.name.charAt(0).toUpperCase() : '')

// Toggle dropdown
const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.position-relative')) {
    isOpen.value = false
  }
}
document.addEventListener('click', handleClickOutside)

// Logout function
const logout = async () => {
  try {
    await store.dispatch('auth/logout')
    router.push({ name: 'Home' })
  } catch (err) {
    console.error('Logout failed:', err)
  }
}

// Theme toggle
const currentTheme = computed(() => store.getters['theme/currentTheme'])
const toggleTheme = () => store.dispatch('theme/toggleTheme')

// Fetch user on mount
onMounted(async () => {
  if (isAuthenticated.value) await store.dispatch('auth/fetchUser')
})
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
</style>
