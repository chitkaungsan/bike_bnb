<template>
  <div>
    <Button
      icon="pi pi-bars"
      class="p-button-text p-button-rounded border-top border-bottom border-left border-right"
      @click="toggleMenu"
      aria-haspopup="true"
      aria-controls="menu"
    />
    <Menu
      ref="menu"
      :popup="true"
      :model="menuItems"
      id="menu"
    />
  </div>
</template>

<script setup>
import { ref } from "vue";
import Button from "primevue/button";
import Menu from "primevue/menu";

const props = defineProps({
  booking: Object,
});

const menu = ref();

const toggleMenu = (event) => {
  menu.value.toggle(event);
};

// Example dropdown menu items
const menuItems = ref([
  {
    label: "View Details",
    icon: "pi pi-eye",
    command: () => {
      console.log("View booking:", props.booking.id);
    },
  },
  {
    label: "Edit",
    icon: "pi pi-pencil",
    command: () => {
      console.log("Edit booking:", props.booking.id);
    },
  },
  {
    label: "Delete",
    icon: "pi pi-trash",
    command: () => {
      console.log("Delete booking:", props.booking.id);
    },
  },
]);
</script>
<style scoped>

</style>
