// router/index.js
import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
import Home from "../Pages/Home.vue";
import Login from "../Pages/Auth/Login.vue";
import Register from "../Pages/Auth/Register.vue";
import RenterDashboard from "../Pages/Renter/Dashboard.vue";
import OwnerDashboard from "../Pages/Owner/Dashboard.vue";
import AdminDashboard from "../Pages/Admin/Dashboard.vue";
// import Dashboard from '../Pages/Dashboard/index.vue';
import AuthCallback from "../components/AuthCallback.vue";
import BikeDetail from "../Pages/BikeDetail.vue";
import StoreDetail from "../Pages/StoreDetail.vue";
import Bikes from "../Pages/Bikes.vue";
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import ownerRoutes from './owner.js'
import adminRoutes from './admin_.js'
import renterRoutes from './renter.js'
import BookingRoutes from './booking.js'
const routes = [
    { path: "/", name: "Home", component: Home },
    { path: "/login", name: "Login", component: Login },
    { path: "/register", name: "Register", component: Register },
    { path: '/auth/google/callback', component: AuthCallback },
    { path : '/bikes', name: 'bikes', component: Bikes },
    { path : '/bike/:id', name: 'bikes.detail', component: BikeDetail },
    { path : '/store/:id', name: 'store.detail', component: StoreDetail },

    ...ownerRoutes,
    ...adminRoutes,
    ...renterRoutes,
    ...BookingRoutes,
];

const router = createRouter({
    history: createWebHistory(),
    routes,
     scrollBehavior(to, from, savedPosition) {
        // If using browser back/forward, restore saved position
        if (savedPosition) return savedPosition;
        // Always scroll to top for new pages
        return { top: 0 };
    }
});

NProgress.configure({ showSpinner: true })


router.beforeEach(async (to, from, next) => {
      NProgress.start()
        if (to.path === "/auth/google/callback") {
    next();
    return; 
  }
    // Pages that require authentication
    const requiresAuth = to.meta.requiresAuth;

    // If auth required and user not loaded yet
    if (requiresAuth && !store.getters["auth/user"]) {
        try {
            await store.dispatch("auth/fetchUser"); // fetch user first
            next(); // proceed after fetching
        } catch {
            next({ name: "Home" }); // redirect if fetch fails
        }
    } else {
        next();
    }
});

router.afterEach(() => {
  NProgress.done()
});

export default router;
