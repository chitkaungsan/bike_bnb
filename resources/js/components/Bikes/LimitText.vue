<template>
  <span>{{ truncatedText }}</span>
</template>

<script setup>
import { computed } from 'vue'

// Props: text and limit length
const props = defineProps({
  text: {
    type: String,
    required: true
  },
  limit: {
    type: Number,
    default: 10 // default limit length
  }
})

// Computed property to cut text
const truncatedText = computed(() => {
  if (!props.text) return ''
  return props.text.length > props.limit
    ? props.text.substring(0, props.limit) + '...'
    : props.text
})
</script>
