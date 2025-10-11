<template>
  <div class="container-fluid">
    <Images :mainImage="mainImage" :images="images" />
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import Images from "../components/BikeDetail/Images.vue";

const store = useStore();
const route = useRoute();
const bike = ref(null);
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
