// router/index.js
import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
import Home from "../Pages/Home.vue";
import Login from "../Pages/Auth/Login.vue";
import Register from "../Pages/Auth/Register.vue";
import RenterDashboard from "../pages/Renter/Dashboard.vue";
import OwnerDashboard from "../pages/Owner/Dashboard.vue";
import AdminDashboard from "../pages/Admin/Dashboard.vue";
// import Dashboard from '../Pages/Dashboard/index.vue';
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import ownerRoutes from './owner.js'
import adminRoutes from './admin_.js'
import renterRoutes from './renter.js'
const routes = [
    { path: "/", name: "Home", component: Home },
    { path: "/login", name: "Login", component: Login },
    { path: "/register", name: "Register", component: Register },
    ...ownerRoutes,
    ...adminRoutes,
    ...renterRoutes,
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
NProgress.configure({ showSpinner: false })

router.beforeEach(async (to, from, next) => {
      NProgress.start()
    // Pages that require authentication
    const requiresAuth = to.meta.requiresAuth;

    // If auth required and user not loaded yet
    if (requiresAuth && !store.getters["auth/user"]) {
        try {
            await store.dispatch("auth/fetchUser"); // fetch user first
            next(); // proceed after fetching
        } catch {
            next({ name: "/" }); // redirect if fetch fails
        }
    } else {
        next();
    }
});

router.afterEach(() => {
  NProgress.done()
});
export default router;
