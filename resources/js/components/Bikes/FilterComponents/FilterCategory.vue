
<template>
  <div class="filter-section">
    <h5 class="filter-title">Style of your bike</h5>
    <div class="category-pills">
      <button
        v-for="cat in categories"
        :key="cat.id"
        type="button"
        class="category-pill"
        :class="{ active: selected === cat.id }"
        @click="toggleCategory(cat.id)"
      >
        {{ cat.name }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();

//  Only one selected at a time
const selected = ref(null);
const categories = computed(() => store.getters["bikes/categories"]);

function toggleCategory(id) {
  // If already selected, unselect; else select new one
  selected.value = selected.value === id ? null : id;
  store.dispatch("homeFilter/updateCatId", id);
}

onMounted(async () => {
  // 1️ Load categories first
  await store.dispatch("bikes/fetchAllCategories");

  // 2️ Read category_id from URL query
  const cat_id = route.query.category_id;

  // 3️ If there’s a category in query, set it as selected
  if (cat_id) {
    selected.value = Number(cat_id);
  }
});
</script>



<style scoped>
.category-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem; /* smaller gap */
}

/* 🪶 Compact pill */
.category-pill {
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  border-radius: 40px;
  padding: 0.35rem 0.9rem; /* smaller padding */
  font-size: 0.85rem; /* smaller text */
  font-weight: 500;
  color: var(--text-color);
  cursor: pointer;
  transition: all 0.2s ease;
  line-height: 1.1;
}

.category-pill:hover {
  background-color: var(--primary-hover-color);
  color: #fff;
}

.category-pill.active {
  background-color: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* 🌿 On mobile, still comfy to tap */
@media (max-width: 768px) {
  .category-pill {
    flex: 1 1 calc(50% - 0.4rem);
    text-align: center;
    padding: 0.55rem 0.6rem;
    font-size: 0.9rem;
  }
}
</style>
