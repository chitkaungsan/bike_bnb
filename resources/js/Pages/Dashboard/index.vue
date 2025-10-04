<template>
    <OwnerDashboard v-if="isAuthenticated && user && user.role === 'owner'" :user="user" />
    <RenterDashboard v-else-if="isAuthenticated && user && user.role === 'renter'" :user="user" />
    <AdminDashboard v-else-if="isAuthenticated && user && user.role === 'admin'" :user="user" />
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useI18n } from 'vue-i18n';
import OwnerDashboard from '../../components/OwnerDashboard/Dashboard.vue';
import RenterDashboard from '../../components/RenterDashboard/Dashboard.vue';
import AdminDashboard from '../../components/AdminDashboard/Dashboard.vue';
const store = useStore();
const { t } = useI18n();
const user = computed(() => store.getters['auth/user']);
const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);

onMounted(() => {
  if (!user.value) {
    store.dispatch('auth/fetchUser');
  }
});
</script>
