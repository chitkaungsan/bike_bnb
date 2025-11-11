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
import { computed, onMounted, onBeforeUnmount, ref, watch } from "vue";
import { useStore } from "vuex";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import Forbidden from "@/components/Forbidden.vue"; 

const store = useStore();
const user = computed(() => store.state.auth.user);
const message = ref("");

defineProps({
  title: String,
});
defineEmits(["toggle-sidebar"]);

let channel = null;
let channelName = "";

// 🔹 Watch for when user becomes available
watch(user, (newUser) => {
  if (newUser && newUser.role === "owner") {
    // Leave old channel if any
    if (channelName) {
      window.Echo.leave(`private-${channelName}`);
    }

    // Build channel name for this user
    channelName = `owner-booking-status.${newUser.id}`;
    console.log("🔗 Subscribing to channel:", channelName);

    // Subscribe to private channel
    channel = window.Echo.private(channelName);

    // Listen for events
    channel.listen(".BookingStatusChange", (data) => {
      console.log("✅ Received booking update:", data);
      message.value = `${data.message} (${new Date().toLocaleTimeString()})`;
    });
  }
}, { immediate: true }); // 👈 Run immediately on mount

// 🔹 Clean up when component is destroyed
onBeforeUnmount(() => {
  if (channelName) {
    console.log("👋 Leaving channel:", `private-${channelName}`);
    window.Echo.leave(`private-${channelName}`);
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
