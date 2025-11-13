<template>
    <div class="noti-wrapper d-flex" role="button" tabindex="0" @click="$emit('open', data)"
        @keydown.enter="$emit('open', data)">
        <!-- Image -->
        <div class="thumb-wrapper me-2">
            <!-- <img :src="data.bike_photo" :alt="data.bike_title || 'bike image'" class="thumb" loading="lazy"
                @error="onImageError" /> -->
                <div class="modal-image-wrapper">
              <Image
                :src="data.bike_photo"
                class="thumb"
                loading="lazy"
                :alt="data.bike_title"
                width="100%"
                height="100%"
                preview
                @error="onImageError"
              />
            </div>
        </div>

        <!-- Text -->
        <div class="text-area">
            <div class="message">
                <strong>{{ data.bike_title }}</strong> is booked for
                {{ formatDate(data.start_date) }} to {{ formatDate(data.end_date) }}.
                Use {{ daysCount }} day<span v-if="daysCount > 1">s</span> and total price {{ totalPrice }} THB.
            </div>
            <div class="d-flex align-items-center mt-2">
                <div class="d-flex gap-2">
                    <font-awesome-icon :icon="faCheck" v-if="data.booking_status === 'pending'" class="text-success" @click="confirmBooking(data.booking_id)"/>
                    <font-awesome-icon :icon="faTimes" v-if="data.booking_status === 'pending'" class="text-danger" @click="cancelBooking(data.booking_id)"/>
                </div>
                <div class="ms-3">
                    <span v-if="data.booking_status === 'cancelled'" class="badge bg-danger">
                        {{ data.booking_status }}
                    </span>
                    <span v-if="data.booking_status === 'confirmed'" class="badge bg-secondary">
                        {{ data.booking_status }}
                    </span>
                </div>

                <!-- RIGHT: Time -->
                <div class="ms-auto time small opacity-75">
                    {{ timeAgo }}
                </div>

            </div>

        </div>
    </div>
</template>
<script setup>
import { computed, ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faTimes, faCheck } from "@fortawesome/free-solid-svg-icons";
import Image from "primevue/image";
import { useToast } from "primevue/usetoast";
import { useStore } from "vuex";
/* -------------------------
   PROPS + EMIT
--------------------------- */
const toast = useToast();
const store = useStore();
const loadingConfirm = ref(false);
const loadingCancel = ref(false);
const props = defineProps({
    data: { type: Object, required: true },
    fallbackImage: { type: String, default: "/images/bike-fallback.png" }
});

defineEmits(["open"]);

/* -------------------------
   IMAGE ERROR HANDLER
--------------------------- */
const imgError = ref(false);

function onImageError(e) {
    imgError.value = true;
    e.target.src = props.fallbackImage;
}

/* -------------------------
   FORMAT DATE
--------------------------- */
function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    return `${String(d.getDate()).padStart(2, "0")}-${String(
        d.getMonth() + 1
    ).padStart(2, "0")}-${d.getFullYear()}`;
}

/* -------------------------
   COMPUTED VALUES
--------------------------- */
const daysCount = computed(() => {
    if (props.data.days) return Number(props.data.days);

    if (props.data.start_date && props.data.end_date) {
        const a = new Date(props.data.start_date);
        const b = new Date(props.data.end_date);
        const diff = Math.round((b - a) / (1000 * 60 * 60 * 24));
        return diff > 0 ? diff : 1;
    }
    return 1;
});

const totalPrice = computed(() => {
    if (props.data.total_price) return props.data.total_price;
    return (Number(props.data.bike_price) * daysCount.value).toFixed(2);
});

const timeAgo = computed(() => {
    const dt = new Date(props.data.created_at);
    const diffMs = Date.now() - dt.getTime();
    const mins = Math.floor(diffMs / 60000);
    if (mins < 1) return "Just now";
    if (mins < 60) return `${mins}m`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `${hours}h`;
    return `${Math.floor(hours / 24)}d`;
});

const confirmBooking = async (booking_id) => {
    addLoading(true);
  try {
    const bookingId = booking_id
    await store.dispatch('booking/confirmBooking', bookingId)
  } catch (error) {
    console.error('Error confirming booking:', error)
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to confirm booking.', life: 3000 });
  } finally {
    toast.removeAllGroups();
    setTimeout(() => {
      toast.add({ severity: 'success', summary: 'Success', detail: 'Booking confirmed successfully.', life: 3000 });
    }, 500);
  }
  // In real app, you'd call an API to confirm the booking   
}

