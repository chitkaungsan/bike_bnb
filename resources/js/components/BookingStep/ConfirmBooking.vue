<template>
    <div>
        <h1>Confirm Booking</h1>
    </div>
</template>
<script setup>  
import { ref } from "vue";
const props = defineProps({
    bookingDetails: {
        type: Object,
        required: true
    }
});
</script>