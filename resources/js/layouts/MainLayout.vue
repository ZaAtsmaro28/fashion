<template>
    <div class="flex h-screen bg-base text-primary font-sans overflow-hidden">
        <div
            v-if="uiStore.isLoading"
            class="fixed top-0 left-0 w-full h-1 z-9999 overflow-hidden bg-secondary/20"
        >
            <div class="h-full bg-accent animate-progress"></div>
        </div>

        <transition name="fade">
            <div
                v-if="isMobileSidebarOpen"
                @click="isMobileSidebarOpen = false"
                class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-40 lg:hidden"
            ></div>
        </transition>

        <BaseModal
            :show="isLogoutModalOpen"
            title="Konfirmasi Keluar"
            confirm-text="Ya, Keluar"
            variant="danger"
            @close="isLogoutModalOpen = false"
            @confirm="confirmLogout"
        >
            <template #icon>
                <LogOut :size="24" />
            </template>
            <template #description>
                Apakah Anda yakin ingin keluar dari sistem SYAR'IHUB? Sesi Anda
                akan berakhir.
            </template>
        </BaseModal>

        <Sidebar
            :is-collapsed="isSidebarCollapsed"
            :is-mobile-open="isMobileSidebarOpen"
            :menu-items="menuItems"
            @logout="handleLogout"
            @close-sidebar="isMobileSidebarOpen = false"
        />

        <div class="flex-1 flex flex-col overflow-hidden">
            <TopBar
                :is-collapsed="isSidebarCollapsed"
                @toggle-sidebar="isSidebarCollapsed = !isSidebarCollapsed"
                @open-mobile-sidebar="isMobileSidebarOpen = true"
            />

            <main class="flex-1 overflow-y-auto p-4 md:p-8 relative">
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>

                <footer
                    class="mt-12 py-6 border-t border-secondary/10 text-center text-xs text-primary-light opacity-60"
                >
                    <p>© 2026 SYARIHUB Fashion Management. Versi 1.0.0</p>
                </footer>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useUiStore } from "@/stores/ui";
import { useAuthStore } from "@/stores/auth";
import { LogOut } from "lucide-vue-next";
import BaseModal from "@/components/BaseModal.vue";
import {
    LayoutDashboard,
    ShoppingCart,
    Package,
    Users,
    FileText,
    AlertTriangle,
} from "lucide-vue-next";

import Sidebar from "./Sidebar.vue";
import TopBar from "./TopBar.vue";

const uiStore = useUiStore();
const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const isSidebarCollapsed = ref(false);
const isMobileSidebarOpen = ref(false);
const isLogoutModalOpen = ref(false);

// Otomatis tutup sidebar mobile saat pindah halaman
watch(
    () => route.path,
    () => {
        isMobileSidebarOpen.value = false;
    }
);

const menuItems = [
    { path: "/", label: "Dashboard", icon: LayoutDashboard },
    { path: "/pos", label: "Penjualan", icon: ShoppingCart },
    {
        path: "/inventory/low-stock",
        label: "Stok Kritis",
        icon: AlertTriangle,
        roles: ["owner", "gudang"],
    },
    {
        path: "/products",
        label: "Produk",
        icon: Package,
        roles: ["owner", "gudang"],
    },
    {
        path: "/reports/sales",
        label: "Laporan",
        icon: FileText,
        roles: ["owner"],
    },
    { path: "/users", label: "Pengguna", icon: Users, roles: ["owner"] },
];

const handleLogout = () => {
    isLogoutModalOpen.value = true;
};

// Fungsi eksekusi setelah user klik "Ya, Keluar" di modal
const confirmLogout = async () => {
    isLogoutModalOpen.value = false; // Tutup modal dulu
    await authStore.logout();
    router.push({ name: "login" });
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes progress {
    0% {
        width: 0;
        transform: translateX(0);
    }
    50% {
        width: 70%;
    }
    100% {
        width: 100%;
        transform: translateX(100%);
    }
}
.animate-progress {
    animation: progress 1.5s infinite linear;
}
</style>
