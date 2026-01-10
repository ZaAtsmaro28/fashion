import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import { useAuthStore } from "@/stores/auth";
import api from "@/api";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // SINKRONISASI HEADER AXIOS (Kunci agar tidak 401 saat refresh)
    if (authStore.token) {
        api.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${authStore.token}`;
    }

    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const guestOnly = to.matched.some(
        (record) => record.meta.requiresAuth === false
    );

    // Cek status autentikasi dari getter store
    const isAuthenticated = authStore.isAuthenticated;

    if (requiresAuth && !isAuthenticated) {
        next({ name: "login" });
    } else if (guestOnly && isAuthenticated) {
        next({ name: "products.index" }); // Arahkan ke katalog jika sudah login
    } else {
        next();
    }
});

export default router;
