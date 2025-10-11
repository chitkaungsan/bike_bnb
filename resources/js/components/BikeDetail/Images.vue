<template>
  <div class="gallery-container">
    <div v-if="galleryImages.length > 0">
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
        <button @click="openModal" class="show-all-btn btn btn-light">
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

    <div v-else class="text-center p-5 bg-light rounded">
      <p>No images to display.</p>
    </div>

    <!-- Fullscreen Modal -->
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
              <img :src="img" class="img-fluid" alt="Photo" />
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

const props = defineProps({
  images: { type: Array, default: () => [] },
  mainImage: { type: String, required: true },
});

const galleryImages = computed(() => {
  if (!props.mainImage) return props.images;
  const otherImages = props.images.filter((img) => img !== props.mainImage);
  return [props.mainImage, ...otherImages];
});

const isModalVisible = ref(false);
const openModal = () => (isModalVisible.value = true);
</script>

<style scoped>
/* --- General Container --- */
.gallery-container {
  max-width: 1200px;
  margin: 0 auto;
  background-color: transparent;
  padding: 0;
}

/* --- Desktop Grid --- */
.photo-grid-desktop {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  gap: 0.5rem;
  position: relative;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
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

/* Large first image */
.photo-grid-desktop .grid-item:first-child {
  grid-column: 1 / 2;
  grid-row: 1 / 3;
}

/* Show all button */
.show-all-btn {
  position: absolute;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 10;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  font-weight: 600;
}

/* --- Mobile Carousel --- */
.photo-carousel-mobile {
  width: 100vw; /* ✅ full width edge-to-edge */
  margin-left: 50%;
  transform: translateX(-50%);
  border-radius: 0; /* ✅ remove rounding for full bleed */
  overflow: hidden;
}

.carousel-item-content {
  width: 100%;
  aspect-ratio: 16 / 10;
  overflow: hidden;
  background-color: #000; /* gives depth behind images */
}

.carousel-item-content img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* --- Modal Styles --- */
.modal-image-wrapper {
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  border-radius: 0.5rem;
}
.modal-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* No images block transparent */
.text-center.p-5.bg-light.rounded {
  background-color: transparent !important;
}
</style>
