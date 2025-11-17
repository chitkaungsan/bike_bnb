<template>
  <div class="container my-4">
    <div class="card mx-auto shadow-sm p-3">
      <form @submit.prevent="submitForm" class="row g-3">
        <!-- Store Name & Phone -->
        <div class="col-12">
          <div class="row">
            <div class="col-12 col-md-6">
              <label class="form-label">Store Name</label>
              <input
                type="text"
                v-model="form.name"
                class="form-control"
                placeholder="Enter store name"
                required
              />
              <small v-if="errors.name" class="text-danger">{{ errors.name }}</small>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Store Phone</label>
              <input
                type="text"
                v-model="form.phone"
                class="form-control"
                placeholder="Enter Phone Number"
                required
              />
              <small v-if="errors.name" class="text-danger">{{ errors.phone }}</small>
            </div>
          </div>
        </div>

        <!-- Address -->
        <div class="col-12 col-md-6">
          <label class="form-label">Address</label>
          <input
            type="text"
            v-model="form.address"
            class="form-control"
            placeholder="Enter address"
            required
          />
          <small v-if="errors.name" class="text-danger">{{ errors.address }}</small>
        </div>

        <!-- Description -->
        <div class="col-12">
          <label class="form-label">Description</label>
          <quill-editor
            :content="form.description"
            @update:content="(val) => (form.description = val)"
            :options="editorOptions"
            style="height: 120px"
          />
          <small v-if="errors.name" class="text-danger">{{ errors.description }}</small>
        </div>

        <!-- Location -->
        <div class="col-12 col-md-6">
          <label class="form-label">Location</label>
          <div id="map" style="height: 200px; border-radius: 0.5rem" class="mb-2"></div>
          <input
            type="text"
            v-model="form.location"
            class="form-control"
            readonly
            placeholder="Click on map to select location"
          />
          <small class="text-danger">{{ errors.location }}</small>
        </div>

        <!-- Logo -->
        <div class="col-6 col-md-3 text-center">
          <label class="form-label">Logo</label>
          <input
            type="file"
            ref="logoInput"
            @change="handleLogoUpload"
            accept="image/*"
            class="d-none"
          />
          <div class="logo-preview mx-auto" @click="triggerLogoInput">
            <img v-if="previewLogo" :src="previewLogo" />
            <span v-else class="placeholder-text">Click to upload</span>
          </div>
        </div>

        <!-- Cover Photo -->
        <div class="col-6 col-md-3 text-center">
          <label class="form-label">Cover</label>
          <input
            type="file"
            ref="coverInput"
            @change="handleCoverUpload"
            accept="image/*"
            class="d-none"
          />
          <div class="cover-preview" @click="triggerCoverInput">
            <img v-if="previewCover" :src="previewCover" />
            <span v-else class="placeholder-text">Click to upload</span>
          </div>
        </div>

        <!-- Extra Images -->
        <div class="col-12">
          <label class="form-label">Store Images</label>
          <div class="row g-2">
            <div class="col-3 text-center" v-for="img in extraImages" :key="img.key">
              <input
                type="file"
                :ref="(el) => (extraRefs[img.key] = el)"
                @change="(e) => handleExtraUpload(e, img.key)"
                accept="image/*"
                class="d-none"
              />
              <div class="extra-preview" @click="triggerExtraInput(img.key)">
                <img v-if="img.preview" :src="img.preview" />
                <span v-else class="placeholder-text">{{ img.label }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <div class="col-12 text-end mt-3">
          <button type="submit" class="btn btn-primary me-2" :disabled="loading">
            <span v-if="loading">
              <i class="fas fa-spinner fa-spin me-2"></i> Saving...
            </span>
            <span v-else>Save Store</span>
          </button>

          <router-link
            :to="{ name: 'owner.stores' }"
            class="btn btn-secondary"
            :class="{ disabled: loading }"
          >
            Cancel
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref, reactive, onMounted, computed, nextTick } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import "quill/dist/quill.snow.css";
import L from "leaflet";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import Quill from "quill";
import { useToast } from "primevue/usetoast";

const toast = useToast();
const store = useStore();
const router = useRouter();
const form = reactive({
  name: "",
  description: "",
  location: "",
  logo: null,
  cover_photo: null,
  front: null,
  side: null,
  outside: null,
  inside: null,
  address: "",
  phone: "",
});

const errors = reactive({});

