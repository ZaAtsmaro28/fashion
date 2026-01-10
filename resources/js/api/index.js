import axios from "axios";
import { useUiStore } from "@/stores/ui";
import { useAuthStore } from "@/stores/auth"; // Import Auth Store

const api = axios.create({
    baseURL: "http://fashion.test/api",
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

api.interceptors.request.use(
    (config) => {
        const uiStore = useUiStore();
        const authStore = useAuthStore(); // Ambil dari store

        uiStore.setLoading(true);

        // Gunakan kunci "token" agar sama dengan yang ada di AuthStore
        const token = authStore.token || localStorage.getItem("token");

        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        const uiStore = useUiStore();
        uiStore.setLoading(false);
        return Promise.reject(error);
    }
);

api.interceptors.response.use(
    (response) => {
        const uiStore = useUiStore();
        uiStore.setLoading(false);
        return response;
    },
    (error) => {
        const uiStore = useUiStore();
        const authStore = useAuthStore();

        uiStore.setLoading(false);

        // Jika error 401 (Unauthorized/Token Expired)
        if (error.response && error.response.status === 401) {
            // Bersihkan data di store dan localStorage melalui action logout
            // Kita tidak perlu memanggil API logout ke server karena token sudah tidak valid
            authStore.$patch({
                token: null,
                user: null,
            });
            localStorage.removeItem("token");
            localStorage.removeItem("user");

            // Redirect ke login hanya jika kita tidak sedang berada di halaman login
            if (window.location.pathname !== "/login") {
                window.location.href = "/login";
            }
        }
        return Promise.reject(error);
    }
);

export default api;
