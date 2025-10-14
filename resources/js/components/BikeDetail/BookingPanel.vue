<template>
    <div class="booking-panel-wrapper" ref="wrapperRef">
        <div class="d-none d-lg-block">
            <div class="booking-panel" ref="panelRef" :class="stickyClass">
                <div class="p-4 shadow rounded-4 border">
                    <div class="d-flex align-items-center mb-3">
                        <span class="price-amount fw-bold fs-4">{{
                            formattedPrice
                        }}</span>
                        <span class="price-period ms-1 text-muted">day</span>
                    </div>

                    <div class="date-guest-container rounded-3 border mb-3">
                        <div class="position-relative">
                            <div class="row g-0">
                                <div class="col-6">
                                    <div
                                        class="date-input-display p-2 border-end"
                                    >
                                        <label
                                            class="form-label text-uppercase small fw-bold"
                                            >Pick Up</label
                                        >
                                        <span class="date-placeholder">{{
                                            pickupDateDisplay
                                        }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="date-input-display p-2">
                                        <label
                                            class="form-label text-uppercase small fw-bold"
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
                            >
                                <template #date="slotProps">
                                    <span
                                        v-if="isBooked(slotProps.date)"
                                        class="disabled-day"
                                    >
                                        {{ slotProps.date.day }}
                                    </span>
                                    <template v-else>{{
                                        slotProps.date.day
                                    }}</template>
                                </template>
                            </Calendar>
                        </div>

                        <div class="border-top">
                            <Dropdown
                                v-model="selectedGuests"
                                :options="guestOptions"
                                placeholder="Add guests"
                                class="w-100 guest-dropdown"
                            >
                                <template #value="slotProps">
                                    <div class="guest-input-display p-2">
                                        <label
                                            class="form-label text-uppercase small fw-bold"
                                            >Guests</label
                                        >
                                        <span
                                            v-if="slotProps.value"
                                            class="guest-placeholder"
                                            >{{
                                                slotProps.value
                                            }}
                                            guest(s)</span
                                        >
                                        <span
                                            v-else
                                            class="guest-placeholder text-muted"
                                            >{{ slotProps.placeholder }}</span
                                        >
                                    </div>
                                </template>
                                <template #option="slotProps">
                                    <div>{{ slotProps.option }} guest(s)</div>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <Button class="w-100 p-3 reserve-btn" label="Book Now" />

                    <p class="text-center text-muted small mt-2 mb-0">
                        You won't be charged yet
                    </p>
                </div>

                <div class="text-center mt-3">
                    <a href="#" class="text-muted text-decoration-underline">
                        <i class="bi bi-flag me-2"></i>Report this listing
                    </a>
                </div>
            </div>
        </div>

        <div class="mobile-booking-footer d-lg-none">
            <div class="footer-content">
                <div class="price-info">
                    <span class="price-amount fw-bold">{{
                        formattedPrice
                    }}</span>
                    <span class="price-period text-muted"> day</span>
                </div>
                <Button class="reserve-btn-mobile" label="Book Now" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed,watch } from "vue";
import Calendar from "primevue/calendar";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";


// --- Props ---
const props = defineProps({
    pricePerNight: {
        type: Number,
        required: true,
    },
    currency: {
        type: String,
        default: "THB",
    },
    parentContainer: {
        type: Object,
        default: null,
    },
});

// --- State ---
const dates = ref();
const selectedGuests = ref();
const guestOptions = ref([1, 2, 3, 4, "5+"]);
const wrapperRef = ref(null);
const panelRef = ref(null);
const stickyClass = ref("");
const bookedDates = ref([
    new Date(2025, 9, 20), // Oct 20, 2025
    new Date(2025, 9, 25), // Oct 25, 2025
    new Date(2025, 10, 1), // Nov 1, 2025
]);
const isBooked = (slotDate) => {
    return bookedDates.value.some(
        (d) =>
            d.getFullYear() === slotDate.year &&
            d.getMonth() === slotDate.month &&
            d.getDate() === slotDate.day
    );
};
watch(dates, (newVal) => {
    if (!newVal || newVal.length < 2) return;

    const [start, end] = newVal;

    // Check if any booked date falls between pickup and return
    const invalid = bookedDates.value.some((d) => d >= start && d <= end);

    if (invalid) {
        alert("⚠️ Your selected range includes a booked date. Please choose another range.");
        dates.value = null; // reset calendar selection
    }
});

