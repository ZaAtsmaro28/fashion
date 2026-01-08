import { defineStore } from "pinia";
import api from "@/api";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: JSON.parse(localStorage.getItem("user")) || null,
        token: localStorage.getItem("token") || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        userRole: (state) => state.user?.role || null,
    },

    actions: {
        async login(credentials) {
            try {
                const response = await api.post("/login", credentials);
                const token = response.data.access_token;
                const user = response.data.user;

                if (token) {
                    this.token = token;
                    this.user = user;

                    localStorage.setItem("token", token);
                    localStorage.setItem("user", JSON.stringify(user));

                    api.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;
                    return response;
                }
            } catch (error) {
                throw error;
            }
        },

        async logout() {
            try {
                // Beri tahu server bahwa token ini sudah tidak berlaku
                await api.post("/logout");
            } catch (error) {
                console.error("Logout error on server:", error);
            } finally {
                // 1. Reset State Pinia secara manual
                this.token = null;
                this.user = null;

                // 2. Hapus data dari Local Storage
                localStorage.removeItem("token");
                localStorage.removeItem("user");

                // 3. Bersihkan Header Axios
                delete api.defaults.headers.common["Authorization"];

                // 4. Force reload (Opsional tapi direkomendasikan)
                window.location.href = "/login";
            }
        },
    },
});
