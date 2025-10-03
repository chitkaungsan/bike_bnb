import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index';
import store from './store/index';
import * as bootstrap from 'bootstrap'; // don't remove it still work
import i18n from './i18n';
store.dispatch('theme/initTheme');

createApp(App)
  .use(router)
  .use(store)
  .use(i18n)
  .mount('#app');