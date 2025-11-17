<template>
  <div class="island-background">
    <img
      v-for="(icon, index) in randomIcons"
      :key="index"
      :src="icon.src"
      :style="{
        top: icon.top,
        left: icon.left,
        width: icon.size,
        animationDelay: icon.delay,
        transform: icon.rotate,
      }"
      class="island-bg-icon"
      alt=""
      loading="lazy"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
  maxIcons: { type: Number, default: 2 },
});

const iconPaths = [
  "/svg/coconut-cocktail-svgrepo-com.svg",
  "/svg/coconut-svgrepo-com.svg",
  "/svg/island-palm-tree-svgrepo-com.svg",
  "/svg/motorcycle-delivery-single-box-svgrepo-com.svg",
  "/svg/mountain-svgrepo-com.svg",
  "/svg/palm-tree-svgrepo-com.svg",
  "/svg/beach.png",
  "/svg/island.png",
  "/svg/jungle.png",
  "/svg/leaf.png",
  "/svg/marijuana.png",
  "/svg/motorbike.png",
  "/svg/persian-shield.png",
  "/svg/tropical-leaf.png",
  "/svg/tropical-leaves.png",
  "/svg/vacations.png",
];

const randomIcons = ref([]);

function getRandomPosition(existingPositions, minDistance) {
  let top, left, isTooClose, tries = 0;
  do {
    top = Math.random() * 80;
    left = Math.random() * 80;
    isTooClose = existingPositions.some(
      (p) => Math.hypot(p.top - top, p.left - left) < minDistance
    );
    tries++;
  } while (isTooClose && tries < 50);
  return { top, left };
}

onMounted(() => {
  const count = Math.max(1, props.maxIcons);
  const existing = [];
  const minDistance = 20;

  for (let i = 0; i < count; i++) {
    const src = iconPaths[Math.floor(Math.random() * iconPaths.length)];
    const { top, left } = getRandomPosition(existing, minDistance);
    existing.push({ top, left });
    randomIcons.value.push({
      src,
      top: `${top}%`,
      left: `${left}%`,
      size: `${60 + Math.random() * 100}px`,
      delay: `${Math.random() * 4}s`,
      rotate: `rotate(${Math.random() * 12 - 6}deg)`,
    });
  }
});
</script>

<style scoped>
.island-background {
  position: absolute;
  inset: 0;
  z-index: 0; /* ✅ bring above -1 so visible behind content */
  overflow: hidden;
  opacity: 0.6;
  pointer-events: none;
}

/* Slightly stronger for visibility */
.island-bg-icon {
  position: absolute;
  opacity: 0.05; /* ✅ increase visibility */
  pointer-events: none;
  animation: floatIsland 9s ease-in-out infinite alternate;
  will-change: transform;
  transition: transform 0.4s ease;
  mix-blend-mode: multiply;
}

@keyframes floatIsland {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  100% {
    transform: translateY(-12px) rotate(4deg);
  }
}

/* Dark mode tuning */
:root[data-bs-theme="dark"] .island-bg-icon {
  opacity: 0.1;
  filter: brightness(0.7);
}
</style>
