<template>
  <div class="container my-5">
    <div class="placeholder-glow" aria-hidden="true">
      <!-- Image Gallery Section -->
      <div class="row g-2 mb-4">
        <!-- Main Image -->
        <div class="col-lg-6">
          <div class="placeholder skeleton-main-img rounded"></div>
        </div>
        <!-- Thumbnail Grid -->
        <div class="col-lg-6">
          <div class="row g-2">
            <div class="col-6">
              <div class="placeholder skeleton-thumb-img rounded"></div>
            </div>
            <div class="col-6">
              <div class="placeholder skeleton-thumb-img rounded"></div>
            </div>
            <div class="col-6">
              <div class="placeholder skeleton-thumb-img rounded"></div>
            </div>
            <div class="col-6">
              <div class="placeholder skeleton-thumb-img rounded"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Details & Booking Section -->
      <div class="row">
        <!-- Left Column: Details -->
        <div class="col-lg-7">
          <span class="placeholder col-8 fs-2"></span>
          <span class="placeholder col-5"></span>
          <hr />
          <span class="placeholder col-6 fs-4 mb-3"></span>
          <span class="placeholder col-12"></span>
          <span class="placeholder col-12"></span>
          <span class="placeholder col-10"></span>
          <br /><br />
          <span class="placeholder col-12"></span>
          <span class="placeholder col-11"></span>
        </div>

        <!-- Right Column: Booking Card -->
        <div class="col-lg-5">
          <div class="card shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="placeholder col-4 fs-5"></span>
                <span class="placeholder col-3"></span>
              </div>

              <div class="border rounded p-2 mb-3">
                <div class="row">
                  <div class="col-6">
                    <span
                      class="placeholder col-8 d-block mb-2"
                      style="font-size: 0.75rem"
                    ></span>
                    <span class="placeholder col-10 d-block"></span>
                  </div>
                  <div class="col-6 border-start">
                    <span
                      class="placeholder col-8 d-block mb-2"
                      style="font-size: 0.75rem"
                    ></span>
                    <span class="placeholder col-10 d-block"></span>
                  </div>
                </div>
                <hr class="my-2" />
                <div>
                  <span
                    class="placeholder col-6 d-block mb-2"
                    style="font-size: 0.75rem"
                  ></span>
                  <span class="placeholder col-11 d-block"></span>
                </div>
              </div>

              <a class="btn btn-secondary btn-lg w-100 disabled placeholder"></a>
              <div class="text-center mt-2">
                <span class="placeholder col-5"></span>
              </div>

              <div class="text-center mt-4">
                <span class="placeholder col-4" style="font-size: 0.8rem"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// No script logic is needed for this static skeleton component.
</script>

<style scoped>
/* Scoped styles to avoid affecting other components */

/* We use aspect-ratio to make the image placeholders responsive. */
/* You can also use fixed heights if you prefer. */
.skeleton-main-img {
  width: 100%;
  aspect-ratio: 4 / 3;
}

.skeleton-thumb-img {
  width: 100%;
  aspect-ratio: 4 / 3;
}

/* Make the placeholder button visually similar to the example */
.btn.placeholder {
  height: 48px;
}
</style>
