<template>
  <div class="container-fluid">
    <HomeNav />
    <main>
      <HomeHero />
      <CategorySection />
      <BikeList />
    </main>
    <HomeFooter />
  </div>
</template>
<script setup>
import HomeFooter from "../components/Layout/HomeFooter.vue";
import HomeHero from "../components/Layout/HomeHero.vue";
import HomeNav from "../components/Layout/HomeNav.vue";
import CategoryCard from "../components/Home/CategoryCard.vue";
import CategorySection from "../components/Home/CategorySection.vue";
import BikeList from "../components/Home/BikeList.vue";
</script>
