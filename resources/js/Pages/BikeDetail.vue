<template>
  <div class="container-fluid">
    <HomeNav />
    <Images :mainImage="mainImage" :images="images" class="mt-2" />
    <ListingPage v-if="!loading" :mainImage="mainImage" :images="images" />
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import Images from "../components/BikeDetail/Images.vue";
import HomeNav from "../components/Layout/HomeNav.vue";
import ListingPage from "../components/BikeDetail/ListingPage.vue";

const store = useStore();
const route = useRoute();
const loading = ref(true);
const mainImage = ref(null);
const images = ref([]);

onMounted(async () => {
  const id = route.params.id;
  try {
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
