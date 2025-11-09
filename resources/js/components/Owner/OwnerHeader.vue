<template>
  <header class="main-header" v-if="user && user.role=='owner'">
    <div class="header-content">
      <button class="sidebar-toggle-btn" @click="$emit('toggle-sidebar')">
        <font-awesome-icon :icon="faBars" />
      </button>
      <h5 class="header-title">{{ title }}</h5>
    </div>
    <!-- You can add user profile/actions here -->
  </header>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";

const store = useStore();
const user = computed(() => store.state.auth.user);
defineProps({
  title: String,
});

defineEmits(["toggle-sidebar"]);
</script>

<style scoped>
.main-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 1rem;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.sidebar-toggle-btn {
  background: none;
  border: none;
  color: var(--text-color);
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: var(--border-radius-md);
}
.sidebar-toggle-btn:hover {
  background-color: var(--background-color);
}

.header-title {
  font-weight: 700;
  margin: 0;
}
</style>
