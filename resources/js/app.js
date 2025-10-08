import { createApp } from 'vue'
import App from './App.vue'
import '../css/main.css'
import router from './router/index'
import store from './store/index'
import 'bootstrap/dist/css/bootstrap.min.css'
import * as bootstrap from 'bootstrap'
import i18n from './i18n'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {  faBicycle, faEye, faMapLocationDot, faStore, faEyeSlash, faMoon,
  faMountainSun, faRoad, faCity, faBolt,faStar, faHeart, faChevronLeft, faChevronRight  } from '@fortawesome/free-solid-svg-icons'
import { faGoogle, faFacebookF } from '@fortawesome/free-brands-svg-icons'
import { faHeart as faHeartRegular } from '@fortawesome/free-regular-svg-icons';
import 'bootstrap/dist/css/bootstrap.min.css' // if not already imported
import 'bootstrap-icons/font/bootstrap-icons.css' // this imports the icons
// Inter fonts
import '@fontsource/inter/400.css'
import '@fontsource/inter/500.css'
import '@fontsource/inter/600.css'
import '@fontsource/inter/700.css'
import "leaflet/dist/leaflet.css";
// Add icons to the library
library.add(faBicycle, faGoogle, faFacebookF, faEye, faEyeSlash, faMapLocationDot, faStore,
  faMountainSun, faRoad, faCity, faBolt, faMoon,faStar, faHeart, faHeartRegular, faChevronLeft, faChevronRight)
import vueFilePond from 'vue-filepond/dist/vue-filepond.js';
import 'vue-multiselect/dist/vue-multiselect.min.css'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import Tooltip from 'primevue/tooltip';
import 'primeicons/primeicons.css';
import Button from 'primevue/button';
// Init theme in store
store.dispatch('theme/initTheme')

// Create Vue app instance
const app = createApp(App)

// Register font-awesome component
app.component('font-awesome-icon', FontAwesomeIcon)
app.component('QuillEditor', QuillEditor)
app.component('Toast', Toast)
app.component('Button', Button);

app.directive('tooltip', Tooltip);
// Use plugins
app.use(router)
   .use(store)
   .use(i18n)
   .use(ToastService)
   .use(PrimeVue, {
    theme: {
        preset: Aura
    }
})
   .mount('#app')

   window.router = router