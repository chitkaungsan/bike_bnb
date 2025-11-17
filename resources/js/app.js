import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import '../css/main.css';
import router from './router/index';
import store from './store/index';
import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';
import i18n from './i18n';

// Quill Editor
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faBicycle, faEye, faMapLocationDot, faStore, faEyeSlash, faMoon,
  faMountainSun, faRoad, faCity, faBolt, faStar, faHeart, faChevronLeft, faChevronRight
} from '@fortawesome/free-solid-svg-icons';
import { faGoogle, faFacebookF } from '@fortawesome/free-brands-svg-icons';
import { faHeart as faHeartRegular } from '@fortawesome/free-regular-svg-icons';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fontsource/inter/400.css';
import '@fontsource/inter/500.css';
import '@fontsource/inter/600.css';
import '@fontsource/inter/700.css';
import "leaflet/dist/leaflet.css";

library.add(
  faBicycle, faGoogle, faFacebookF, faEye, faEyeSlash, faMapLocationDot, faStore,
  faMountainSun, faRoad, faCity, faBolt, faMoon, faStar, faHeart, faHeartRegular, faChevronLeft, faChevronRight
);

// PrimeVue
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import Tooltip from 'primevue/tooltip';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import 'primeicons/primeicons.css';
import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

// Init theme in store
store.dispatch('theme/initTheme');

// Create app
const app = createApp(App);

const globalOptions = {
  mode: 'auto',
};
// Components
app.component('font-awesome-icon', FontAwesomeIcon);
app.component('QuillEditor', QuillEditor);
app.component('Toast', Toast);
app.component('Button', Button);
app.component('Dialog', Dialog);

// Directives
app.directive('tooltip', Tooltip);

// Use Plugins
app.use(router)
   .use(store)
   .use(i18n)
   .use(ToastService)
   .use(VueTelInput, globalOptions)
   .use(PrimeVue, {
     theme: {
       preset: Aura
     }
   })
   .mount('#app');

window.router = router;
