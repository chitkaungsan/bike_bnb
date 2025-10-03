// store/index.js
import { createStore } from 'vuex';
import users from './modules/users';
import theme from './modules/theme';
import auth from './modules/auth';

const stores = createStore({
  modules: {
    users,
    theme,
    auth
  }
});
export default stores;