<template>
    <div class="date-guest-container rounded-3 border mb-3">
        <div class="position-relative">
            <div class="row g-0">
                <div class="col-6">
                    <div class="date-input-display p-2 border-end">
                        <label class="form-label text-uppercase small fw-bold"
                            >Pick Up</label
                        >
                        <span class="date-placeholder">{{
                            pickupDateDisplay
                        }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="date-input-display p-2">
                        <label class="form-label text-uppercase small fw-bold"
                            >Return</label
                        >
                        <span class="date-placeholder">{{
                            returnDateDisplay
                        }}</span>
                    </div>
                </div>
            </div>

            <Calendar
                v-model="dates"
                :disabledDates="bookedDates"
                :manualInput="false"
                :minDate="new Date()"
                selectionMode="range"
                class="calendar-overlay"
                dateFormat="dd/mm/yy"
                numberOfMonths="2"
                :showIcon="false"
                disabled="true" 
            >
                <template #date="slotProps">
                    <span v-if="isBooked(slotProps.date)" class="disabled-day">
                        {{ slotProps.date.day }}
                    </span>
                    <template v-else>{{ slotProps.date.day }}</template>
                </template>
            </Calendar>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import Calendar from "primevue/calendar";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    bookedDates: {
        type: Array,
        default: () => [],
    },
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:modelValue"]);

const toast = useToast();
const dates = ref([]);
watch(
    () => props.modelValue,
    (newVal) => {
        dates.value = newVal;
    },
    { immediate: true, deep: true }
);

// Watch local ref and emit changes to parent
watch(dates, (newVal) => {
    emit("update:modelValue", newVal);
});

watch(dates, (newVal) => {
    emit("update:modelValue", newVal);

    if (!newVal || newVal.length < 2) return;

    const [start, end] = newVal;

    const invalid = props.bookedDates.some((d) => d >= start && d <= end);

    if (invalid) {
        toast.add({
            severity: "warn",
            summary: "Invalid Date Range",
            detail: "Your selected range includes a booked date. Please choose another range.",
            life: 5000,
        });
        dates.value = null;
    }
});

const isBooked = (slotDate) => {
    return props.bookedDates.some(
        (d) =>
            d.getFullYear() === slotDate.year &&
            d.getMonth() === slotDate.month &&
            d.getDate() === slotDate.day
    );
};

const pickupDateDisplay = computed(() => {
    if (dates.value && dates.value[0])
        return dates.value[0].toLocaleDateString("en-GB");
    return "Add date";
});

const returnDateDisplay = computed(() => {
    if (dates.value && dates.value[1])
        return dates.value[1].toLocaleDateString("en-GB");
    return "Add date";
});
</script>

<style scoped>
.date-input-display {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 0.65rem;
    margin-bottom: 0.1rem;
    color: #333;
}

.date-placeholder {
    color: #6c757d;
    font-size: 0.9rem;
}

.calendar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.disabled-day {
    text-decoration: line-through;
    color: #b80a0a;
    pointer-events: none;
    opacity: 0.6;
}
</style>
