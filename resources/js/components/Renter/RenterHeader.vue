<template>
  <header class="main-header" v-if="user && user.role === 'renter'">
    <div class="header-content">
      <button class="sidebar-toggle-btn" @click="$emit('toggle-sidebar')">
        <font-awesome-icon :icon="faBars" />
      </button>
      <h5 class="header-title">{{ title }}</h5>
    </div>
  </header>

  <div v-else>
    <Forbidden />
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import Forbidden from "@/components/Forbidden.vue";

defineProps({
  title: String,
});
defineEmits(["toggle-sidebar"]);

const store = useStore();
const user = computed(() => store.state.auth.user);
</script>

<style scoped>
.main-header {
  display: flex;
  align-items: center;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 1rem;
  position: relative;
  z-index: 999;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.sidebar-toggle-btn {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}
</style>
