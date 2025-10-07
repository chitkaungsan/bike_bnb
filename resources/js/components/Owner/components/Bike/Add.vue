<template>
  <div class="container my-5">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Add New Vehicle Listing</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="handleSubmit" novalidate>
          <!-- Basic Information -->
          <h6 class="mb-3">Basic Information</h6>
          <hr class="mt-0 mb-4" />
          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label"><strong>Listing Title</strong></label>
              <input
                type="text"
                class="form-control"
                v-model="formData.title"
                placeholder="e.g., Honda Click 150i - Excellent Condition"
                :class="{ 'is-invalid': errors.title }"
              />
              <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label"><strong>Price</strong></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input
                  type="number"
                  step="0.01"
                  class="form-control"
                  v-model.number="formData.price"
                  placeholder="e.g., 1500.00"
                  :class="{ 'is-invalid': errors.price }"
                />
                <div v-if="errors.price" class="invalid-feedback">{{ errors.price }}</div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="mb-4">
            <label class="form-label"><strong>Description</strong> (Optional)</label>
            <div
              id="editor"
              ref="editorRef"
              class="border rounded"
              style="min-height: 50px"
            ></div>
          </div>

          <!-- Vehicle Specifications -->
          <h6 class="mb-3">Vehicle Specifications</h6>
          <hr class="mt-0 mb-4" />
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label"><strong>Brand</strong></label>
              <select
                class="form-select"
                v-model="formData.brand_id"
                :class="{ 'is-invalid': errors.brand_id }"
              >
                <option disabled value="">-- Please select a brand --</option>
                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                  {{ brand.name }}
                </option>
              </select>
              <div v-if="errors.brand_id" class="invalid-feedback">
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
              <label class="form-label"><strong>Category</strong></label>
              <select
                class="form-select"
                v-model="formData.cat_id"
                :class="{ 'is-invalid': errors.cat_id }"
              >
                <option disabled value="">-- Please select a category --</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.name }}
                </option>
              </select>
              <div v-if="errors.cat_id" class="invalid-feedback">{{ errors.cat_id }}</div>
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
              />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label d-block"><strong>Transmission Type</strong></label>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  value="auto"
                  v-model="formData.type"
                />
                <label class="form-check-label">Automatic</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  value="manual"
                  v-model="formData.type"
                />
                <label class="form-check-label">Manual</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  value="ev"
                  v-model="formData.type"
                />
                <label class="form-check-label">Electric (EV)</label>
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

          <!-- Media Upload -->
          <h6 class="mb-3">Media & Documents</h6>
          <hr class="mt-0 mb-4" />
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label"><strong>Vehicle Photo</strong></label>
                  <div
                    class="image-upload border rounded d-flex align-items-center justify-content-center"
                    @click="handleImageClick('photo')"
                  >
                    <img
                      :src="
                        photoPreview ||
                        'https://placehold.co/400x400/63C1A2/FFFFFF?text=Click+to+Upload'
                      "
                      class="img-fluid preview-image"
                      alt="Vehicle Photo Preview"
                    />
                    <input
                      type="file"
                      id="photo"
                      class="d-none"
                      @change="handleFileChange('photo', $event)"
                    />
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label"
                    ><strong>License/Green Book Photo</strong></label
                  >
                  <div
                    class="image-upload border rounded d-flex align-items-center justify-content-center"
                    @click="handleImageClick('license_image')"
                  >
                    <img
                      :src="
                        licensePreview ||
                        'https://placehold.co/400x400/63C1A2/FFFFFF?text=Click+to+Upload'
                      "
                      class="img-fluid preview-image"
                      alt="License Preview"
                    />
                    <input
                      type="file"
                      id="license_image"
                      class="d-none"
                      @change="handleFileChange('license_image', $event)"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <file-pond
                ref="pond"
                name="bike_images"
                label-idle='More bike photo <span class="filepond--label-action">Browse</span>'
                allow-multiple="true"
                :max-files="6"
                :server="serverOptions"
                :files="files"
                @updatefiles="handleUpdateFiles"
              />
            </div>
          </div>

          <!-- Listing Status -->
          <h6 class="mb-3">Listing Status</h6>
          <hr class="mt-0 mb-4" />
          <div class="mb-4">
            <div class="form-check form-switch">
              <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                v-model="formData.status"
                true-value="active"
                false-value="inactive"
              />
              <label class="form-check-label">
                <strong>Make Listing Active</strong>
                <small class="d-block text-muted"
                  >Inactive listings will be saved as drafts.</small
                >
              </label>
            </div>
          </div>

          <div class="d-grid gap-2 col-2">
            <button type="submit" class="btn btn-primary btn-lg" :disabled="isLoading">
              <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
              {{ isLoading ? "Submitting..." : "Submit Listing" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from "vue";
import Quill from "quill";
import { useStore } from "vuex";
import vueFilePond from "vue-filepond";
import "quill/dist/quill.snow.css";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";

const FilePond = vueFilePond(FilePondPluginImagePreview, FilePondPluginFileValidateType);
// --- FORM DATA ---
const formData = reactive({
  title: "",
  price: "",
  brand_id: "",
  model: "",
  cat_id: "",
  year: "",
  type: "auto",
  bike_no: "",
  status: "active",
  description: "",
});
const files = ref([]);
const store = useStore();
// --- ERRORS ---
const errors = reactive({});
const loading = computed(() => store.getters["bikes/loading"]);
const user = computed(() => store.getters["auth/user"]);
const user_id = null;
// --- IMAGE UPLOADS ---
const photo = ref(null);
const license_image = ref(null);
const photoPreview = ref(null);
const licensePreview = ref(null);
const editorRef = ref(null);

// --- DUMMY DATA ---
const brands = ref([
  { id: 1, name: "Honda" },
  { id: 2, name: "Yamaha" },
  { id: 3, name: "Kawasaki" },
  { id: 4, name: "Vespa" },
]);
const categories = ref([
  { id: 1, name: "Scooter" },
  { id: 2, name: "Motorbike" },
  { id: 3, name: "Big Bike" },
]);

// --- METHODS ---
const validateForm = () => {
  Object.keys(errors).forEach((k) => delete errors[k]);
  if (!formData.title) errors.title = "Title is required";
  if (!formData.price || formData.price <= 0) errors.price = "Price must be > 0";
  if (!formData.brand_id) errors.brand_id = "Please select a brand";
  if (!formData.cat_id) errors.cat_id = "Please select a category";
  return Object.keys(errors).length === 0;
};
const handleUpdateFiles = (fileItems) => {
  files.value = fileItems.map((fileItem) => fileItem.file);
  console.log("Selected files:", files.value);
};

const handleImageClick = (field) => document.getElementById(field).click();
const handleFileChange = (field, e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (field === "photo") {
    photo.value = file;
    photoPreview.value = URL.createObjectURL(file);
  }
  if (field === "license_image") {
    license_image.value = file;
    licensePreview.value = URL.createObjectURL(file);
  }
};

const isLoading = ref(false);
const handleSubmit = async () => {
  if (!validateForm()) return;
  isLoading.value = true;

  const submissionData = new FormData();
  // Append form fields
  for (let key in formData) submissionData.append(key, formData[key]);

  // Append single photo
  if (photo.value) submissionData.append("photo", photo.value);

  if (user.value) submissionData.append("user_id", user.value.id);
  // Append license image
  if (license_image.value) submissionData.append("license_image", license_image.value);

  // Append multiple files (from FilePond or other multiple upload)
  if (files.value.length > 0) {
    files.value.forEach((file, index) => {
      submissionData.append("files[]", file); // use "files[]" as backend expects array
    });
  }

  try {
    // Dispatch Vuex action with all files
    await store.dispatch("bikes/addBike", submissionData);
    console.log("✅ Bike added successfully!");
  } catch (err) {
    console.error("Failed to add bike:", err);
    alert("❌ Failed to add bike!");
  } finally {
    isLoading.value = false;
  }
};
onMounted(() => {
  if (!editorRef.value) return;
  const quill = new Quill(editorRef.value, {
    theme: "snow",
    placeholder: "Enter bike description...",
    modules: { toolbar: [["bold", "italic"], [{ list: "bullet" }], ["link", "image"]] },
  });

  quill.on("text-change", () => {
    formData.description = quill.root.innerHTML;
  });
  store.dispatch("auth/fetchUser");
});
</script>

<style scoped>
.image-upload {
  width: 100%;
  height: 200px;
  cursor: pointer;
  overflow: hidden;
}
.preview-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.card-header h3 {
  font-weight: 300;
}
.form-label strong {
  font-weight: 500;
}
h6 {
  color: var(--primary-color);
}
</style>

<style>
/* Compact horizontal layout */
.filepond--root {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  padding: 5px;
}

.filepond--item {
  width: 100px; /* slightly larger preview */
  height: 100px; /* slightly larger preview */
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
</style>
