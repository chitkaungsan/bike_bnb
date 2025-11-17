<template>
  <div class="bike-card-wrapper">
    <div class="bike-card">
      <div class="bike-card-img-wrapper">
        <div class="bike-logo ms-auto">
          <Image
            :src="props.image"
            alt="bike image"
            class="bike-card-img"
            preview
            width="100%"
            loading="lazy"
          />
        </div>
        <div class="bike-review">
          <span class="stars">
            <font-awesome-icon
              v-for="n in 1"
              :key="n"
              :icon="['fas', 'star']"
              :class="{ filled: n <= props.review }"
            />
          </span>
          <span class="review-score">{{ reviewDisplay }}</span>
        </div>
        <div class="bike-wishlist" @click.stop="toggleWishlist">
          <font-awesome-icon :icon="[wishlist ? 'fas' : 'far', 'heart']" />
        </div>
      </div>

      <div class="bike-card-body">
        <div class="d-flex">
          <div class="col">
            <router-link
              class="bike-card-link text-decoration-none"
              :to="{ name: 'bikes.detail', params: { id: props.id } }"
            >
              <h6 class="bike-card-title">{{ props.title }}</h6>
              <p class="bike-card-model">{{ props.model }}</p>
            </router-link>
          </div>
          <div class="bike-logo ms-auto">
            <figure class="text-end">
              <blockquote class="blockquote fs-6">Scooter</blockquote>
              <figcaption class="blockquote-footer">Auto</figcaption>
            </figure>
          </div>
        </div>

        <div class="bike-card-footer">
          <span class="bike-card-price">{{ "$" + formattedPrice + "/day" }}</span>
          <router-link :to="{ name: 'bikes.detail', params: { id: props.id } }">
            <button class="btn btn-sm btn-book">{{ t("book_now") }}</button>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import Image from "primevue/image";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const props = defineProps({
  id: { type: Number, default: 0 },
  image: { type: String, default: "" },
  title: { type: String, default: "" },
  model: { type: String, default: "" },
  price: { type: [Number, String], default: 0 },
  review: { type: Number, default: 0 },
  store_id: { type: Number, default: 0 },
  store_name: { type: String, default: "" },
  store_logo: { type: String, default: "" },
  category_name: { type: String, default: "" },
  type: { type: String, default: "" },
});

const wishlist = ref(false);
function toggleWishlist() {
  wishlist.value = !wishlist.value;
}

function formatNumberRaw(value) {
  if (value === null || value === undefined || value === "") return "";
  const cleaned = String(value).replace(/[^0-9.-]/g, "");
  const num = Number(cleaned);
  if (Number.isNaN(num)) return String(value);
  return new Intl.NumberFormat("en-US").format(num);
}

const formattedPrice = computed(() => formatNumberRaw(props.price));
const reviewDisplay = computed(() => {
  const r = Number(props.review ?? 0);
  return Number.isNaN(r) ? "" : r.toFixed(1);
});
</script>

<style scoped>
/* your existing styles kept, plus overlay styles at the bottom */
.bike-card-wrapper {
  flex: 0 0 100%;
}
@media (min-width: 576px) {
  .bike-card-wrapper {
    flex: 0 0 48%;
  }
  /* ADDED: Restore height for larger screens */
  .bike-card-img-wrapper {
    height: 200px;
  }
}
@media (min-width: 768px) {
  .bike-card-wrapper {
    flex: 0 0 30%;
  }
}
@media (min-width: 1200px) {
  .bike-card-wrapper {
    flex: 0 0 23%;
  }
}

.bike-card {
  border-radius: var(--border-radius-md);
  overflow: hidden;
  border: 1px solid var(--border-color);
  background-color: var(--section-bg-color);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
}
.bike-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.bike-card-img-wrapper {
  position: relative;
  width: 100%;
  height: 180px; /* MODIFIED: Reduced height for mobile */
  overflow: hidden; /* crop overflow */
}
.bike-card-img {
  width: 100%;
  height: 180px;
  object-fit: fill;
  object-position: center;
}

/* Review stars top-left */
.bike-review {
  position: absolute;
  top: 0.5rem;
  left: 0.5rem;
  background-color: rgba(0, 0, 0, 0.6);
  color: white;
  padding: 0.2rem 0.4rem; /* MODIFIED: Slightly smaller padding */
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  font-size: 0.75rem; /* MODIFIED: Reduced font size */
}
.bike-review .stars {
  margin-right: 0.25rem;
}
.bike-review .fa-star.filled {
  color: #ffc107;
}

/* Wishlist heart top-right */
.bike-wishlist {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  color: var(--primary-color);
  font-size: 1.2rem;
  cursor: pointer;
  background-color: rgba(255, 255, 255, 0.8);
  padding: 0.25rem;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Card body */
.bike-card-body {
  padding: 0.6rem; /* MODIFIED: Reduced padding */
}
.bike-card-title {
  font-weight: 600;
  font-size: 0.85rem; /* MODIFIED: Reduced font size */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--text-color);
}
.bike-card-model {
  font-size: 0.65rem; /* MODIFIED: Reduced font size */
  color: var(--secondary-text-color);
  margin-bottom: 0.3rem;
}
.bike-card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.bike-card-price {
  font-weight: 700;
  font-size: 0.9rem; /* MODIFIED: Reduced font size */
  color: var(--primary-color);
}
.btn-book {
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: var(--border-radius-md);
  padding: 0.3rem 0.6rem; /* MODIFIED: Reduced padding */
  font-size: 0.8rem; /* MODIFIED: Reduced font size */
}
.btn-book:hover {
  background-color: var(--primary-hover-color);
}
.bike-logo img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid var(--primary-color);
  object-fit: cover;
}

/* Fullscreen overlay */
.fullscreen-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(8px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}
.fullscreen-content {
  position: relative;
  max-width: 90%;
  max-height: 90%;
}
.fullscreen-content img {
  width: 100%;
  height: auto;
  border-radius: 10px;
}
.close-btn {
  position: absolute;
  top: -10px;
  right: -10px;
  color: white;
  font-size: 2rem;
  cursor: pointer;
  background: rgba(0, 0, 0, 0.6);
  border-radius: 50%;
  padding: 0 10px;
  border: none;
}
.no-scroll {
  overflow: hidden;
}
</style>