// --- Computed Properties ---
const formattedPrice = computed(() => {
    return new Intl.NumberFormat("th-TH", {
        style: "currency",
        currency: props.currency,
        minimumFractionDigits: 0,
    }).format(props.pricePerNight);
});

const pickupDateDisplay = computed(() => {
    if (dates.value && dates.value[0]) {
        return dates.value[0].toLocaleDateString("en-GB"); // Format as DD/MM/YYYY
    }
    return "Add date";
});

const returnDateDisplay = computed(() => {
    if (dates.value && dates.value[1]) {
        return dates.value[1].toLocaleDateString("en-GB"); // Format as DD/MM/YYYY
    }
    return "Add date";
});

const testFunction = () => {
    console.log("Test function called");
}
// --- Logic for Sticky Behavior ---
const handleScroll = () => {
    if (window.innerWidth < 992) {
        if (panelRef.value) {
            panelRef.value.style.width = null;
            panelRef.value.style.top = null;
        }
        stickyClass.value = "";
        return;
    }
    

    if (!wrapperRef.value || !panelRef.value || !props.parentContainer) return;

    const panelEl = panelRef.value;
    const parentRect = props.parentContainer.getBoundingClientRect();
    const wrapperRect = wrapperRef.value.getBoundingClientRect();
    const panelHeight = panelEl.offsetHeight;
    const topOffset = 20;
    const bottomOffset = 20;

    if (wrapperRect.top <= topOffset) {
        const wrapperWidth = wrapperRef.value.offsetWidth;
        panelEl.style.width = `${wrapperWidth}px`;

        if (parentRect.bottom < panelHeight + bottomOffset) {
            stickyClass.value = "is-sticky-bottom";
            panelEl.style.top = null;
        } else {
            stickyClass.value = "is-sticky-top";
            panelEl.style.top = `${topOffset}px`;
        }
    } else {
        stickyClass.value = "";
        panelEl.style.width = null;
        panelEl.style.top = null;
    }
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
/* --- General & Sticky Styles --- */
.booking-panel-wrapper {
    position: relative;
}
.is-sticky-top {
    position: fixed;
}
.is-sticky-bottom {
    position: absolute;
    bottom: 0;
}
.price-amount {
    color: #222;
}

/* --- Date & Guest Input Container --- */
.date-input-display,
.guest-input-display {
    display: flex;
    flex-direction: column;
}
.form-label {
    font-size: 0.65rem;
    margin-bottom: 0.1rem;
    color: #333;
}
.date-placeholder,
.guest-placeholder {
    color: #6c757d;
    font-size: 0.9rem;
}
.calendar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0; /* Makes the default input invisible */
    cursor: pointer;
}

/* --- Reserve Button Styles --- */
@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

.reserve-btn,
.reserve-btn-mobile {
    position: relative;
    overflow: hidden;
    background: linear-gradient(90deg, #e61e4d 0%, #d70466 50%, #c3002d 100%);
    background-size: 200% auto;
    color: white;
    font-weight: 600;
    border: none;
    border-radius: 0.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.reserve-btn:hover,
.reserve-btn-mobile:hover {
    transform: scale(1.03);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.reserve-btn::after,
.reserve-btn-mobile::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 200%;
    height: 100%;
    background: linear-gradient(
        120deg,
        rgba(255, 255, 255, 0) 20%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 80%
    );
    border-radius: inherit;
    pointer-events: none;
    animation: shimmer 2.5s linear infinite;
}

/* --- Mobile Footer Styles --- */
.mobile-booking-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #fff;
    border-top: 1px solid #ddd;
    padding: 0.75rem 1rem;
    z-index: 1000;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.08);
}
.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.reserve-btn-mobile {
    padding: 0.75rem 1.5rem;
}

/* --- PrimeVue Component Overrides --- */
:deep(.p-calendar .p-inputtext),
:deep(.p-dropdown .p-inputtext),
:deep(.p-dropdown) {
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
}

:deep(.guest-dropdown .p-dropdown-label) {
    padding: 0 !important;
}
:deep(.p-datepicker-calendar td > span[data-p-disabled="true"]) {
    /* Overwrites default text decoration and adds the line */
    text-decoration: none;
    background-image: linear-gradient(
        to top right,
        transparent 49.5%,
        #d2d2d2 49.5%,
        #d2d2d2 50.5%,
        transparent 50.5%
    );
    color: #cccccc; /* Ensures the date number is also light gray */
    cursor: default;
}
.disabled-day {
    text-decoration: line-through;
    color: #b80a0a;
    pointer-events: none; /* prevent selection */
    opacity: 0.6;
}
</style>
