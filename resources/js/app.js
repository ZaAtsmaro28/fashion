import { createApp } from "vue";
import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import App from "./App.vue";
import router from "./router";
import { useAuthStore } from "@/stores/auth"; // Import store untuk digunakan di direktif

const app = createApp(App);
const pinia = createPinia();

// 1. Pasang Plugin
pinia.use(piniaPluginPersistedstate);
app.use(pinia);
app.use(router);

// 2. Daftarkan Kustom Direktif (SEBELUM Mount)
app.directive("role", {
    mounted(el, binding) {
        const authStore = useAuthStore();

        // binding.value akan berisi array role, misal: ['owner', 'gudang']
        const allowedRoles = binding.value;
        const userRole = authStore.userRole;

        if (!allowedRoles.includes(userRole)) {
            // Hapus elemen dari DOM jika role tidak sesuai
            el.parentNode?.removeChild(el);
        }
    },
});

// 3. Mount Aplikasi (Langkah Terakhir)
app.mount("#app");
