// store/index.js
import { createStore } from 'vuex';
import users from './modules/users';
import theme from './modules/theme';
import auth from './modules/auth';
import store from './modules/store';
import bikes from './modules/bikes';
const stores = createStore({
  modules: {
    users,
    theme,
    auth,
    store,
    bikes
  }
});
export default stores;