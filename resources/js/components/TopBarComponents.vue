<template>
  <div class="d-flex align-items-center justify-content-between py-2 px-3 top-bar">
    <!-- Back Button -->
    <div class="d-flex align-items-center">
      <i class="bi bi-chevron-left fs-2 back-icon" @click="goBack"></i>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex align-items-center gap-3">
      <!-- Share -->
      <div class="icon-btn" @click="shareItem">
        <i class="bi bi-share fs-4"></i>
      </div>

      <!-- Favorite -->
      <div class="icon-btn" :class="{ active: isFavorited }" @click="toggleFavorite">
        <i class="bi" :class="isFavorited ? 'bi-heart-fill' : 'bi-heart'"></i>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
  initialFavorited: { type: Boolean, default: false },
});

const router = useRouter();
const isFavorited = ref(props.initialFavorited);

const goBack = () => {
  if (window.history.length > 1) router.back();
  else router.push({ name: "bike.list" });
};

const toggleFavorite = () => {
  isFavorited.value = !isFavorited.value;
  // emit event if needed
  // emit('update:favorited', isFavorited.value)
};

const shareItem = () => {
  if (navigator.share) {
    navigator.share({
      title: "Bike Rental",
      text: "Check out this item!",
      url: window.location.href,
    });
  } else {
    alert("Sharing not supported on this device.");
  }
};
</script>

<style scoped>
.top-bar {
  border-bottom: 1px solid var(--border-color);
  background: var(--navbar-bg);
  position: sticky;
  top: 0;
  z-index: 100;
  border-radius: var(--border-radius-md);
  padding: 0.5rem 1rem;
}

.back-icon {
  cursor: pointer;
  color: var(--text-color);
  transition: transform 0.2s ease, color 0.2s ease;
}
.back-icon:hover {
  color: var(--primary-color);
  transform: scale(1.1);
}

.icon-btn {
  cursor: pointer;
  color: var(--secondary-text-color);
  background: var(--background-color);
  border-radius: 50%;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  border: 1px solid var(--border-color);
}
.icon-btn:hover {
  background: var(--primary-hover-color);
  color: #fff;
  transform: scale(1.05);
}
.icon-btn.active {
  color: #fff;
  background: var(--primary-color);
  border-color: var(--primary-color);
}
</style>
