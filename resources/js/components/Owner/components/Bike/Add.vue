<template>
  <div class="container my-5">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Add New Vehicle Listing</h5>
      </div>
      <div class="card-body p-4">
        <div class="mb-4">
          <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation" v-for="step in steps" :key="step.id">
              <button
                class="nav-link"
                :class="{
                  active: currentStep === step.id,
                  'step-done': currentStep > step.id,
                }"
                type="button"
                @click="goToStep(step.id)"
              >
                <strong>Step {{ step.id }}:</strong> {{ step.name }}
              </button>
            </li>
          </ul>
        </div>

        <form @submit.prevent="handleSubmit" novalidate>
          <div v-show="currentStep === 1">
            <h6 class="mb-3 text-primary">Basic Information</h6>
            <hr class="mt-0 mb-4" />
            <div class="row">
              <div class="col-md-8 mb-3">
                <label class="form-label">
                  <strong>Listing Title</strong>
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="formData.title"
                  placeholder="e.g., Honda Click 150i - Excellent Condition"
                  :class="{ 'is-invalid': errors.title }"
                />
                <small class="form-text text-muted"
                  >A clear, descriptive title helps attract buyers.</small
                >
                <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">
                  <strong>Price</strong>
                  <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text">฿</span>
                  <input
                    type="number"
                    step="100"
                    class="form-control"
                    v-model.number="formData.price"
                    placeholder="e.g., 45000"
                    :class="{ 'is-invalid': errors.price }"
                  />
                  <div v-if="errors.price" class="invalid-feedback">
                    {{ errors.price }}
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label"><strong>Description</strong> (Optional)</label>
              <div
                id="editor"
                ref="editorRef"
                class="border rounded"
                style="min-height: 150px"
              ></div>
            </div>
          </div>

          <div v-show="currentStep === 2">
            <h6 class="mb-3 text-primary">Vehicle Specifications</h6>
            <hr class="mt-0 mb-4" />
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <strong>Brand</strong>
                  <span class="text-danger">*</span>
                </label>
                <multiselect
                  v-model="selectedBrand"
                  :options="brands"
                  :multiple="false"
                  :searchable="true"
                  :close-on-select="true"
                  label="name"
                  track-by="id"
                  placeholder="Select a brand"
                  :class="{ 'is-invalid': errors.brand_id }"
                >
                  <template #option="{ option }">
                    <div class="option-item">
                      <img :src="option.logo" alt="" class="option-logo" />
                      <span>{{ option.name }}</span>
                    </div>
                  </template>
                  <template #singleLabel="{ option }">
                    <div class="selected-item">
                      <img :src="option.logo" alt="" class="option-logo" />
                      <span>{{ option.name }}</span>
                    </div>
                  </template>
                </multiselect>
                <div v-if="errors.brand_id" class="invalid-feedback d-block">
                  {{ errors.brand_id }}
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label"><strong>Model</strong> (Optional)</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="formData.model"
                  placeholder="e.g., Click 150i"
                />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <strong>Category</strong>
                  <span class="text-danger">*</span>
                </label>
                <multiselect
                  v-model="selectedCategory"
                  :options="categories"
                  :close-on-select="true"
                  :clear-on-select="false"
                  label="name"
                  track-by="id"
                  placeholder="Select a category"
                  :class="{ 'is-invalid': errors.cat_id }"
                />
                <div v-if="errors.cat_id" class="invalid-feedback">
                  {{ errors.cat_id }}
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label"
                  ><strong>Year of Manufacture</strong> (Optional)</label
                >
                <input
                  type="number"
                  class="form-control"
                  v-model.number="formData.year"
                  placeholder="e.g., 2022"
                  min="1980"
                  :max="new Date().getFullYear()"
                />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label d-block mb-3">
                  <strong>Transmission Type</strong>
                  <span class="text-danger">*</span>
                </label>
                <div class="d-flex flex-wrap gap-3">
                  <div
                    v-for="option in transmissionOptions"
                    :key="option.value"
                    class="select-box"
                    :class="{ active: formData.type === option.value }"
                    @click="formData.type = option.value"
                  >
                    <div class="icon fs-5 mb-1">{{ option.icon }}</div>
                    <div class="label">{{ option.label }}</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label"
                  ><strong>Registration No.</strong> (Optional)</label
                >
                <input
                  type="text"
                  class="form-control"
                  v-model="formData.bike_no"
                  placeholder="e.g., 1กข 1234"
                />
              </div>
            </div>
          </div>

          <div v-show="currentStep === 3">
            <h6 class="mb-3 text-primary">Media & Documents</h6>
            <hr class="mt-0 mb-4" />
            <div class="row align-items-start">
              <div class="col-md-5 mb-4">
                <label class="form-label">
                  <strong>Main Cover Photo</strong>
                  <span class="text-danger">*</span>
                </label>
                <small class="form-text text-muted d-block mb-2"
                  >This is the primary image for your listing.</small
                >
                <div
                  class="image-upload border rounded d-flex align-items-center justify-content-center"
                  :class="{ 'border-danger': errors.photo }"
                  @click="handleImageClick('photo')"
                >
                  <img
                    :src="
                      photoPreview ||
                      'https://placehold.co/400x300/EBF4FF/3A86FF?text=Click+to+Upload'
                    "
                    class="img-fluid preview-image"
                    alt="Vehicle Photo Preview"
                  />
                  <input
                    type="file"
                    id="photo"
                    class="d-none"
                    @change="handleFileChange('photo', $event)"
                    accept="image/png, image/jpeg"
                  />
                </div>
                <div v-if="errors.photo" class="text-danger small mt-1">
                  {{ errors.photo }}
                </div>
              </div>
              <div class="col-md-7 mb-4">
                <label class="form-label"
                  ><strong>Additional Photos</strong> (Optional)</label
                >
                <small class="form-text text-muted d-block mb-2"
                  >Upload up to 5 more photos for a gallery.</small
                >
                <file-pond
                  ref="pond"
                  name="bike_images"
                  label-idle='Drag & Drop or <span class="filepond--label-action">Browse</span>'
                  allow-multiple="true"
                  accepted-file-types="image/jpeg, image/png"
                  :max-files="5"
                  :files="files"
                  @updatefiles="handleUpdateFiles"
                />
              </div>
            </div>
            <hr class="my-4" />
            <div class="row">
              <div class="col-12">
                <label class="form-label"
                  ><strong>License/Green Book Photo</strong> (Optional)</label
                >
                <small class="form-text text-muted d-block mb-2"
                  >Upload a clear photo of the vehicle's registration document.</small
                >
                <div
                  class="image-upload-small border rounded d-flex align-items-center justify-content-center"
                  @click="handleImageClick('license_image')"
                >
                  <img
                    :src="
                      licensePreview ||
                      'https://placehold.co/400x250/EBF4FF/3A86FF?text=Click+to+Upload'
                    "
                    class="img-fluid preview-image"
                    alt="License Preview"
                  />
                  <input
                    type="file"
                    id="license_image"
                    class="d-none"
                    @change="handleFileChange('license_image', $event)"
                    accept="image/png, image/jpeg"
                  />
                </div>
              </div>
            </div>
          </div>

          <div v-show="currentStep === 4">
            <h6 class="mb-3 text-primary">Listing Status</h6>
            <hr class="mt-0 mb-4" />
            <div class="p-3 bg-light rounded border mb-4">
              <div class="form-check form-switch fs-5">
                <input
                  class="form-check-input"
                  type="checkbox"
                  role="switch"
                  v-model="formData.status"
                  true-value="active"
                  false-value="inactive"
                  id="listingStatusSwitch"
                />
                <label class="form-check-label" for="listingStatusSwitch">
                  <strong>Make Listing Active</strong>
                  <small class="d-block text-muted"
                    >Inactive listings are saved as drafts and won't be visible to the
                    public.</small
                  >
                </label>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">
                <strong>Select Store</strong>
                <span class="text-danger">*</span>
              </label>
              <multiselect
                v-model="selectedStore"
                :options="stores"
                :close-on-select="true"
                :clear-on-select="false"
                label="name"
                track-by="id"
                placeholder="Select a store"
                :class="{ 'is-invalid': errors.store_id }"
              />
              <div v-if="errors.store_id" class="invalid-feedback d-block">
                {{ errors.store_id }}
              </div>
            </div>
            <div class="alert alert-info" role="alert">
              You're all set! Review your information in the previous steps, then click
              "Submit Listing" to publish.
            </div>
          </div>

          <hr />
          <div class="d-flex justify-content-between mt-4">
            <button
              type="button"
              class="btn btn-secondary"
              @click="prevStep"
              :disabled="currentStep === 1"
            >
              &larr; Previous
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="nextStep"
              v-if="currentStep < steps.length"
            >
              Next &rarr;
            </button>
            <button
              type="submit"
              class="btn btn-success btn-lg"
              v-if="currentStep === steps.length"
              :disabled="isLoading"
            >
              <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
              {{ isLoading ? "Submitting..." : "Submit Listing" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>
import { reactive, ref, onMounted, computed, watch } from "vue";
import Quill from "quill";
import { useStore } from "vuex";
import vueFilePond from "vue-filepond";
import "quill/dist/quill.snow.css";
import axios from "../../../../service/axios"; // Adjust path if needed
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

const FilePond = vueFilePond(FilePondPluginImagePreview, FilePondPluginFileValidateType);
import { useToast } from "primevue/usetoast";

const toast = useToast();

// --- STATE MANAGEMENT ---
const store = useStore();
const isLoading = ref(false);
const currentStep = ref(1);
const steps = [
  { id: 1, name: "Basics" },
  { id: 2, name: "Specifications" },
  { id: 3, name: "Media" },
  { id: 4, name: "Finalize" },
];

// --- FORM DATA ---
const formData = reactive({
  title: "",
  price: null,
  brand_id: "",
  model: "",
  cat_id: "",
  year: null,
  type: "auto",
  bike_no: "",
  status: "active",
  description: "",
  user_id: "",
  store_id: "",
});
const editorRef = ref(null);

// --- IMAGE UPLOADS ---
const photo = ref(null);
const photoPreview = ref(null);
const files = ref([]);
const license_image = ref(null);
const licensePreview = ref(null);

// --- OPTIONS DATA ---
const selectedBrand = ref(null);
const brands = ref([]);

const selectedCategory = ref(null);
const categories = ref([]);

const selectedStore = ref(null);
const stores = ref([]);

const transmissionOptions = [
  { value: "auto", label: "Automatic", icon: "⚙️" },
  { value: "manual", label: "Manual", icon: "🕹️" },
  { value: "ev", label: "Electric", icon: "⚡" },
];
function validateForm() {
  if (!selectedStore.value) {
    errors.value.store_id = "Please select a store.";
    return false;
  } else {
    errors.value.store_id = null;
    return true;
  }
}

// --- ERRORS & USER ---
const errors = reactive({});
const user = computed(() => store.getters["auth/user"]);

// --- WATCH USER AND SET user_id ---

// --- STEP NAVIGATION ---
const goToStep = (step) => {
  if (step < currentStep.value) {
    currentStep.value = step;
  } else if (step > currentStep.value && step - currentStep.value === 1) {
    if (validateStep()) currentStep.value = step;
  }
};

const nextStep = () => {
  if (validateStep() && currentStep.value < steps.length) currentStep.value++;
};
const prevStep = () => {
  if (currentStep.value > 1) currentStep.value--;
};

// --- VALIDATION ---
const validateStep = () => {
  // Clear previous errors
  Object.keys(errors).forEach((k) => delete errors[k]);

  let isValid = true;

  switch (currentStep.value) {
    case 1: // Basics
      if (!formData.title) {
        errors.title = "Title is required";
        isValid = false;
      }
      if (!formData.price || formData.price <= 0) {
        errors.price = "Price must be a positive number";
        isValid = false;
      }
      break;

    case 2: // Specifications
      if (!selectedBrand.value) {
        errors.brand_id = "Please select a brand";
        isValid = false;
      }
      if (!formData.cat_id) {
        errors.cat_id = "Please select a category";
        isValid = false;
      }
      break;

    case 3: // Media
      if (!photo.value) {
        errors.photo = "A main cover photo is required.";
        isValid = false;
      }
      break;

    case 4: // Finalize
      formData.store_id = selectedStore.value?.id || null;
      if (!formData.store_id) {
        errors.store_id = "Please select a store";
        isValid = false;
      } else {
        errors.store_id = null;
      }
      break;
  }

  return isValid;
};

// --- EVENT HANDLERS ---
const handleUpdateFiles = (fileItems) => {
  files.value = fileItems.map((fileItem) => fileItem.file);
};
const handleImageClick = (field) => document.getElementById(field).click();
const handleFileChange = (field, e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (field === "photo") {
    photo.value = file;
    photoPreview.value = URL.createObjectURL(file);
    if (errors.photo) delete errors.photo;
  }
  if (field === "license_image") {
    license_image.value = file;
    licensePreview.value = URL.createObjectURL(file);
  }
};

// --- WATCH SELECTED BRAND ---
watch(selectedBrand, (val) => {
  formData.brand_id = val ? val.id : "";
  if (val && errors.brand_id) delete errors.brand_id;
});
watch(selectedCategory, (val) => {
  formData.cat_id = val ? val.id : "";
  if (val && errors.cat_id) delete errors.cat_id;
});
watch(user, (val) => {
  if (val && val.id) {
    formData.user_id = val.id;
  }
});
watch(selectedStore, (val) => {
  formData.store_id = val ? val.id : "";
  if (val && errors.store_id) delete errors.store_id;
});
// --- FORM SUBMISSION ---
const handleSubmit = async () => {
  // Validate all steps
  let allValid = true;
  for (let i = 1; i < steps.length; i++) {
    currentStep.value = i;
    if (!validateStep()) {
      allValid = false;
      break;
    }
  }
  if (!allValid) return;

  // Ensure user_id is set
  if (!formData.user_id) {
    alert("User is not loaded yet. Please wait a moment.");
    return;
  }

  currentStep.value = steps.length;
  isLoading.value = true;

  const submissionData = new FormData();
  for (let key in formData) submissionData.append(key, formData[key] ?? "");

  // Append images
  if (photo.value) submissionData.append("photo", photo.value);
  if (license_image.value) submissionData.append("license_image", license_image.value);
  if (files.value.length > 0) {
    files.value.forEach((file) => submissionData.append("files[]", file));
  }

  try {
    await store.dispatch("bikes/addBike", submissionData);

    // ✅ Only show success if no error occurs
    toast.add({
      severity: "success",
      summary: "Success",
      detail: `Bike added successfully.`,
      life: 1500,
    });
  } catch (err) {
    console.error("Failed to add bike:", err.response?.data || err);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: `${err.response?.data?.message || "Failed to add bike"}`,
      life: 1500,
    });
  } finally {
    isLoading.value = false;
  }
};

