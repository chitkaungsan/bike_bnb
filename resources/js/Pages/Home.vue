<template>
    <button @click="toggleTheme" class="btn btn-secondary me-3">
        Switch to {{ currentTheme === 'light' ? 'Dark' : 'Light' }} Mode
    </button>
    <router-link to="/dashboard" class="btn btn-primary me-3" v-if="isAuthenticated">Dashboard</router-link>
    <router-link to="/login" class="btn btn-primary" v-else>Login</router-link>
    
    <LogoutButton v-if="isAuthenticated"></LogoutButton>
</template>
<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import LogoutButton from '../components/LogoutButton.vue';
const store = useStore();
const router = useRouter();

// The path to the getter now includes the module name
const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']); // Assuming auth module

// --- UPDATED: Get current theme from the 'theme' module ---
const currentTheme = computed(() => store.getters['theme/currentTheme']);

// --- UPDATED: Dispatch the namespaced action ---
const toggleTheme = () => {
    store.dispatch('theme/toggleTheme');
};

const logout = async () => {
    await store.dispatch('auth/logout'); // Assuming auth module
    router.push({ name: 'Login' });
};
</script>
