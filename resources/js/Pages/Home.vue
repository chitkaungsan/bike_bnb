<template>
 <div class="container-fluid">
    <HomeNav />
    <main>
     <HomeHero />
    </main>
    <HomeFooter/>
 </div>
</template>
<script setup>
import HomeFooter from '../components/Layout/HomeFooter.vue';
import HomeHero from '../components/Layout/HomeHero.vue';
import HomeNav from '../components/Layout/HomeNav.vue';
</script>