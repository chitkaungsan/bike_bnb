<template>
  <div class="bike-card-wrapper">
    <div class="bike-card">
      <div class="bike-card-img-wrapper">
        <!-- Image placeholder -->
        <div class="skeleton-img"></div>
      </div>

      <!-- Card body -->
      <div class="bike-card-body">
        <div class="d-flex">
          <div class="col">
            <h6 class="bike-card-title skeleton-text short"></h6>
            <p class="bike-card-model skeleton-text long"></p>
          </div>
          <div class="bike-logo ms-auto">
            <div class="skeleton-logo-small"></div>
          </div>
        </div>

        <div class="bike-card-footer">
          <span class="bike-card-price skeleton-text short"></span>
          <div class="btn-book skeleton-btn"></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup></script>
<style scoped>
/* Image */
.skeleton-img {
  width: 100%;
  height: 180px;
  border-radius: 4px;
  background: #e0e0e0;
  animation: pulse 1.5s infinite;
}

/* Text placeholders */
.skeleton-text {
  display: inline-block;
  height: 12px;
  background: #e0e0e0;
  border-radius: 4px;
  animation: pulse 1.5s infinite;
  margin-bottom: 4px;
}
.skeleton-text.short {
  width: 40%;
}
.skeleton-text.long {
  width: 70%;
}

/* Review stars */
.skeleton-star {
  color: #e0e0e0;
  font-size: 0.8rem;
  margin-right: 2px;
}

/* Wishlist heart */
.skeleton-heart {
  color: #e0e0e0;
  font-size: 1.2rem;
}

/* Store logo */
.skeleton-logo {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e0e0e0;
  border: 2px solid #ccc;
  animation: pulse 1.5s infinite;
}
.skeleton-logo-small {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #e0e0e0;
  animation: pulse 1.5s infinite;
}

/* Button */
.skeleton-btn {
  width: 60px;
  height: 24px;
  border-radius: 4px;
  background: #e0e0e0;
  animation: pulse 1.5s infinite;
}

/* Pulse animation */
@keyframes pulse {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.4;
  }
  100% {
    opacity: 1;
  }
}
</style>
