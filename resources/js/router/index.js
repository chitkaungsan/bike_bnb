import { createRouter, createWebHistory } from 'vue-router';
import store from '../store';
import Home from '../Pages/Home.vue';
import Login from '../Pages/Auth/Login.vue';
import Register from '../Pages/Auth/Register.vue';
import Dashboard from '../Pages/Dashboard/index.vue';
const routes = [
  {path: '/', name: 'Home', component: Home},
  {path: '/login', name: 'Login', component: Login},
  {path: '/register', name: 'Register', component: Register},
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true }},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// const store = useStore();
// router.beforeEach((to, from, next) => {
//   if (to.matched.some((record) => record.meta.requiresAuth)) {
//     if (!store.getters['auth/isAuthenticated']) {
//       next({ name: 'Login' });
//     } else {
//       next();
//     }
//   } else {
//     next();
//   }
// });
export default router;