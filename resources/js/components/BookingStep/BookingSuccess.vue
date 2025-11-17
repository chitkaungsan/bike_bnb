<template>
  <div class="container-fluid page-container">
    <div class="container vh-100 d-flex justify-content-center align-items-center py-3 bg-body">
      <div class="col-lg-8 col-md-10 invoice-wrapper">
        <!-- Loading -->
        <div v-if="!bookingDetails && !error" class="text-center p-5">
          <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
          <p class="mt-2 text-muted">Loading your booking details...</p>
        </div>

        <!-- Error -->
        <div v-if="error" class="alert alert-danger text-center">
          <h5>Oops! Something went wrong.</h5>
          <p>{{ error }}</p>
        </div>

        <!-- Invoice -->
        <div v-if="bookingDetails" class="invoice-container card shadow-sm border-0">
          <!-- Header -->
          <div class="invoice-header p-3 p-md-4">
            <div class="row align-items-center">
              <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                <img
                  v-if="bookingDetails.store.logo"
                  :src="bookingDetails.store.logo"
                  alt="Store Logo"
                  class="store-logo"
                />
              </div>
              <div class="col-md-3">
                <h5>{{ bookingDetails.store.name }}</h5>
              </div>

              <div class="col-md-5 text-center text-md-end">
                <div
                  class="d-flex justify-content-center justify-content-md-end align-items-center"
                  :class="{
                    'text-success': bookingDetails.status === 'confirmed',
                    'text-warning': bookingDetails.status === 'pending',
                    'text-danger': bookingDetails.status === 'cancelled',
                  }"
                >
                  <!-- Status icons -->
                  <svg
                    v-if="bookingDetails.status === 'confirmed'"
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    fill="currentColor"
                    class="bi bi-check-circle-fill me-2"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
                    />
                  </svg>

                  <svg
                    v-else-if="bookingDetails.status === 'pending'"
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    fill="currentColor"
                    class="bi bi-hourglass-split me-2"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V3a.5.5 0 0 1-.146.354L10 8l3.354 4.646A.5.5 0 0 1 14.5 13v.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-.5a.5.5 0 0 1 .146-.354L6 8 2.646 3.354A.5.5 0 0 1 2 3v-.5z"
                    />
                  </svg>

                  <svg
                    v-else-if="bookingDetails.status === 'cancelled'"
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    fill="currentColor"
                    class="bi bi-x-circle-fill me-2"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-2.146a.5.5 0 0 0-.708 0L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146a.5.5 0 0 0 0-.708z"
                    />
                  </svg>

                  <h3 class="fw-bold mb-0">
                    {{
                      bookingDetails.status === "confirmed"
                        ? "Booking Confirmed"
                        : bookingDetails.status === "pending"
                        ? "Booking Pending"
                        : "Booking Cancelled"
                    }}
                  </h3>
                </div>
                <p class="text-muted small mb-0 mt-1">
                  Booking ID: {{ bookingDetails.id }}
                </p>
                <p class="text-muted small mb-0 mt-1">
                  Invoice Date: {{ formatDateTime(bookingDetails.createdAt) }}
                </p>
              </div>
            </div>

            <hr class="my-3 border-light" />

            <p class="text-center text-muted mb-0">
              Thank you, {{ bookingDetails.renter.name }}! A confirmation has been sent to
              <strong>{{ bookingDetails.renter.email }}</strong>.
            </p>
          </div>

          <!-- Body -->
          <div class="invoice-body p-3 p-md-4">
            <div class="row">
              <div class="col-md-7 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Reservation Details</h5>
                <div class="text-center mb-3">
                  <img
                    :src="bookingDetails.bike.photo"
                    alt="Bike Photo"
                    class="img-fluid rounded bike-photo"
                  />
                </div>
                <h6 class="fw-bold">{{ bookingDetails.bike.title }}</h6>
                <p class="text-muted small">
                  <i class="pi pi-calendar me-2"></i>
                  {{ formatDateRange(bookingDetails.rentalDates) }}
                </p>

                <hr class="my-3 border-light" />

                <div class="row">
                  <div class="col-6">
                    <strong class="small">BILLED TO:</strong>
                    <address class="text-muted mb-0 small contact-info">
                      {{ bookingDetails.renter.name }}<br />
                      {{ bookingDetails.renter.email }}<br />
                      {{ bookingDetails.renter.phone }}
                    </address>
                  </div>
                  <div class="col-6">
                    <strong class="small">PROVIDED BY:</strong>
                    <address class="text-muted mb-0 small contact-info">
                      {{ bookingDetails.store.name }}<br />
                      {{ bookingDetails.store.phone }}<br />
                      {{ bookingDetails.store.address }}
                    </address>
                  </div>
                </div>
              </div>

              <div class="col-md-5">
                <div class="summary-card p-3 rounded">
                  <h5 class="fw-bold mb-3">Payment Summary</h5>
                  <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Billed On:</span>
                    <span>{{ formatDate(bookingDetails.bookingDate) }}</span>
                  </div>
                  <div class="d-flex justify-content-between small mb-3">
                    <span class="text-muted">Payment Method:</span>
                    <span class="text-capitalize">{{ bookingDetails.paymentMethod }}</span>
                  </div>

                  <hr class="my-2 border-light" />

                  <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">
                      {{ bookingDetails.days }} Day{{ bookingDetails.days > 1 ? "s" : "" }} Rental
                    </span>
                    <span>{{ formatPrice(bookingDetails.totalPrice) }}</span>
                  </div>
                  <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Daily Rate</span>
                    <span>{{ formatPrice(bookingDetails.bike.dailyRate) }}</span>
                  </div>

                  <div class="total-section d-flex justify-content-between align-items-center mt-3 pt-3">
                    <span class="fw-bold">Total Amount</span>
                    <span class="fw-bold fs-5 text-success">
                      {{ formatPrice(bookingDetails.totalPrice) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="invoice-footer text-center p-3">
            <div class="mb-2 no-print">
              <Button
                label="Print"
                icon="pi pi-print"
                class="p-button-secondary p-button-sm me-2"
                @click="printInvoice"
              />
              <Button
                label="Home"
                icon="pi pi-home"
                class="p-button-text p-button-sm me-2"
                @click="goToHome"
              />
              <Button
                label="My Bookings"
                icon="pi pi-list"
                class="p-button-sm"
                @click="goToBookings"
              />
            </div>
            <p class="text-muted small mb-0">
              Questions? Contact us at support@samuibikerentals.com
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";
import Button from "primevue/button";
import { create } from "filepond";

const route = useRoute();
const router = useRouter();
const store = useStore();

const bookingDetails = ref(null);
const error = ref(null);

const formatPrice = (val) =>
    new Intl.NumberFormat("en-TH", {
        style: "currency",
        currency: "THB",
        minimumFractionDigits: 0,
    }).format(val || 0);

const formatDate = (dateString) =>
    dateString
        ? new Date(dateString).toLocaleDateString("en-US", {
              month: "long",
              day: "numeric",
              year: "numeric",
          })
        : "";

const formatDateRange = (dates) => {
    if (!dates || dates.length < 2) return "N/A";
    const options = { month: "short", day: "numeric" };
    const startDate = new Date(dates[0]);
    const endDate = new Date(dates[1]);
    return `${startDate.toLocaleDateString(
        "en-US",
        options
    )} - ${endDate.toLocaleDateString(
        "en-US",
        options
    )}, ${startDate.getFullYear()}`;
};

const goToHome = () => router.push({ name: "Home" });
const goToBookings = () => router.push({ name: "my.booking" });
const printInvoice = () => window.print();


const formatDateTime = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);

  const pad = (n) => n.toString().padStart(2, '0');

  const day = pad(date.getDate());
  const month = pad(date.getMonth() + 1); // months are 0-based
  const year = date.getFullYear();

  const hours = pad(date.getHours());
  const minutes = pad(date.getMinutes());

  return `${day}-${month}-${year} ${hours}:${minutes}`;
};

