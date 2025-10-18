<template>
  <div>
    <!-- Skeleton Loader -->
    <TableSkeleton
      :columns="['#', 'booking id', 'Photo', 'Modal', 'Store', 'Rate', 'Total', 'Status','Rider','Rider Phone', 'Created', 'Action']"
      :rows="10" :rowHeight="32" :striped="true" v-if="loading" />

    <!-- Data Table -->
    <div class="card" v-else>
      <DataTable :value="bookings" tableStyle="min-width: 70rem" :loading="loading" responsiveLayout="scroll"
        stripedRows>
        <!-- Header -->
        <template #header>
          <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="d-flex">
              <h4 class="text-xl font-bold col"> Owner Bookings</h4>
            </div>
          </div>
        </template>
        <Column header="#" style="width: 60px; text-align:center;">
          <template #body="slotProps">
            {{ slotProps.index + 1 }}
          </template>
        </Column>


        <!-- Columns -->
        <Column field="id" header="BookID" style="width: 60px; text-align:center;" />

        <!-- 🏍 Bike Photo + Name -->
        <Column header="Photo">
          <template #body="slotProps">
            <div class="flex items-center gap-3">
              <Image :src="slotProps.data.bike_photo" alt="bike photo" class="bike-photo" width="200" preview />
            </div>
          </template>
        </Column>
        <Column header="Modal">
          <template #body="slotProps">
            <div>
              <div class="font-semibold text-gray-800">{{ slotProps.data.bike_title }}</div>
              <div class="text-sm text-gray-500">
                {{ slotProps.data.bike_model }} ({{ slotProps.data.bike_year }})
              </div>
            </div>
          </template>
        </Column>

        <!-- Store Name -->
        <Column field="store_name" header="Store" />

        <!-- Start Date and end Date -->
        <Column header="Date Range">
          <template #body="slotProps">
            <div style="text-align:center;">
              <div class="font-semibold text-gray-700">
                {{ formatDate(slotProps.data.start_date) }}
              </div>
              <div class="text-gray-500 text-sm">
                {{ formatDate(slotProps.data.end_date) }}
              </div>
            </div>
          </template>
        </Column>

        <!-- Total Price -->
        <Column field="total_price" header="Total (THB)" style="text-align:left;">
          <template #body="slotProps">
            <span class="font-semibold text-green-600">
              {{ Number(slotProps.data.total_price).toLocaleString() }}
            </span>
          </template>
        </Column>

        <!-- Status -->
        <Column field="status" header="Status" style="text-align:left;">
          <template #body="slotProps">
            <span :class="[
              'px-3 py-1 rounded text-sm capitalize',
              slotProps.data.status === 'confirmed'
                ? 'bg-green-500 text-white'
                : slotProps.data.status === 'pending'
                  ? 'bg-yellow-500 text-black'
                  : 'bg-red-500 text-white'
            ]">
              {{ slotProps.data.status }}
            </span>
          </template>
        </Column>

        <!-- Rider -->
         <Column field="created_at" header="Rider" style="text-align:center;">
          <template #body="slotProps">
            {{ (slotProps.data.rider_name) }}
          </template>
        </Column>
        <!-- Phone -->
         <Column field="created_at" header="Rider Phone" style="text-align:center;">
          <template #body="slotProps">
            {{ (slotProps.data.rider_phone) }}
          </template>
        </Column>
        <!-- Created At -->
        <Column field="created_at" header="Created" style="text-align:center;">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.created_at) }}
          </template>
        </Column>

        

        <!-- Actions -->
        <Column header="Action" style="text-align:center; width: 80px;">
          <template #body="slotProps">
            <DropdownMenu :booking="slotProps.data" />
          </template>
        </Column>

        <!-- Footer -->
        <template #footer>
          Total {{ bookings.length }} bookings found.
        </template>
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
import DropdownMenu from "../../components/Owner/components/Booking/Dropdownmenu.vue";

const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const bookings = ref([]);
const loading = ref(false);

onMounted(async () => {
  loading.value = true;

  if (!user.value) {
    await store.dispatch("auth/fetchUser");
  }

  if (user.value) {
    const response = await store.dispatch("booking/fetchOwnerBookings", user.value.id);
    bookings.value = response;
  }

  loading.value = false;
});

const formatDate = (dateStr) => {
  if (!dateStr) return "-";
  const date = new Date(dateStr);
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};
</script>

<style scoped>
/*  Make bike photo neat and consistent */
.bike-photo-wrapper {
  width: 200px;
  height: 200px;
  flex-shrink: 0;
  border-radius: 6px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}



/*  Sabai sabai spacing for table */
.card {
  padding: 1rem;
}

.text-gray-500 {
  color: #6b7280;
}

.flex-col span {
  line-height: 1.2;
  margin-bottom: 2px;
}


.flex-col span:first-child {
  font-weight: 600;
}

.flex-col span:last-child {
  font-size: 0.85rem;
}

.date-range {
  display: flex;
  flex-direction: column;
  align-items: center;
  line-height: 1.2;
}
</style>
