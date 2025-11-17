<template>
  <div class="gallery-container">
    <TopBarComponents :initial-favorited="initialFavorited" />
    <!-- ✅ Top Bar (Back / Share / Favorite) -->
    <!-- <div class="d-flex align-items-center justify-content-between py-2 px-3 top-bar mb-2">
      <div class="d-flex align-items-center">
        <i class="bi bi-chevron-left fs-2 back-icon" @click="goBack"></i>
      </div>
      <div class="d-flex align-items-center gap-3">
        <div class="icon-btn" @click="shareItem">
          <i class="bi bi-share fs-4"></i>
        </div>
        <div class="icon-btn" :class="{ active: isFavorited }" @click="toggleFavorite">
          <i class="bi" :class="isFavorited ? 'bi-heart-fill' : 'bi-heart'"></i>
        </div>
      </div>
    </div> -->

    <!-- ✅ Image Section -->
    <div v-if="galleryImages.length > 0" class="mt-2">
      <!-- Desktop Grid -->
      <div class="photo-grid-desktop d-none d-lg-grid">
        <div class="grid-item" @click="openModal" title="Show all photos">
          <img :src="galleryImages[0]" alt="Main gallery view" />
        </div>
        <div
          v-for="(image, index) in galleryImages.slice(1, 5)"
          :key="index"
          class="grid-item"
          @click="openModal"
          title="Show all photos"
        >
          <img :src="image" :alt="`Gallery view ${index + 2}`" />
        </div>
        <button @click="openModal" class="show-all-btn btn btn-light fw-semibold">
          <i class="bi bi-grid-3x3-gap-fill me-2"></i>Show all photos
        </button>
      </div>

      <!-- Mobile Carousel -->
      <div class="photo-carousel-mobile d-lg-none">
        <Carousel
          :value="galleryImages"
          :numVisible="1"
          :numScroll="1"
          circular
          :autoplayInterval="4000"
          :showIndicators="false"
          :showNavigators="false"
        >
          <template #item="slotProps">
            <div class="carousel-item-content" @click="openModal">
              <img :src="slotProps.data" :alt="`Carousel image ${slotProps.index + 1}`" />
            </div>
          </template>
        </Carousel>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center p-5">
      <p class="text-muted">No images to display.</p>
    </div>

    <!-- ✅ Fullscreen Modal -->
    <Dialog
      v-model:visible="isModalVisible"
      header="All Photos"
      :style="{ width: '95vw', maxWidth: '1400px', height: '90vh' }"
      modal
      dismissableMask
    >
      <div class="container-fluid h-100">
        <div class="row g-2 h-100 overflow-auto">
          <div
            v-for="(img, i) in galleryImages"
            :key="i"
            class="col-12 col-md-6 col-lg-4"
          >
            <div class="modal-image-wrapper">
              <Image
                :src="img"
                class="img-fluid"
                alt="Photo"
                width="100%"
                height="100%"
                preview
              />
            </div>
          </div>
        </div>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import Dialog from "primevue/dialog";
import Carousel from "primevue/carousel";
import Image from "primevue/image";
import { useRouter } from "vue-router";
import TopBarComponents from "../TopBarComponents.vue";

const props = defineProps({
  images: { type: Array, default: () => [] },
  mainImage: { type: String, required: true },
});

const router = useRouter();
const isModalVisible = ref(false);
const isFavorited = ref(false);

const galleryImages = computed(() => {
  if (!props.mainImage) return props.images;
  const others = props.images.filter((img) => img !== props.mainImage);
  return [props.mainImage, ...others];
});

const goBack = () => {
  if (window.history.length > 1) router.back();
  else router.push({ name: "bike.list" });
};

const openModal = () => (isModalVisible.value = true);
const toggleFavorite = () => (isFavorited.value = !isFavorited.value);

const shareItem = () => {
  if (navigator.share) {
    navigator.share({
      title: "Bike Rental",
      text: "Check out this awesome bike!",
      url: window.location.href,
    });
  } else {
    alert("Sharing not supported on this device.");
  }
};
</script>

<style scoped>
/* -------------------------------
   Container
------------------------------- */
.gallery-container {
  max-width: 1200px;
  margin: 0 auto;
  background-color: transparent;
}

/* -------------------------------
   Top Bar
------------------------------- */
.top-bar {
  border-bottom: 1px solid var(--border-color);
  background: var(--navbar-bg);
  position: sticky;
  top: 0;
  z-index: 100;
  border-radius: var(--border-radius-md);
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

/* -------------------------------
   Action Icons
------------------------------- */
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

/* -------------------------------
   Desktop Grid
------------------------------- */
.photo-grid-desktop {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  gap: 0.5rem;
  position: relative;
  border-radius: var(--border-radius-lg);
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
.photo-grid-desktop .grid-item {
  overflow: hidden;
  cursor: pointer;
}
.photo-grid-desktop .grid-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease, filter 0.3s ease;
}
.photo-grid-desktop .grid-item:hover img {
  transform: scale(1.05);
  filter: brightness(0.85);
}
.photo-grid-desktop .grid-item:first-child {
  grid-column: 1 / 2;
  grid-row: 1 / 3;
}
.show-all-btn {
  position: absolute;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 10;
  border-radius: var(--border-radius-md);
  background: var(--section-bg-color);
  color: var(--text-color);
  border: 1px solid var(--border-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  transition: all 0.2s ease;
}
.show-all-btn:hover {
  background: var(--primary-color);
  color: #fff;
}

/* -------------------------------
   Mobile Carousel
------------------------------- */
.photo-carousel-mobile {
  width: 100vw;
  margin-left: 50%;
  transform: translateX(-50%);
  overflow: hidden;
  border-radius: var(--border-radius-md);
  background-color: var(--background-color);
}
.carousel-item-content {
  width: 100%;
  aspect-ratio: 16 / 10;
  overflow: hidden;
  background-color: #000;
}
.carousel-item-content img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* -------------------------------
   Modal
------------------------------- */
.modal-image-wrapper {
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  border-radius: var(--border-radius-md);
  border: 1px solid var(--border-color);
}
.modal-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
