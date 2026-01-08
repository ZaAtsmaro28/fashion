import { defineStore } from "pinia";

export const useUiStore = defineStore("ui", {
    state: () => ({
        isLoading: false,
        // Kita bisa tambahkan state global lain seperti sidebarOpen atau notifications di sini
    }),

    actions: {
        setLoading(status) {
            this.isLoading = status;
        },
    },
});
