<template>
  <tr :class="rowClass">
    <td v-if="showCheckbox" style="width: 40px">
      <div
        class="skeleton skeleton-circle"
        :style="{ height: checkboxSize + 'px', width: checkboxSize + 'px' }"
      ></div>
    </td>
    <td v-for="(col, index) in columns" :key="index">
      <div class="skeleton" :style="cellStyle(index)"></div>
    </td>
    <td v-if="showActions" style="width: 140px">
      <div class="d-flex gap-2">
        <div class="skeleton" style="height: 28px; width: 70px"></div>
        <div class="skeleton" style="height: 28px; width: 50px"></div>
      </div>
    </td>
  </tr>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  columns: { type: Array, default: () => [] },
  showCheckbox: { type: Boolean, default: false },
  showActions: { type: Boolean, default: false },
  compact: { type: Boolean, default: false },
  striped: { type: Boolean, default: false },
  rowIndex: { type: Number, default: 0 },
  rowHeight: { type: Number, default: 18 },
});

const checkboxSize = props.compact ? 18 : 22;

const rowClass = computed(() => ({
  "table-active": props.striped && props.rowIndex % 2 === 1,
}));

const cellStyle = (i) => {
  // use the prop value directly (rowHeight overrides compact/default)
  const height = props.rowHeight || (props.compact ? 18 : 24);

  // vary widths by column index to make it look more organic
  const widths = ["25%", "35%", "45%", "55%", "30%", "40%", "50%"];
  const width = widths[i % widths.length];

  // return full style object
  return {
    height: height + "px",
    width,
    marginTop: "6px",
    marginBottom: "6px",
  };
};
</script>

<style scoped>
.skeleton {
  display: inline-block;
  background: linear-gradient(90deg, #eee 25%, #f5f5f5 37%, #eee 63%);
  background-size: 400% 100%;
  border-radius: 4px;
  animation: shimmer 1.4s ease-in-out infinite;
}
.skeleton-circle {
  border-radius: 50%;
}

@keyframes shimmer {
  0% {
    background-position: 100% 0;
  }
  100% {
    background-position: -100% 0;
  }
}
</style>
