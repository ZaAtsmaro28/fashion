<template>
    <router-view />
</template>

<script setup>
import { onMounted } from "vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();

onMounted(async () => {
    // Cek sesi user saat aplikasi pertama kali dimuat
    if (authStore.token && !authStore.user) {
        await authStore.fetchUser();
    }
});
</script>
