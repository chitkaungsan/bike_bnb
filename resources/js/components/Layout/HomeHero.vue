<template>
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">{{ t('hero.title') }}</h1>
      <p class="lead">{{ t('hero.subtitle') }}</p>

      <!-- Redesigned Search Bar -->
      <div class="search-bar-container">
        <div class="search-bar">
          <div class="search-field">
            <label for="location">{{ t('hero.search.location_label') }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faLocationDot" class="field-icon" />
              <input type="text" id="location" class="form-control" :placeholder="t('hero.search.location_placeholder')">
            </div>
          </div>
          <div class="divider"></div>
          <div class="search-field">
            <label for="dates">{{ t('hero.search.dates_label') }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faCalendarDays" class="field-icon" />
              <input type="text" id="dates" class="form-control"
                     onfocus="(this.type='date')"
                     onblur="(this.type='text')"
                     :placeholder="t('hero.search.dates_placeholder')">
            </div>
          </div>
          <div class="divider"></div>
          <div class="search-field">
            <label for="category">{{ t('hero.search.category_label') }}</label>
            <div class="input-wrapper">
              <font-awesome-icon :icon="faBicycle" class="field-icon" />
              <select id="category" class="form-select">
                <option selected>{{ t('hero.search.category_all') }}</option>
                <option value="1">{{ t('hero.search.category_mountain') }}</option>
                <option value="2">{{ t('hero.search.category_road') }}</option>
                <option value="3">{{ t('hero.search.category_city') }}</option>
                <option value="4">{{ t('hero.search.category_electric') }}</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn-search-round">
            <font-awesome-icon :icon="faMagnifyingGlass" />
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faLocationDot, faCalendarDays, faBicycle, faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<style scoped>
/* Scoped styles specific to the hero section component. */
.hero-section {
  background: url('/public/banner.jpg')
     center center;
  background-size: fit;
  padding: 7rem 0;
  color: white;
  text-align: center;
  position: relative;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

.hero-section .container {
  position: relative;
  z-index: 2;
}

.hero-title {
  font-size: 3rem;
  font-weight: 700;
}

/* =========================
   SEARCH BAR
========================= */
.search-bar-container {
  max-width: 800px;
  margin: 2rem auto 0;
}

.search-bar {
  display: flex;
  background-color: var(--background-color);
  border-radius: 50px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  padding: 0.5rem;
  align-items: center;
  width: 100%;
  transition: background 0.3s, color 0.3s;
}

.search-field {
  flex: 1;
  padding: 0.25rem 1rem;
  text-align: left;
}

.search-field label {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--text-color);
  margin-left: 25px;
}

.input-wrapper {
  display: flex;
  align-items: center;
}

.field-icon {
  color: var(--light-text-color);
  margin-right: 8px;
}

.search-field .form-control,
.search-field .form-select {
  border: none;
  background: none;
  padding: 0.1rem 0;
  color: var(--text-color);
  font-size: 0.9rem;
  width: 100%;
}

.search-field .form-control::placeholder {
  color: var(--light-text-color);
}

.search-field .form-control:focus,
.search-field .form-select:focus {
  box-shadow: none;
  color: var(--text-color);
}

.divider {
  width: 1px;
  height: 40px;
  background-color: var(--border-color);
}

.btn-search-round {
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: background-color 0.2s;
}

.btn-search-round:hover {
  background-color: var(--primary-hover-color);
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.2rem;
  }
  .search-bar {
    flex-direction: column;
    border-radius: 20px;
    padding: 1rem;
    align-items: stretch;
  }
  .search-field {
    padding: 0.5rem 0.25rem;
  }
  .divider {
    width: 100%;
    height: 1px;
    margin: 0.5rem 0;
  }
  .btn-search-round {
    width: 100%;
    border-radius: 10px;
    margin-top: 0.5rem;
  }
}
</style>

