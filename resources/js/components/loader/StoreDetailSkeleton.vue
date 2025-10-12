<template>
  <div class="placeholder-glow" aria-hidden="true">
    <div class="profile-skeleton-container rounded">
      <!-- Cover Photo -->
      <div class="placeholder skeleton-cover rounded-top"></div>

      <!-- Profile Header -->
      <div class="profile-header-container px-4">
        <div class="d-flex flex-wrap align-items-end">
          <!-- Profile Picture -->
          <div class="placeholder skeleton-profile-pic"></div>

          <!-- Name and Friend Count -->
          <div class="ms-4 mb-2 flex-grow-1">
            <span class="placeholder col-4 fs-2"></span>
            <span class="placeholder col-2 d-block mt-2"></span>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex gap-2 mb-3 align-self-center">
            <a class="btn placeholder col-4" style="width: 130px"></a>
            <a class="btn placeholder col-4" style="width: 120px"></a>
            <a class="btn placeholder col-2" style="width: 50px"></a>
          </div>
        </div>

        <hr class="mt-2 skeleton-divider" />

        <!-- Navigation Tabs -->
        <div class="d-flex gap-4 pb-2">
          <span class="placeholder skeleton-tab"></span>
          <span class="placeholder skeleton-tab"></span>
          <span class="placeholder skeleton-tab"></span>
          <span class="placeholder skeleton-tab"></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Purely presentational; no script logic needed.
</script>

<style scoped>
/* ------------------------------
   Skeleton Container
------------------------------ */
.profile-skeleton-container {
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s;
}

/* ------------------------------
   Cover Photo
------------------------------ */
.skeleton-cover {
  width: 100%;
  height: 400px;
  background-color: var(--border-color);
  transition: background-color 0.3s;
}

/* ------------------------------
   Profile Header
------------------------------ */
.profile-header-container {
  background-color: var(--section-bg-color);
  transition: background-color 0.3s;
}

/* ------------------------------
   Profile Picture
------------------------------ */
.skeleton-profile-pic {
  width: 168px;
  height: 168px;
  border-radius: 50%;
  border: 4px solid var(--section-bg-color);
  transform: translateY(-50%);
  background-color: var(--border-color);
  transition: background-color 0.3s, border-color 0.3s;
}

/* ------------------------------
   Placeholder Elements
------------------------------ */
.placeholder {
  display: inline-block;
  background-color: var(--border-color) !important;
  border-radius: 4px;
  transition: background-color 0.3s;
}

/* Action buttons */
.btn.placeholder {
  height: 36px;
  background-color: var(--border-color);
  transition: background-color 0.3s;
}

/* Divider */
.skeleton-divider {
  border-color: var(--border-color);
  transition: border-color 0.3s;
}

/* Navigation Tabs */
.skeleton-tab {
  height: 24px;
  width: 100%;
  border-radius: 4px;
  background-color: var(--border-color);
  transition: background-color 0.3s;
}
</style>
