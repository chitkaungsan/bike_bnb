<template>
  <div class="container-fluid">
    <HomeNav />
    <div v-if="loading">
      <StoreDetailSkeleton />
    </div>
    <div v-else>
      <StoreCover :data="store_detail" />
      <BikeList />
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import StoreDetailSkeleton from "../components/loader/StoreDetailSkeleton.vue";
import StoreCover from "../components/StoreDetail/StoreCover.vue";
import HomeNav from "../components/Layout/HomeNav.vue";
import BikeList from "../components/StoreDetail/BikeList.vue";
const store = useStore();
const route = useRoute();
const loading = ref(false);
const store_detail = computed(() => store.getters["store/store_detail"]);
onMounted(async () => {
  loading.value = true;
  const id = route.params.id;
  try {
    await store.dispatch("store/getStoreWithId", id);
  } catch (err) {
    console.error("Failed to fetch store with id:", err);
  } finally {
    loading.value = false;
  }
});
</script>
