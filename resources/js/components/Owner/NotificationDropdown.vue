<template>
  <div class="notification-wrapper" ref="wrapper">
    <!-- Bell icon with badge -->
    <div class="bell-container" @click="toggleDropdown">
      <font-awesome-icon :icon="faBell" class="bell-icon" />
      <span v-if="unreadCount > 0" class="badge">{{ unreadCount }}</span>
    </div>

    <!-- Dropdown list -->
    <div v-if="isOpen" class="dropdown-menu show">
      <div class="dropdown-header d-flex justify-content-between align-items-center">
        <strong>Notifications</strong>
        <small @click="markAllRead" class="mark-read">Mark all as read</small>
      </div>

      <ul class="list-unstyled m-0 p-0">
        <li v-for="(noti, index) in notifications" :key="index" class="dropdown-item"
          :class="{ 'bg-unread': !noti.is_read }">
          <RenterPending :data="noti" />
        </li>
      </ul>
      <div v-if="notifications.length === 0" class="empty-state">
        No notifications
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import axios from "../../service/axios";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBell } from "@fortawesome/free-solid-svg-icons";
import RenterPending from "./components/NotificationDropdown/RenterPending.vue";
const isOpen = ref(false);
const notifications = ref([]);
const unreadCount = ref(0);
const wrapper = ref(null);

const emit = defineEmits(["refreshed"]); // 👈 for parent (Header.vue) to know

// Toggle dropdown visibility
const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

// Fetch notifications
const fetchNotifications = async () => {
  try {
    const res = await axios.get("/owner/notifications");
    notifications.value = res.data;
    unreadCount.value = res.data.filter((n) => !n.is_read).length;
    emit("refreshed", notifications.value); //  tell parent we refreshed
  } catch (err) {
    console.error("Error fetching notifications:", err);
  }
};

// Mark all as read
const markAllRead = async () => {
  console.log('Marking all notifications as read...');
  setTimeout( async() => {
    const unread = notifications.value.filter((n) => !n.is_read);
    await Promise.all(
      unread.map((noti) =>
        axios.post(`/owner/notifications/${noti.id}/read`)
      )
    );
    await fetchNotifications();
  });
};

// Format time
const formatDate = (datetime) =>
  datetime ? new Date(datetime).toLocaleString() : "";

// Hide dropdown when clicking outside
const handleClickOutside = (event) => {
  if (wrapper.value && !wrapper.value.contains(event.target)) {
    isOpen.value = false;
  }
};

// 👇 Listen for global event from Header.vue
const handleGlobalRefresh = () => {
  console.log("🌴 Refreshing notifications from global event...");
  fetchNotifications();
};

// Mount + cleanup
onMounted(() => {
  fetchNotifications();
  document.addEventListener("click", handleClickOutside);
  window.addEventListener("refresh-notifications", handleGlobalRefresh); //  FIXED
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
  window.removeEventListener("refresh-notifications", handleGlobalRefresh);
});
defineExpose({
  markAllRead,
});
</script>

<style scoped>
.notification-wrapper {
  position: relative;
}

.bell-container {
  position: relative;
  cursor: pointer;
  font-size: 1.3rem;
  color: var(--text-color);
  transition: color 0.2s ease;
}

.bell-icon:hover {
  color: var(--primary-hover-color);
}

.badge {
  position: absolute;
  top: -6px;
  right: -8px;
  background-color: var(--primary-color);
  color: #fff;
  border-radius: 50%;
  font-size: 0.7rem;
  padding: 2px 6px;
}

.dropdown-menu {
  position: absolute;
  top: 2rem;
  right: 0;
  min-width: 300px;
  max-height: 400px;
  overflow-y: auto;
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  border-radius: 6px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  z-index: 999;
  padding: 0.5rem 0;
  transition: background-color 0.3s, border-color 0.3s;
}

.dropdown-header {
  padding: 0.5rem 1rem;
  border-bottom: 1px solid var(--border-color);
  color: var(--text-color);
}

.mark-read {
  color: var(--primary-color);
  cursor: pointer;
}

.dropdown-item {
  padding: 0.5rem 1rem;
  border-bottom: 1px solid var(--border-color);
  color: var(--text-color);
}

.dropdown-item:last-child {
  border-bottom: none;
}

.bg-unread {
  background-color: var(--background-color);
  border-left: 3px solid var(--primary-color);
}

.empty-state {
  text-align: center;
  color: var(--light-text-color);
  padding: 1rem 0;
}
</style>