const previewLogo = ref(null);
const previewCover = ref(null);
const extraImages = reactive([
  { key: "front", label: "Front", preview: null },
  { key: "side", label: "Side", preview: null },
  { key: "outside", label: "Outside", preview: null },
  { key: "inside", label: "Inside", preview: null },
]);

const logoInput = ref(null);
const coverInput = ref(null);
const extraRefs = reactive({ front: null, side: null, outside: null, inside: null });
const loading = computed(() => store.getters["store/loading"]);
const editorOptions = { theme: "snow", placeholder: "Write store description..." };
const user = computed(() => store.getters["auth/user"]);

function handleLogoUpload(e) {
  const file = e.target.files[0];
  form.logo = file;
  if (file) previewLogo.value = URL.createObjectURL(file);
}
function handleCoverUpload(e) {
  const file = e.target.files[0];
  form.cover_photo = file;
  if (file) previewCover.value = URL.createObjectURL(file);
}
function triggerLogoInput() {
  logoInput.value.click();
}
function triggerCoverInput() {
  coverInput.value.click();
}

function handleExtraUpload(e, key) {
  const file = e.target.files[0];
  form[key] = file;
  const idx = extraImages.findIndex((i) => i.key === key);
  if (file && idx !== -1) extraImages[idx].preview = URL.createObjectURL(file);
}
function triggerExtraInput(key) {
  if (extraRefs[key]) extraRefs[key].click();
}

// Map
onMounted(() => {
  const map = L.map("map").setView([13.736717, 100.523186], 12);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  let marker;
  map.on("click", (e) => {
    const { lat, lng } = e.latlng;
    form.location = `${lat},${lng}`;
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
  });

  store.dispatch("auth/fetchUser");
});

async function submitForm() {
  await nextTick();
  Object.keys(errors).forEach((key) => delete errors[key]); // clear old errors

  // Convert Quill Delta to HTML
  let descriptionValue = form.description;
  if (typeof descriptionValue === "object" && descriptionValue.ops) {
    const tempQuill = new Quill(document.createElement("div"));
    tempQuill.setContents(descriptionValue);
    descriptionValue = tempQuill.root.innerHTML;
  }

  try {
    // Vuex sync
    store.dispatch("store/setField", { key: "user_id", value: user.value.id });
    store.dispatch("store/setField", { key: "name", value: form.name });
    store.dispatch("store/setField", { key: "description", value: descriptionValue });
    store.dispatch("store/setField", { key: "location", value: form.location });
    store.dispatch("store/setField", { key: "logo", value: form.logo });
    store.dispatch("store/setField", { key: "cover_photo", value: form.cover_photo });
    store.dispatch("store/setField", { key: "front", value: form.front });
    store.dispatch("store/setField", { key: "side", value: form.side });
    store.dispatch("store/setField", { key: "outside", value: form.outside });
    store.dispatch("store/setField", { key: "inside", value: form.inside });
    store.dispatch("store/setField", { key: "address", value: form.address });
    store.dispatch("store/setField", { key: "phone", value: form.phone });

    const result = await store.dispatch("store/submitStore");
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Store added successfully",
      life: 1000,
    });
    router.push({ name: "owner.stores" });
  } catch (err) {
    if (err.response && err.response.status === 422) {
      const laravelErrors = err.response.data.errors;
      for (const key in laravelErrors) {
        errors[key] = laravelErrors[key][0];
      }
      console.error("❌ Error saving store:", err.response.data);
      toast.add({
        severity: "error",
        summary: "Error",
        detail: `${err.response.data.message}`,
        life: 1000,
      });
    } else {
      console.error("❌ Error saving store:", err);
      toast.add({
        severity: "error",
        summary: "Error",
        detail: `${err.response.data.message}`,
        life: 1000,
      });
    }
  }
}
</script>
<style scoped>
.card {
  border-radius: 0.5rem;
}
#map {
  cursor: crosshair;
}

.logo-preview,
.cover-preview,
.extra-preview {
  border: 2px dashed #ccc;
  border-radius: 0.5rem;
  width: 100%;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  cursor: pointer;
}
.logo-preview {
  border-radius: 50%;
  width: 100px;
  height: 100px;
  margin: auto;
}
.logo-preview img,
.cover-preview img,
.extra-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.placeholder-text {
  color: #888;
  font-size: 0.8rem;
  text-align: center;
}
</style>
