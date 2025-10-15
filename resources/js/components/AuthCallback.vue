<template>
  <div class="d-flex justify-content-center align-items-center min-vh-100 flex-column">
    <p v-if="loading">Logging in with Google...</p>
    <p v-else-if="error" class="text-danger">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "@/service/axios";
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();
const loading = ref(true);
const error = ref("");

// ✅ Helper for safe redirect
function getRedirectPath() {
  try {
    const saved = localStorage.getItem("oauth_redirect");
    if (!saved) return null;
    const parsed = JSON.parse(saved);
    localStorage.removeItem("oauth_redirect");
    return parsed;
  } catch {
    return null;
  }
}

onMounted(async () => {
  try {
    const params = new URLSearchParams(window.location.search);
    const tokenFromURL = params.get("token");
    const code = params.get("code");

    if (!tokenFromURL && !code) {
      router.push({ name: "Login" });
      return;
    }

    let token = tokenFromURL;
    let user = null;

    // 1️⃣ If Laravel returned a token directly
    if (tokenFromURL) {
      localStorage.setItem("auth_token", tokenFromURL);
      axios.defaults.headers.common["Authorization"] = `Bearer ${tokenFromURL}`;
      user = await store.dispatch("auth/fetchUser");
    }

    // 2️⃣ If only `code` returned (in dev flow)
    if (code) {
      const { data } = await axios.get(`${import.meta.env.VITE_API_URL}/auth/google/callback?code=${code}`);
      token = data.token;
      user = data.user;

      localStorage.setItem("auth_token", token);
      axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      store.commit("auth/SET_USER", user);
    }

    // 3️⃣ Save user locally
    if (user) localStorage.setItem("user", JSON.stringify(user));

    // 4️⃣ Handle redirect logic
    const redirectPath = getRedirectPath();
    if (redirectPath) {
      router.push(redirectPath);
      return;
    }

    // 5️⃣ Default redirects by role
    if (user && user.role === "owner") {
      router.push({ name: "owner.dashboard" });
    } else if (user && user.role === "renter") {
      router.push({ name: "Home" });
    } else {
      router.push("/");
    }

  } catch (err) {
    console.error(err);
    error.value = "Google login failed. Please try again.";
  } finally {
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