// --- LIFECYCLE HOOKS ---
onMounted(async () => {
  if (editorRef.value) {
    const quill = new Quill(editorRef.value, {
      theme: "snow",
      placeholder: "Describe the vehicle's condition, history, features, etc.",
      modules: { toolbar: [["bold", "italic"], [{ list: "bullet" }], ["link"]] },
    });
    quill.on("text-change", () => (formData.description = quill.root.innerHTML));
  }

  try {
    const { data } = await axios.get("/bike/brands");
    brands.value = data;
  } catch (error) {
    console.error("Error loading brands:", error);
  }

  try {
    const { data } = await axios.get("/bike/categories");
    categories.value = data;
    console.log(categories.value);
  } catch (error) {
    console.error("Error loading categories:", error);
  }

  // Fetch user first
  await store.dispatch("auth/fetchUser");

  setTimeout(async () => {
    console.log("user", store.state.auth.user.id);
    try {
      const { data } = await axios.get(`/get/stores?user_id=${store.state.auth.user.id}`);
      stores.value = data;
      console.log(stores.value);
    } catch (error) {
      console.error("Error loading stores:", error);
    }
  }, 1000);
});
</script>
<style scoped>
/* Stepper Styles */
.nav-pills .nav-link {
  background-color: #f8f9fa;
  color: #6c757d;
  border: 1px solid #dee2e6;
  margin: 0 5px;
}
.nav-pills .nav-link.active {
  background-color: var(--bs-primary);
  color: white;
  border-color: var(--bs-primary);
}
.nav-pills .nav-link.step-done {
  background-color: #e9f2ff;
  color: var(--bs-primary);
  border-color: #b6d4fe;
}
/* Image Upload Styles */
.image-upload {
  width: 100%; /* full width of parent */
  max-width: 400px; /* optional max width */
  aspect-ratio: 4 / 3; /* maintain 4:3 ratio */
  cursor: pointer;
  overflow: hidden;
  background-color: #f8f9fa;
  border: 1px dashed #ced4da;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.image-upload-small {
  width: 100%;
  max-width: 250px; /* optional max width */
  aspect-ratio: 4 / 3; /* maintain 4:3 ratio */
  cursor: pointer;
  overflow: hidden;
  background-color: #f8f9fa;
  border: 1px dashed #ced4da;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.preview-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
/* Form Styles */
.form-label strong {
  font-weight: 500;
}
h6 {
  font-weight: 600;
}
.is-invalid .multiselect__tags {
  border-color: #dc3545;
}

/* Custom Select Box (Transmission) */
.select-box {
  border: 1.5px solid #ddd;
  border-radius: 8px;
  padding: 12px 16px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
  min-width: 100px;
  background-color: #fff;
  font-size: 0.9rem;
}
.select-box:hover {
  border-color: #0d6efd;
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.15);
}
.select-box.active {
  border-color: #0d6efd;
  background-color: #e9f2ff;
  color: #0d6efd;
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}
.select-box .label {
  font-weight: 500;
}

