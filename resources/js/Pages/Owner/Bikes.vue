<template>
  <div>
    <TableSkeleton
      :columns="['#', 'Name', 'Store', 'Logo', 'Photo', 'Price', 'Address', 'Created']"
      :rows="10"
      :rowHeight="32"
      :striped="true"
      v-if="loading"
    />
    <div class="card" v-else>
      <DataTable
        :value="bikes"
        tableStyle="min-width: 60rem"
        :loading="loading"
        responsiveLayout="scroll"
        stripedRows
      >
        <!-- Header -->
        <template #header>
          <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="d-flex">
              <h4 class="text-xl font-bold col">Your Bikes</h4>
              <Button icon="pi pi-plus" class="ms-auto" rounded raised @click="addBike" />
            </div>
          </div>
        </template>

        <!-- Columns -->
        <Column field="title" header="Name" />
        <Column field="store_name" header="Store" />
        <Column header="Logo">
          <template #body="slotProps">
            <div
              class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center"
              style="display: inline-block"
            >
              <Image
                :src="slotProps.data.store_logo"
                alt="photo"
                preview
                width="80"
                class="w-24 rounded"
              />
            </div>
          </template>
        </Column>

        <Column header="photo">
          <template #body="slotProps">
            <div
              class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center"
              style="display: inline-block"
            >
              <Image
                :src="slotProps.data.photo"
                alt="photo"
                preview
                width="100"
                class="w-24 rounded"
              />
            </div>
          </template>
        </Column>

        <Column field="price" header="Price" />

        <Column field="store_address" header="Address">
          <template #body="slotProps">
            <span class="block max-w-xs truncate">{{
              slotProps.data.store_address
            }}</span>
          </template>
        </Column>

        <Column header="Created">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.created_at) }}
          </template>
        </Column>

        <!-- Footer -->
        <template #footer> Total {{ bikes.length }} Bikes found. </template>
      </DataTable>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref, computed } from "vue";
import TableSkeleton from "../../components/loader/SkeletonTable/TableSkeleton.vue";
import { useStore } from "vuex";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Image from "primevue/image";

const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const bikes = ref([]);
const loading = ref(false);
onMounted(async () => {
  loading.value = true;

  await store.dispatch("auth/fetchUser");

  setTimeout(async () => {
    if (!user.value) return;
    const response = await store.dispatch("bikes/fetchBikeList", user.value.id);
    bikes.value = response;
    loading.value = false;
  }, 200);
});

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};
const addBike = () => {
  router.push({ name: "owner.bikes.add" });
};
</script>
