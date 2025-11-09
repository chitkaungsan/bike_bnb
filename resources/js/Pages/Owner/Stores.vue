<template>
  <div v-if="user && user.role=='owner'">
    <TableSkeleton
      :columns="['#', 'Name', 'Email', 'Status']"
      :rows="10"
      :rowHeight="32"
      :striped="true"
      v-if="loading"
    />
    <div class="card" v-else>
      <DataTable
        :value="stores"
        tableStyle="min-width: 60rem"
        :loading="loading"
        responsiveLayout="scroll"
        stripedRows
      >
        <!-- Header -->
        <template #header>
          <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="d-flex">
              <h4 class="text-xl font-bold col">Your stores</h4>
              <Button
                icon="pi pi-plus"
                class="ms-auto"
                rounded
                raised
                @click="addStore"
              />
            </div>
          </div>
        </template>

        <!-- Columns -->
        <Column field="name" header="Name" />
        <Column header="Logo">
          <template #body="slotProps">
            <div
              class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center"
              style="display: inline-block"
            >
              <Image
                :src="slotProps.data.logo"
                alt="logo"
                preview
                width="100"
                class="w-24 rounded"
              />
            </div>
          </template>
        </Column>

        <Column field="phone" header="Phone" />

        <Column field="address" header="Address">
          <template #body="slotProps">
            <span class="block max-w-xs truncate">{{ slotProps.data.address }}</span>
          </template>
        </Column>

        <Column header="Created">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.created_at) }}
          </template>
        </Column>

        <!-- Footer -->
        <template #footer> Total {{ stores.length }} shops found. </template>
      </DataTable>
    </div>
    <div class="d-flex justify-content-end mt-3"></div>
  </div>
  <div v-else>
    <Forbidden /> 
  </div>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import TableSkeleton from "../../components/loader/SkeletonTable/TableSkeleton.vue";
import { useStore } from "vuex";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Image from "primevue/image";
import Forbidden from "../../components/Forbidden.vue";
const store = useStore();

const stores = ref([]);
const user = computed(() => store.getters["auth/user"]);
const loading = ref(false);
onMounted(async () => {
  loading.value = true;
  await store.dispatch("auth/fetchUser");
  if (!user.value) {
    loading.value = false;
    return;
  }
  const response = await store.dispatch("store/fetchStoreList", user.value.id);
  stores.value = response;
  loading.value = false;
});
const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};

const addStore = () => {
  router.push({ name: "owner.stores.add" });
};
</script>
