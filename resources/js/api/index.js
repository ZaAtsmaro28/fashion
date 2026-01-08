import axios from "axios";
import { useUiStore } from "@/stores/ui"; // Import UI Store

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
        uiStore.setLoading(true); // Mulai Loading

        const token = localStorage.getItem("auth_token");
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
        uiStore.setLoading(false); // Selesai Loading
        return response;
    },
    (error) => {
        const uiStore = useUiStore();
        uiStore.setLoading(false); // Selesai Loading (meskipun error)

        if (error.response && error.response.status === 401) {
            localStorage.removeItem("auth_token");
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

export default api;