onMounted(async () => {
    const bookingId = route.params.id;
    if (!bookingId) {
        error.value = "No booking ID found.";
        return;
    }

    try {
        // --- Fetch booking from Vuex ---
        const res = await store.dispatch("booking/fetchBookingById", bookingId);
        const data = res.data; // Use your JSON response directly

        bookingDetails.value = {
            id: `SB-${data.booking_id}`,
            bookingDate: data.start_date,
            status: data.status,
            renter: {
                name: data.renter_name || data.user_name,
                email: data.renter_email || data.user_email,
                phone: data.renter_phone || data.user_phone,
            },
            bike: {
                title: data.bike_title,
                model: data.bike_model || "",
                photo: data.bike_photo,
                dailyRate: data.daily_rate,
            },
            rentalDates: [data.start_date, data.end_date],
            days: data.days,
            totalPrice: data.total_price,
            paymentMethod: data.payment_type,
            createdAt: data.created_at,
            store: {
                name: data.store_name,
                phone: data.store_phone,
                address: data.store_address,
                logo: data.store_logo,
            },
        };
    } catch (err) {
        console.error(err);
        error.value =
            "We couldn't retrieve your booking details. Please try again later.";
    }
});
</script>

<style scoped>
.page-container {
  background-color: var(--background-color);
  color: var(--text-color);
  transition: background-color 0.3s, color 0.3s;
}

.invoice-wrapper {
  max-height: 95vh;
  overflow-y: auto;
}
.invoice-container {
  background-color: var(--section-bg-color);
  color: var(--text-color);
  border: 1px solid var(--border-color);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}
.invoice-header {
  border-bottom: 1px solid var(--border-color);
}
.bike-photo {
  max-height: 200px;
  object-fit: cover;
  border: 1px solid var(--border-color);
}
.summary-card {
  background-color: var(--section-bg-color);
  border: 1px solid var(--border-color);
  color: var(--text-color);
}
.total-section {
  border-top: 2px solid var(--border-color);
}
.contact-info {
  color: var(--light-text-color);
}

/* Light and dark transitions */
.bg-body {
  background-color: var(--background-color);
}

/* Scrollbar for dark mode */
.invoice-wrapper::-webkit-scrollbar {
  width: 8px;
}
.invoice-wrapper::-webkit-scrollbar-thumb {
  background-color: var(--border-color);
  border-radius: 4px;
}

/* Print */
@media print {
  body {
    background-color: #fff !important;
  }
  .no-print {
    display: none !important;
  }
}
.store-logo {
    max-width: 100%; /* Ensures it scales down on small screens */
    height: auto; /* Maintains aspect ratio */
    max-height: 80px; /* Max height on larger screens */
    object-fit: contain;
}
</style>
