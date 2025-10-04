// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import store from '../store';
import Home from '../Pages/Home.vue';
import Login from '../Pages/Auth/Login.vue';
import Register from '../Pages/Auth/Register.vue';
import Dashboard from '../Pages/Dashboard/index.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { adminOnly: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {

  // Pages that require authentication
  const requiresAuth = to.meta.requiresAuth;

  // If auth required and user not loaded yet
  if (requiresAuth && !store.getters['auth/user']) {
    try {
      await store.dispatch('auth/fetchUser'); // fetch user first
      next(); // proceed after fetching
    } catch {
      next({ name: '/' }); // redirect if fetch fails
    }
  } else {
    next();
  }
});
export default router;
