<template>
  <header>
    <nav class="navbar navbar-expand-sm">
      <div class="container-fluid">
        <!-- Brand -->
        <span class="navbar-brand text-decoration-none text-dark" href="#">
          <router-link to="/" class="text-decoration-none text-dark">{{
            t("brand")
          }}</router-link>
        </span>

        <!-- Toggler for small screens -->
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/bikes">{{
                t("nav.browse_bikes")
              }}</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/register?role=owner" class="nav-link" href="#">{{
                t("nav.become_owner")
              }}</router-link>
            </li>
            <li class="nav-item ms-lg-3">
              <LanguageSwitcher />
            </li>
            <li class="nav-item ms-lg-3">
              <HomeMenu />
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { useI18n } from "vue-i18n";
import HomeMenu from "./HomeMenu.vue";
import LanguageSwitcher from "../LanguageSwitcher.vue";

// Translation function
const { t } = useI18n();
</script>

<style scoped>
.navbar {
  padding: 1rem 0;
  background-color: var(--navbar-bg);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.navbar-brand {
  font-weight: 700;
  font-size: 1.5rem;
  color: var(--text-color);
}

.navbar-brand i {
  color: var(--primary-color);
}

.nav-link {
  font-weight: 500;
  color: var(--light-text-color);
  transition: color 0.2s;
}

.nav-link:hover {
  color: var(--primary-color);
}
</style>
