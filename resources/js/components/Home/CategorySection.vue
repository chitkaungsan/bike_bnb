<template>
  <section class="section">
    <div class="container">
    <IslandBackground :max-icons="5"/>
      <div class="col-12 text-center mb-2">
        <h3 class="text-center bike-title">{{ $t("browse_by_category") }}</h3>
      </div>
      <div class="row g-4 mt-2">
        <CategoryCard
          v-for="cat in categories"
          :key="cat.key"
          :icon="cat.icon"
          :title="t('categories.' + cat.key)"
          :selected="selectedCategory === cat.key"
          @click="selectCategory(cat.key)"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue";
import CategoryCard from "./CategoryCard.vue";
import { useI18n } from "vue-i18n";
import IslandBackground from "../islandBackground.vue";

const { t } = useI18n();
// Define categories
const categories = [
  { key: "mountain", icon: ["fas", "mountain-sun"] },
  { key: "road", icon: ["fas", "road"] },
  { key: "city", icon: ["fas", "city"] },
  { key: "electric", icon: ["fas", "bolt"] },
];

// State for selected category
const selectedCategory = ref(null);

function selectCategory(key) {
  selectedCategory.value = key;
}
</script>

<style scoped>
.section {
  padding: 4rem 0;
  background-color: transparent; /* let icons show through */
  position: relative;
  overflow: hidden;
  isolation: isolate; /* keeps z-index layering clean */
}

.section::before {
  /* create subtle base background */
  content: "";
  position: absolute;
  inset: 0;
  background-color: var(--background-color);
  z-index: -2; /* stays behind icons */
}

.section-title {
  font-weight: 700;
  margin-bottom: 2rem;
  color: var(--text-color);
}
.bike-title {
  font-size: 3rem;
  font-weight: 700;
  background: linear-gradient(90deg, #63c1a2, #63c1a2); /* gradient text */
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  display: inline-block;
  margin-bottom: 2rem;
}

.bike-title::after {
  content: "";
  display: block;
  width: 60px;
  height: 4px;
  background: #63c1a2;
  margin: 0.5rem auto 0;
  border-radius: 2px;
  transition: width 0.3s;
}

.bike-title:hover::after {
  width: 100px; /* expand underline on hover */
}

.bike-title {
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}
</style>
