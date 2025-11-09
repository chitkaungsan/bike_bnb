<template>
    <div v-if="user && user.role=='renter'">
        <h1>Renter Bike</h1>
    </div>
    <div v-else>
        <Forbidden />
    </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import Forbidden from "../../components/Forbidden.vue";
const store = useStore();
const user = computed(() => store.getters["auth/user"]);

</script>