/* Multiselect Brand Logo */
.option-item,
.selected-item {
  display: flex;
  align-items: center;
}
.option-logo {
  width: 20px;
  height: 20px;
  border-radius: 4px;
  object-fit: contain;
  margin-right: 8px;
}
@media (max-width: 768px) {
  .image-upload,
  .image-upload-small {
    max-width: 100%;
  }
}
</style>
<style>
/* Global style overrides */
.multiselect__tags {
  min-height: 38px;
  padding: 8px 40px 0 8px;
}
.multiselect__select {
  height: 36px;
}
.multiselect__placeholder,
.multiselect__input {
  padding-top: 0;
  margin-bottom: 0;
}
/* Make Filepond a bit smaller to fit the layout */
/* Compact horizontal layout */
.filepond--root {
  display: flex;
  flex-wrap: nowrap;
  padding: 5px;
}

.filepond--item {
  width: 100px; /* slightly larger preview */
  height: auto; /* slightly larger preview */
  margin-right: 6px;
  border-radius: 6px;
}

.filepond--image-preview {
  max-width: 100%;
  max-height: 100%;
}

.filepond--label-action {
  font-size: 0.9rem; /* slightly bigger text */
}

.filepond--panel-root {
  padding: 5px;
}
.filepond--panel filepond--panel-root {
  height: 200px;
}
</style>
