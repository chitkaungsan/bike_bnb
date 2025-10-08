<template>
  <div class="d-flex justify-content-center align-items-center min-vh-100 flex-column">
    <p v-if="loading">Logging in with Google...</p>
    <p v-else-if="error" class="text-danger">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "../service/axios";
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();
const loading = ref(true);
const error = ref("");

onMounted(async () => {
  try {
    // 1️⃣ Check if token is in URL (redirect from Laravel)
    const params = new URLSearchParams(window.location.search);
    const tokenFromURL = params.get("token");
    const code = params.get("code");

    if (tokenFromURL) {
      localStorage.setItem("token", tokenFromURL);

      // Update axios default header immediately
      axios.defaults.headers.common["Authorization"] = `Bearer ${tokenFromURL}`;

      // Fetch user and commit to store
      const user = await store.dispatch("auth/fetchUser");
      store.commit("auth/SET_USER", user);

      localStorage.setItem("user", JSON.stringify(user));

      if (user && user.role === "owner") router.push({ name: "owner.dashboard" });
      else window.location.href = "/";
      return;
    }

    if (code) {
      const response = await axios.get(
        `${import.meta.env.VITE_API_URL}/auth/google/callback?code=${code}`
      );
      const { token, user } = response.data;

      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
      console.log(user);
      if (user.role === "owner") router.push({ name: "owner.dashboard" });
      else window.location.href = "/";
      return;
    }

    // If neither token nor code, redirect to login
    router.push("/login");
  } catch (err) {
    console.error(err);
    error.value = "Google login failed. Please try again.";
    loading.value = false;
  }
});
</script>

<style scoped>
p {
  font-size: 1rem;
}
.text-danger {
  color: #dc3545;
}
</style>
