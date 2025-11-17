<template>
  <footer class="footer position-relative">
    <!-- 🌴 Island Background -->
    <IslandBackground :max-icons="6" />

    <div class="container-fluid content-layer">
      <div class="row">
        <!-- Brand -->
        <div class="col-md-4 mb-3">
          <a class="navbar-brand" href="#"><i class="fa-solid fa-bicycle"></i> {{ t('brand') }}</a>
          <p class="mt-2">{{ t('footer.description') }}</p>
        </div>

        <!-- Explore -->
        <div class="col-md-2 offset-md-2 mb-3">
          <h5>{{ t('footer.explore.title') }}</h5>
          <ul class="list-unstyled">
            <li><a href="#">{{ t('footer.explore.home') }}</a></li>
            <li><a href="#">{{ t('footer.explore.how_it_works') }}</a></li>
            <li><a href="#">{{ t('footer.explore.about_us') }}</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div class="col-md-2 mb-3">
          <h5>{{ t('footer.support.title') }}</h5>
          <ul class="list-unstyled">
            <li><a href="#">{{ t('footer.support.help_center') }}</a></li>
            <li><a href="#">{{ t('footer.support.contact_us') }}</a></li>
            <li><a href="#">{{ t('footer.support.faqs') }}</a></li>
          </ul>
        </div>

        <!-- Connect -->
        <div class="col-md-2 mb-3">
          <h5>{{ t('footer.connect.title') }}</h5>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
          </div>
        </div>
      </div>
      <hr />
      <p class="text-center text-muted">{{ t('footer.copyright') }}</p>
    </div>
  </footer>
</template>

<script setup>
import { useI18n } from "vue-i18n";
import IslandBackground from "../islandBackground.vue"; // 🌴 added import

// Translation function
const { t } = useI18n();
</script>

<style scoped>
.footer {
  background-color: var(--section-bg-color);
  padding: 3rem 0;
  border-top: 1px solid var(--border-color);
  color: var(--light-text-color);
  transition: background-color 0.3s, color 0.3s, border-color 0.3s;
  margin-top: auto;
  position: relative;
  overflow: hidden;
  isolation: isolate; /* keeps z-index layering correct */
}

/* 🌴 Island background layer */
.footer .island-background {
  position: absolute;
  inset: 0;
  z-index: 0;
  opacity: 0.6; /* 🟢 nicely visible now */
  pointer-events: none;
  mix-blend-mode: multiply;
}

/* Foreground content */
.content-layer {
  position: relative;
  z-index: 2;
}

.footer h5 {
  font-weight: 600;
  color: var(--text-color);
}

.footer a {
  color: var(--light-text-color);
  text-decoration: none;
}
.footer a:hover {
  color: var(--primary-color);
}

.footer .navbar-brand {
  font-weight: 700;
  font-size: 1.5rem;
  color: var(--text-color);
  padding: 0;
}
.footer .navbar-brand i {
  color: var(--primary-color);
}

.footer .social-icons a {
  font-size: 1.5rem;
  margin-right: 1rem;
  color: var(--light-text-color);
}
.footer hr {
  border-color: var(--border-color);
  opacity: 0.6;
}

.footer p.text-muted {
  color: var(--light-text-color) !important;
}

/* 🌙 Dark mode tweak */
:root[data-bs-theme="dark"] .footer .island-background {
  opacity: 0.35;
  filter: brightness(0.8);
}
</style>
