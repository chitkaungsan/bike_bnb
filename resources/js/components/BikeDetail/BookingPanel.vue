<template>
    <div class="booking-panel-wrapper" ref="wrapperRef">
        <div class="d-none d-lg-block">
            <div class="booking-panel" ref="panelRef" :class="stickyClass">
                <div class="p-4 shadow rounded-4 border">
                    <!-- PRICE HEADER -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="col">
                            <span class="price-amount text-muted">{{
                                formattedPrice
                            }}</span>
                            <span class="price-period ms-1 text-muted"
                                >day</span
                            >
                        </div>
                        <div class="ms-auto" v-if="showTotal">
                            <span class="price-amount text-muted">
                                {{
                                    bookingValue.total_price
                                        ? new Intl.NumberFormat("th-TH", {
                                              style: "currency",
                                              currency: currency,
                                              minimumFractionDigits: 0,
                                          }).format(bookingValue.total_price)
                                        : ""
                                }}
                            </span>
                            <span class="price-period ms-1 text-muted">
                                for {{ bookingValue.days }} days
                            </span>
                        </div>
                        <div><span v-if="loading">Loading...</span></div>
                    </div>

                    <!-- DATE SELECTION -->
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
                    </div>

                    <!-- USER INFO -->
                    <!-- <div class="border rounded-3 p-3 mb-3 bg-light"> -->
                    <!-- <div class="mb-2">
                            <label class="form-label small fw-bold text-uppercase">Name</label>
                            <InputText
                                v-model="name"
                                placeholder="Your full name"
                                class="w-100"
                            />
                        </div> -->

                    <!-- <div>
                            <label class="form-label small fw-bold text-uppercase">Phone</label>
                            <div class="d-flex align-items-center gap-1">
                                <vue-tel-input v-model="phone" class="w-100" :preferredCountries="['th']" mode="international"></vue-tel-input>
                                
                            </div>
                        </div> -->
                    <!-- </div> -->

                    <!-- SUBMIT BUTTON -->
                    <Button
                        class="w-100 p-3 reserve-btn"
                        label="Book Now"
                        @click="startBook"
                    />
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

        <!-- MOBILE FOOTER -->
        <div class="mobile-booking-footer d-lg-none">
            <div class="footer-content">
                <div class="price-info">
                    <span class="price-amount fw-bold">{{
                        formattedPrice
                    }}</span>
                    <span class="price-period text-muted"> day</span>
                </div>
                <Button
                    class="reserve-btn-mobile"
                    label="Book Now"
                    @click="startBook"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import Calendar from "primevue/calendar";
import Button from "primevue/button";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import { useStore } from "vuex";

import InputText from "primevue/inputtext";

const store = useStore();
const toast = useToast();

// --- Props ---
const props = defineProps({
    pricePerDay: {
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

const router = useRouter();
// --- State ---

const today = new Date();
const dates = ref([
    today, // start date → today
    today,
]);

const wrapperRef = ref(null);
const panelRef = ref(null);
const stickyClass = ref("");
const bookingValue = ref([]);
const loading = ref(false);
const showTotal = ref(false);
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
watch(dates, async (newVal) => {
    if (!newVal || newVal.length < 2) return;

    const [start, end] = newVal;

    // Check if any booked date falls between pickup and return
    const invalid = bookedDates.value.some((d) => d >= start && d <= end);

    if (invalid) {
        toast.add({
            severity: "warn",
            summary: "Invalid Date Range",
            detail: "Your selected range includes a booked date. Please choose another range.",
            life: 5000,
        });
        dates.value = null; // reset calendar selection
    } else {
        // Valid range selected, you can proceed with further logic here
        try {
            loading.value = true;
            const response = await store.dispatch("booking/calculatePrice", {
                bikeId: router.currentRoute.value.params.id,
                startDate: start,
                endDate: end,
            });
            bookingValue.value = response;
            if (response) showTotal.value = true;
        } catch (error) {
            console.error("Error setting loading state:", error);
        } finally {
            loading.value = false;
        }
        if (bookingValue.value.total_price) {
            toast.add({
                severity: "info",
                summary: "Price Calculated",
                detail: `Total Price: ${new Intl.NumberFormat("th-TH", {
                    style: "currency",
                    currency: props.currency,
                    minimumFractionDigits: 0,
                }).format(bookingValue.value.total_price)} for ${
                    bookingValue.value.days
                } days`,
                life: 5000,
            });
        }
    }
});

// --- Computed Properties ---
const formattedPrice = computed(() => {
    return new Intl.NumberFormat("th-TH", {
        style: "currency",
        currency: props.currency,
        minimumFractionDigits: 0,
    }).format(props.pricePerDay);
});

const pickupDateDisplay = computed(() => {
    if (dates.value && dates.value[0]) {
        return dates.value[0].toLocaleDateString("en-CA"); // Format as DD/MM/YYYY
    }
    return "Add date";
});

const returnDateDisplay = computed(() => {
    if (dates.value && dates.value[1]) {
        return dates.value[1].toLocaleDateString("en-CA"); // Format as DD/MM/YYYY
    }
    return "Add date";
});

const startBook = () => {
    if (!dates.value || dates.value.length < 2) {
        toast.add({
            severity: "warn",
            summary: "Select Dates",
            detail: "Please select both pick-up and return dates before proceeding.",
            life: 5000,
        });
        return;
    }

    const start = dates.value[0];
    const end = dates.value[1];
    const formattedStart = start.toLocaleDateString("en-CA"); // YYYY-MM-DD
    const formattedEnd = end.toLocaleDateString("en-CA"); // YYYY-MM-DD

    // const formattedStart = new Date(dates.value[0]).toISOString().split("T")[0];
    // const formattedEnd = new Date(dates.value[1]).toISOString().split("T")[0];
    // Check if user is logged in
    if (localStorage.getItem("token") === null) {
        toast.add({
            severity: "warn",
            summary: "Login Required",
            detail: "Please log in to proceed with the booking.",
            life: 5000,
        });
        localStorage.setItem(
            "pendingBooking",
            JSON.stringify({
                bike_id: router.currentRoute.value.params.id,
                start_date: formattedStart,
                end_date: formattedEnd,
            })
        );
        router.push({ name: "Register" });
    } else {
        console.log('formattedStart, formattedEnd', formattedStart, formattedEnd);
        router.push({
            name: "booking.step",
            query: {
                bike_id: router.currentRoute.value.params.id,
                start_date: formattedStart,
                end_date: formattedEnd,
                days: bookingValue.value.days,
                total_price: bookingValue.value.total_price,
                currency: props.currency,
                price_per_day: props.pricePerDay,
            },
        });
    }
};
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
