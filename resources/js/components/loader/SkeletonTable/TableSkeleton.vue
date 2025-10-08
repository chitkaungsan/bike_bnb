<!-- TableSkeleton.vue -->
<template>
  <div>
    <table class="table">
      <thead v-if="showHeaders">
        <tr>
          <th v-if="showCheckbox" style="width: 40px"></th>
          <th v-for="(col, i) in columns" :key="i">
            <div
              class="skeleton"
              :style="{
                height: headerHeight + 'px',
                width: headerWidths[i % headerWidths.length],
              }"
            />
          </th>
          <th v-if="showActions" style="width: 140px"></th>
        </tr>
      </thead>
      <tbody>
        <TableSkeletonRow
          v-for="n in rows"
          :key="n"
          :columns="columns"
          :showCheckbox="showCheckbox"
          :showActions="showActions"
          :compact="compact"
          :striped="striped"
          :rowIndex="n - 1"
        />
      </tbody>
    </table>
  </div>
</template>

<script setup>
import TableSkeletonRow from "./TableSkeletonRow.vue";

const props = defineProps({
  columns: { type: Array, default: () => ["Column 1", "Column 2", "Column 3"] },
  rows: { type: Number, default: 6 },
  showHeaders: { type: Boolean, default: true },
  showCheckbox: { type: Boolean, default: false },
  showActions: { type: Boolean, default: false },
  compact: { type: Boolean, default: false },
  striped: { type: Boolean, default: false },
});

const headerHeights = { default: 14, compact: 10 };
const headerHeight = props.compact ? headerHeights.compact : headerHeights.default;
const headerWidths = ["80%", "60%", "50%", "70%"];
</script>

<style scoped>
.table {
  border-collapse: separate;
  border-spacing: 0 8px;
}
.table thead th {
  border-bottom: none;
  background: transparent;
}
.table tbody tr {
  background: transparent;
}
</style>
