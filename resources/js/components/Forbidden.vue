<template>
  <div class="forbidden-container">
    <h1 class="error-code">403</h1>
    <h2 class="error-message">Forbidden</h2>
    <p class="error-description">
      You don't have permission to access this page.
    </p>

    <button class="btn-home" @click="goHome">
      Go Back Home
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goHome = () => {
  router.push({ name: 'Home' }) // make sure your home route is named 'home'
}
</script>

<style scoped>
.forbidden-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  background-color: #f8f9fa;
  color: #333;
  padding: 20px;
}

.error-code {
  font-size: 6rem;
  margin-bottom: 0.5rem;
}

.error-message {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.error-description {
  font-size: 1.1rem;
  margin-bottom: 2rem;
  color: #666;
}

.btn-home {
  padding: 10px 20px;
  border: none;
  background-color: #6c757d;
  color: white;
  font-size: 1rem;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-home:hover {
  background-color: #0056b3;
}
</style>
