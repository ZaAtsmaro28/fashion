import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import { useAuthStore } from "@/stores/auth";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // 1. Cek jika rute butuh autentikasi
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    // 2. Cek jika rute hanya untuk guest (seperti login)
    const guestOnly = to.matched.some(
        (record) => record.meta.requiresAuth === false
    );

    // Ambil token dari store atau localStorage langsung untuk validasi paling akurat
    const isAuthenticated =
        !!authStore.token || !!localStorage.getItem("token");

    if (requiresAuth && !isAuthenticated) {
        // Jika butuh login tapi tidak ada token, lempar ke login
        next({ name: "login" });
    } else if (guestOnly && isAuthenticated) {
        // Jika sudah login tapi coba buka halaman login, lempar ke dashboard
        next({ name: "dashboard" });
    } else {
        // Lanjutkan navigasi
        next();
    }
});

export default router;