const addLoading= (loading) => {
    if(loading===true){
       toast.add({ severity: 'info', summary: 'Processing',detail: 'Loading......Please wait a few second.', closable: false });
    }
}
const cancelBooking = async (booking_id) => {
  try {
    addLoading(true);
    const bookingId = booking_id
    await store.dispatch('booking/cancelBooking', bookingId)
  } catch (error) {
    console.error('Error cancelling booking:', error)
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to cancel booking.', life: 3000 });
  } finally {
    toast.removeAllGroups();
     setTimeout(() => {
    toast.add({ severity: 'success', summary: 'Success', detail: 'Booking cancelled successfully.', life: 3000 });
    }, 500);
  }
  // In real app, you'd call an API to cancel the booking  
}

/* -------------------------
   ICON EXPOSE
--------------------------- */
const rejectIcon = faTimes;
const confirmIcon = faCheck;
</script>

<style scoped>
/* WRAPPER */
.noti-wrapper {
    border-radius: var(--border-radius-md);
    transition: background 0.15s ease;
    cursor: pointer;
}

.noti-wrapper:hover {
    background: rgba(255, 255, 255, 0.04);
}

/* IMAGE */
.thumb-wrapper {
    width: 70px;
    height: 52px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    flex-shrink: 0;
}

.thumb {
    width: 100%;
    height: 100%;
    object-fit: fit;
}

/* TEXT */
.text-area {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.message {
    font-size: 0.85rem;
    color: var(--text-color);
    line-height: 1.25rem;
    word-break: break-word;
}

.time {
    margin-top: 4px;
    font-size: 0.75rem;
    opacity: 0.7;
}

@media (min-width: 430px) and (max-width: 576px) {

    /* --- FIX: Increase image wrapper size on mobile --- */
    .thumb-wrapper {
        width: 80px;
        /* Increased from 70px */
        height: 67px;
        /* Increased from 52px (to maintain aspect ratio) */
    }


    .thumb {
        width: 100%;
        height: 100%;
        object-fit: fit;
        /* Removed 'padding: auto' as it's invalid CSS */
    }

    .noti-wrapper {
        width: 400px;
    }

    .text-area {
        display: flex;
        flex-direction: column;
        /* remove vertical center to allow natural wrapping */
    }

    .message {

        font-size: 0.65rem;
        color: var(--text-color);
        white-space: normal;
        /* 'wrap' is invalid; 'normal' allows wrapping */
    }
}

@media (min-width: 575px) and (max-width: 760px) {

    /* --- FIX: Increase image wrapper size on mobile --- */
    .thumb-wrapper {
        width: 80px;
        /* Increased from 70px */
        height: 67px;
        /* Increased from 52px (to maintain aspect ratio) */
    }


    .thumb {
        width: 100%;
        height: 100%;
        object-fit: fit;
        /* Removed 'padding: auto' as it's invalid CSS */
    }

    .noti-wrapper {
        width: 400px;
    }

    .text-area {
        display: flex;
        flex-direction: column;
        /* remove vertical center to allow natural wrapping */
    }

    .message {

        font-size: 0.65rem;
        color: var(--text-color);
        white-space: normal;
        /* 'wrap' is invalid; 'normal' allows wrapping */
    }
}

@media (min-width: 310px) and (max-width: 429px) {

    /* --- FIX: Increase image wrapper size on mobile --- */
    .thumb-wrapper {
        width: 100px;
        /* Increased from 70px */
        height: 67px;
        /* Increased from 52px (to maintain aspect ratio) */
    }


    .thumb {
        width: 100%;
        height: 100%;
        object-fit: fit;
        /* Removed 'padding: auto' as it's invalid CSS */
    }

    .noti-wrapper {
        width: 300px;
    }

    .text-area {
        display: flex;
        flex-direction: column;
        /* remove vertical center to allow natural wrapping */
    }

    .message {

        font-size: 0.65rem;
        color: var(--text-color);
        white-space: normal;
        /* 'wrap' is invalid; 'normal' allows wrapping */
    }
}

.times-icon:hover {
    color: var(--primary-hover-color);
}

/* Dark mode only */
:root[data-bs-theme='dark'],
body.dark {}
</style>

<!-- Fix ui for mobile screen image is too small. make some medium size  -->