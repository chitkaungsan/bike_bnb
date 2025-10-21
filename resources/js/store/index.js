// store/index.js
import { createStore } from 'vuex';
import users from './modules/users';
import theme from './modules/theme';
import auth from './modules/Auth';
import store from './modules/store';
import bikes from './modules/bikes';
import booking from './modules/booking';
import cities from './modules/cities';
const stores = createStore({
  modules: {
    users,
    theme,
    auth,
    store,
    bikes,
    booking,
    cities
  }
});
export default stores;