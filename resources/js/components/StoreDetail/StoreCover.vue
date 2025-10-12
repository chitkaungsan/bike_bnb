<template>
  <div class="container">
    <!-- Cover Photo Section -->
    <top-bar-components :initial-favorited="data.is_favorited" class="mt-2" />
    <div class="cover-photo-container rounded-top mt-2">
      <img :src="data.cover_photo" alt="Cover Photo" class="cover-photo-img" />
      <button class="btn btn-primary btn-sm cover-photo-button" @click="openModal">
        <i class="bi bi-shop me-1"></i> Show store photos
      </button>
    </div>

    <!-- Profile Header Section -->
    <div class="profile-header-container px-4 pt-3 pb-2">
      <div class="d-flex flex-wrap align-items-end">
        <!-- Profile Picture -->
        <div class="profile-picture-wrapper">
          <img :src="data.logo" alt="Profile Picture" class="profile-picture-img" />
        </div>

        <!-- Name and Bike Count -->
        <div class="ms-4 mb-2 flex-grow-1">
          <h3 class="user-name">{{ data.name }}</h3>
          <a href="#" class="friend-count text-decoration-none" v-if="data.bikes?.length">
            {{ data.bikes.length }} Bikes
          </a>
        </div>

        <!-- Action Buttons -->
        <div class="col-5">
          <div class="d-flex gap-2 mb-3 align-self-center w-100">
            <div class="search-container flex-grow-1">
              <input
                type="text"
                class="form-control search-input"
                placeholder="Search for bikes..."
                v-model="searchQuery"
              />
              <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-outline-primary search-btn" @click="onSearch">
                  <i class="bi bi-filter-left"></i>
                </button>
                <button class="btn btn-primary search-btn" @click="onSearch">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery Modal (PrimeVue Dialog style) -->
    <Dialog
      v-model:visible="isModalVisible"
      header="Store Photos"
      :style="{ width: '95vw', maxWidth: '1400px', height: '90vh' }"
      modal
      dismissableMask
    >
      <div class="container-fluid h-100">
        <div class="row g-2 h-100 overflow-auto">
          <div
            v-for="(img, index) in data.images"
            :key="index"
            class="col-12 col-md-6 col-lg-4"
          >
            <div class="modal-image-wrapper">
              <Image
                :src="img"
                alt="Store Photo"
                class="img-fluid"
                preview
                width="100%"
                height="100%"
              />
            </div>
          </div>
        </div>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Dialog from "primevue/dialog";
import TopBarComponents from "../TopBarComponents.vue";
import Image from "primevue/image";
defineProps({
  data: { type: Object, required: true },
});

const navTabs = ["Posts", "About", "Friends", "Photos", "Reels", "Check-ins"];
const isModalVisible = ref(false);

const openModal = () => (isModalVisible.value = true);
</script>

<style scoped>
/* Cover Photo */
.cover-photo-container {
  position: relative;
  height: 400px;
  overflow: hidden;
  background-color: var(--border-color);
  border-left: 0.5px solid #d9d9d9d8; /* subtle line */
  border-right: 0.5px solid #d9d9d9d8; /* subtle line */
  border-bottom: 0.5px solid #d9d9d9d8;
}
.cover-photo-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.cover-photo-button {
  position: absolute;
  bottom: 1rem;
  right: 2rem;
  font-weight: 600;
  background-color: var(--primary-color);
  color: var(--text-color);
  border: none;
}

/* Profile Header */
.profile-header-container {
  position: relative;
  background-color: var(--section-bg-color);
  border-bottom-left-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
  border-left: 0.5px solid #d9d9d9d8; /* subtle line */
  border-right: 0.5px solid #d9d9d9d8; /* subtle line */
  border-bottom: 0.5px solid #d9d9d9d8; /* subtle line */
}

/* Profile Picture */
.profile-picture-wrapper {
  position: relative;
  margin-top: -100px;
  border-radius: 50%;
  border: 0.5px solid #d9d9d9d8; /* subtle line */
}
.profile-picture-img {
  width: 168px;
  height: 168px;
  border-radius: 50%;
  border: 4px solid var(--section-bg-color);
  object-fit: cover;
}

/* User Info */
.user-name {
  font-weight: bold;
  color: var(--text-color);
}
.friend-count {
  font-weight: 600;
  color: var(--secondary-text-color);
}

/* Modal Image Grid */
.modal-image-wrapper {
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  border-radius: var(--border-radius-md);
  border: 1px solid var(--border-color-muted); /* subtle line */
  margin-bottom: 0.5rem;
}
.modal-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.search-container {
  display: flex;
  width: 100%;
  max-width: 500px;
  border-radius: 50px;
  overflow: hidden;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

.search-input {
  flex-grow: 1;
  border: none;
  padding: 0.625rem 1rem;
  border-radius: 50px 0 0 50px;
  outline: none;
  transition: all 0.3s ease;
}

.search-btn {
  border: none;
  background-color: #63c1a2; /* primary */
  color: #fff;
  padding: 0 1.5rem;
  border-radius: 0 50px 50px 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.3s, transform 0.2s;
}

.search-btn:hover {
  background-color: #52a88a;
  transform: scale(1.05);
}

.search-btn i {
  font-size: 1.1rem;
}
.search-container {
  display: flex;
  width: 100%;
  max-width: 500px;
  border-radius: 50px;
  overflow: hidden;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
  background-color: var(--section-bg-color);
}

.search-input {
  flex-grow: 1;
  border: none;
  padding: 0.625rem 1rem;
  border-radius: 50px 0 0 50px;
  outline: none;
  background-color: transparent;
  color: var(--text-color);
  transition: all 0.3s ease;
  min-width: 0; /* prevents overflow on small screens */
}

.search-input::placeholder {
  color: var(--light-text-color);
}

.search-btn {
  border: none;
  background-color: var(--primary-color);
  color: #fff;
  padding: 0 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.3s, transform 0.2s;
}

.search-btn:hover {
  background-color: var(--primary-hover-color);
  transform: scale(1.05);
}

.search-btn i {
  font-size: 1.1rem;
}

/* Responsive tweaks */
@media (max-width: 992px) {
  .profile-header-container {
    flex-direction: column;
    text-align: center;
  }

  .profile-picture-wrapper {
    margin: -80px auto 0;
  }

  .col-5 {
    width: 100% !important;
  }

  .search-container {
    max-width: 100%;
    border-radius: 10px;
  }

  .search-input {
    padding: 0.5rem 0.8rem;
    font-size: 0.95rem;
  }

  .search-btn {
    padding: 0 1rem;
  }
}

@media (max-width: 576px) {
  .cover-photo-container {
    height: 250px;
  }

  .user-name {
    font-size: 1.25rem;
  }

  .search-container {
    flex-direction: column;
    border-radius: 10px;
  }

  .search-input {
    border-radius: 10px 10px 0 0;
  }

  .btn-group {
    display: flex;
    width: 100%;
  }

  .search-btn {
    flex: 1;
    border-radius: 0 0 10px 10px;
  }
}
</style>
