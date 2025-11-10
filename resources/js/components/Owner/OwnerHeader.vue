<template>
  <header class="main-header" v-if="user && user.role === 'owner'">
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
import { computed, onMounted, onBeforeUnmount, ref } from "vue";
import { useStore } from "vuex";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import Forbidden from "@/components/Forbidden.vue"; 

const store = useStore();
const user = computed(() => store.state.auth.user);
const message = ref(""); // For debugging or later UI feedback

defineProps({
  title: String,
});
defineEmits(["toggle-sidebar"]);

let channel = null;

onMounted(() => {
  if (user.value && user.value.role === "owner") {
    // ✅ Connect to Pusher channel when owner is logged in
    channel = window.Echo.channel("test-channel");

    channel.listen(".test-event", (data) => {
      message.value = "TestEvent received at " + new Date().toLocaleTimeString();
      console.log("✅ Received test-event:", data);
    });
  }
});

onBeforeUnmount(() => {
  if (channel) {
    window.Echo.leave("test-channel");
  }
});
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
