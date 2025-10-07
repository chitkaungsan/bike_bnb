<template>
  <section class="section">
    <div class="container">
      <h2 class="section-title text-center">Browse by Category</h2>
      <div class="row g-4">
        <CategoryCard
          v-for="cat in categories"
          :key="cat.title"
          :icon="cat.icon"
          :title="cat.title"
          :selected="selectedCategory === cat.title"
          @click="selectCategory(cat.title)"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue";
import CategoryCard from "./CategoryCard.vue";

// Define categories
const categories = [
  { title: "Mountain", icon: ["fas", "mountain-sun"] },
  { title: "Road", icon: ["fas", "road"] },
  { title: "City", icon: ["fas", "city"] },
  { title: "Electric", icon: ["fas", "bolt"] },
];

// State for selected category
const selectedCategory = ref(null);

function selectCategory(title) {
  selectedCategory.value = title;
}
</script>

<style scoped>
.section {
  padding: 4rem 0;
  background-color: var(--background-color);
}
.section-title {
  font-weight: 700;
  margin-bottom: 2rem;
  color: var(--text-color);
}
</style>
