<template>
  <div class="container-fluid" v-if="!loading">
    <HomeNav />
    <Images :mainImage="mainImage" :images="images" class="mt-2" />
    <ListingPage v-if="!loading" :mainImage="mainImage" :images="images" />
  </div>
  <DetailSkeleton v-else />
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import Images from "../components/BikeDetail/Images.vue";
import HomeNav from "../components/Layout/HomeNav.vue";
import ListingPage from "../components/BikeDetail/ListingPage.vue";
import DetailSkeleton from "../components/loader/DetailSkeleton.vue";
const store = useStore();
const route = useRoute();
const loading = ref(false);
const mainImage = ref(null);
const images = ref([]);

onMounted(async () => {
  const id = route.params.id;
  try {
    loading.value = true;
    const res = await store.dispatch("bikes/getBike", id);
    mainImage.value = res.photo;
    images.value = res.images;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
});
</script>
