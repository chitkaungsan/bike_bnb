import { createApp } from 'vue'
import App from './App.vue'
import '../css/main.css'
import router from './router/index'
import store from './store/index'
import * as bootstrap from 'bootstrap' // keep bootstrap import
import i18n from './i18n'

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faBicycle,faEye,faMapLocationDot,faStore, faEyeSlash } from '@fortawesome/free-solid-svg-icons'
import { faGoogle, faFacebookF } from '@fortawesome/free-brands-svg-icons'

// Inter fonts
import '@fontsource/inter/400.css'
import '@fontsource/inter/500.css'
import '@fontsource/inter/600.css'
import '@fontsource/inter/700.css'

// Add icons to the library
library.add(faBicycle, faGoogle, faFacebookF,faEye, faEyeSlash,faMapLocationDot,faStore,)

// Init theme in store
store.dispatch('theme/initTheme')

// Create Vue app instance
const app = createApp(App)

// Register font-awesome component
app.component('font-awesome-icon', FontAwesomeIcon)

// Use plugins
app.use(router)
   .use(store)
   .use(i18n)
   .mount('#app')
