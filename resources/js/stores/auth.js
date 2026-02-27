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

                    api.defaults.headers.common["Authorization"] =
                        `Bearer ${token}`;
                    return response;
                }
            } catch (error) {
                throw error;
            }
        },

        async logout() {
            try {
                await api.post("/logout");
            } catch (error) {
                console.error("Logout error on server:", error);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                delete api.defaults.headers.common["Authorization"];
                window.location.href = "/login";
            }
        },

        async updateProfile(payload) {
            try {
                const response = await api.put("/profile", payload);

                this.user.name = payload.name;
                this.user.email = payload.email;

                localStorage.setItem("user", JSON.stringify(this.user));

                return response.data;
            } catch (error) {
                throw error;
            }
        },

        async updatePassword(payload) {
            try {
                const response = await api.put("/profile/password", payload);
                return response.data;
            } catch (error) {
                throw error;
            }
        },
    },
});
