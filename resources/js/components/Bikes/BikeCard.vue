<template>
  <div class="bike-card-wrapper">
    <div class="bike-card">
      <!-- 🌴 Island Background -->
      <IslandBackground :max-icons="2" />

      <!-- Image + Overlay -->
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
        <!-- Review stars top-left -->
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
        <!-- Wishlist heart top-right -->
        <div class="bike-wishlist" @click.stop="toggleWishlist">
          <font-awesome-icon :icon="[wishlist ? 'fas' : 'far', 'heart']" />
        </div>
      </div>

      <!-- Card body -->
      <div class="bike-card-body">
        <div class="d-flex">
          <div class="col">
            <router-link
              class="bike-card-link text-decoration-none"
              :to="{ name: 'bikes.detail', params: { id: props.id } }"
            >
              <h6 class="bike-card-title">
                <LimitText :text="props.title" :limit="20" />
              </h6>
              <p class="bike-card-model">
                <LimitText :text="props.model" :limit="20" />
              </p>
            </router-link>
          </div>
          <div class="bike-logo ms-auto" v-tooltip.top="store_name">
            <router-link :to="{ name: 'store.detail', params: { id: props.store_id } }">
              <img v-if="store_logo" :src="store_logo" alt="logo" />
              <i class="bi bi-shop fs-3 text-muted" v-else></i>
            </router-link>
          </div>
        </div>

        <div class="bike-card-footer">
          <!-- formatted price -->
          <span class="bike-card-price">{{ "฿" + formattedPrice + "/day"  }}</span>
          <router-link :to="{ name: 'bikes.detail', params: { id: props.id } }">
            <button class="btn btn-book no-wrap">{{ t("book_now") }}</button>
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
import IslandBackground from "../islandBackground.vue"; // 🌴 added import only
import LimitText from "./LimitText.vue";

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
/*  just add this small snippet to keep island background under content */
.bike-card {
  position: relative;
  isolation: isolate; /* ensures background layers correctly */
}

.bike-card .island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.6;
  pointer-events: none;
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
  height: 200px;
  overflow: hidden;
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
  padding: 0.25rem 0.5rem;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  font-size: 0.8rem;
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
  padding: 0.75rem;
}
.bike-card-title {
  font-weight: 600;
  font-size: 0.9rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--text-color);
}
.bike-card-model {
  font-size: 0.7rem;
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
  font-size: 1.05rem;
  color: var(--primary-color);
}
.btn-book {
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: var(--border-radius-md);
  padding: 0.35rem 0.75rem;
  font-size: 0.85rem;
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

/* Fullscreen overlay (unchanged) */
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
/* 🖥️  >1920px (Ultra-wide 4K monitors) */
@media (min-width: 1921px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-title {
    font-size: 1.1rem;
  }
  .bike-card-model {
    font-size: 0.95rem;
  }
  .bike-card-price {
    font-size: 1.15rem;
  }
  .btn-book {
    padding: 0.45rem 0.9rem;
    font-size: 0.95rem;
  }
  .bike-logo img {
    width: 50px;
    height: 50px;
  }
}
.no-wrap {
  white-space: nowrap;
}

/* 🖥️ 1601px – 1920px (Large desktop / 27"–32") */
@media (min-width: 1601px) and (max-width: 1920px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-title {
    font-size: 1rem;
  }
  .bike-card-model {
    font-size: 0.9rem;
  }
  .bike-card-price {
    font-size: 1rem;
  }
  .btn-book {
    padding: 0.35rem 0.75rem;
    font-size: 0.9rem;
  }
  .bike-logo img {
    width: 40px;
    height: 40px;
  }
}

/* 💻 1300px – 1600px (Large laptop / small desktop) */
@media (min-width: 1300px) and (max-width: 1600px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-title {
    font-size: 0.85rem;
  }
  .bike-card-model {
    font-size: 0.75rem;
  }
  .bike-card-price {
    font-size: 0.85rem;
  }
  .btn-book {
    padding: 0.3rem 0.6rem;
    font-size: 0.8rem;
  }
  .bike-logo img {
    width: 35px;
    height: 35px;
  }
}

/* 💻 990px – 1299px (MacBook Air / mid-laptop) */
@media (min-width: 1180px) and (max-width: 1299px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-img {
    height: 100px;
  }
  .bike-card-title {
    font-size: 0.75rem;
  }
  .bike-card-model {
    font-size: 0.65rem;
  }
  .bike-card-price {
    font-size: 0.75rem;
  }
  .btn-book {
    padding: 0.25rem 0.5rem;
    font-size: 0.7rem;
  }
  .bike-logo img {
    width: 30px;
    height: 30px;
  }
}
/* 💻 990px – 1299px (MacBook Air / mid-laptop) */
@media (min-width: 990px) and (max-width: 1179px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-img {
    height: 100px;
  }
  .bike-card-title {
    font-size: 0.8rem;
  }
  .bike-card-model {
    font-size: 0.6rem;
  }
  .bike-card-price {
    font-size: 0.7rem;
  }
  .btn-book {
    padding: 0.17rem 0.4rem;
    font-size: 0.5rem;
  }
  .bike-logo img {
    width: 20px;
    height: 20px;
  }
}

/* 📱 768px – 989px (Tablet landscape) */
@media (min-width: 768px) and (max-width: 989px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-title {
    font-size: 0.8rem;
  }
  .bike-card-model {
    font-size: 0.65rem;
  }
  .bike-card-price {
    font-size: 0.8rem;
  }
  .btn-book {
    padding: 0.3rem 0.55rem;
    font-size: 0.75rem;
  }
  .bike-logo img {
    width: 28px;
    height: 28px;
  }
}

/* 📱 576px – 767px (Tablet portrait / large mobile) */
@media (min-width: 576px) and (max-width: 767px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-title {
    font-size: 0.9rem;
  }
  .bike-card-model {
    font-size: 0.7rem;
  }
  .bike-card-price {
    font-size: 0.85rem;
  }
  .btn-book {
    padding: 0.25rem 0.6rem;
    font-size: 0.75rem;
  }
  .bike-logo img {
    width: 32px;
    height: 32px;
  }
}

/* 📱 <576px (Small phones) */
@media (max-width: 575px) {
  .bike-card-img-wrapper {
    height: auto;
  }
  .bike-card-title {
    font-size: 0.8rem;
  }
  .bike-card-model {
    font-size: 0.6rem;
  }
  .bike-card-price {
    font-size: 0.8rem;
  }
  .btn-book {
    padding: 0.2rem 0.5rem;
    font-size: 0.7rem;
  }
  .bike-logo img {
    width: 28px;
    height: 28px;
  }
}
</style>
