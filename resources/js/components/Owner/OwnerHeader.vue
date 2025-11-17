<template>
  <header class="main-header" v-if="user && user.role === 'owner'">
    <div class="d-flex w-100 align-items-center justify-content-between">
      <div class="header-content">
        <button class="sidebar-toggle-btn" @click="$emit('toggle-sidebar')">
          <font-awesome-icon :icon="faBars" />
        </button>
        <h5 class="header-title">{{ title }}</h5>
      </div>

      <!-- 🔔 Notification Component -->
      <div class="ms-auto d-flex align-items-center gap-3">
        <NotificationDropdown ref="notiRef" @refreshed="handleRefreshed" @click="markAllRead"/>
      </div>
    </div>
  </header>

  <div v-else>
    <Forbidden />
  </div>
</template>

<script setup>
import { computed,ref, watch, onBeforeUnmount } from "vue";
import { useStore } from "vuex";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import Forbidden from "@/components/Forbidden.vue";
import NotificationDropdown from "./NotificationDropdown.vue";

const store = useStore();
const user = computed(() => store.state.auth.user);
const notiRef = ref(null);

defineProps({
  title: String,
});
defineEmits(["toggle-sidebar"]);

let channel = null;
let channelName = "";

// When NotificationDropdown refreshes, we can log or add behavior
const handleRefreshed = (notifications) => {
  console.log("🔔 Notifications refreshed in header:", notifications.length);
};
const markAllRead = () => {
  if (notiRef.value) {
    notiRef.value.markAllRead();
  }
  console.log("Mark all notifications as read from header");
}
// Watch user and subscribe to Echo
watch(
  user,
  (newUser) => {
    if (newUser && newUser.role === "owner") {
      if (channelName) {
        window.Echo.leave(`private-${channelName}`);
      }

      channelName = `owner-booking-status.${newUser.id}`;
      channel = window.Echo.private(channelName);

      channel.listen(".BookingStatusChange", async (data) => {
        console.log("✅ Booking event:", data);
        // Broadcast global event to NotificationDropdown
        const event = new CustomEvent("refresh-notifications");
        window.dispatchEvent(event);
      });
    }
  },
  { immediate: true }
);

onBeforeUnmount(() => {
  if (channelName) {
    window.Echo.leave(`private-${channelName}`);
  }
});
</script>

<style scoped>
.main-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
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
</style>
