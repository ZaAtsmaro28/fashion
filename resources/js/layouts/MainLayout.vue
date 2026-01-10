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

        <Sidebar
            :is-collapsed="isSidebarCollapsed"
            :is-mobile-open="isMobileSidebarOpen"
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

        <BaseModal
            :show="isLogoutModalOpen"
            title="Konfirmasi Keluar"
            confirm-text="Ya, Keluar"
            variant="danger"
            @close="isLogoutModalOpen = false"
            @confirm="confirmLogout"
        >
            <template #icon><LogOut :size="24" /></template>
            <template #description>
                Apakah Anda yakin ingin keluar dari sistem SYAR'IHUB? Sesi Anda
                akan berakhir.
            </template>
        </BaseModal>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useUiStore } from "@/stores/ui";
import { useAuthStore } from "@/stores/auth";
import { LogOut } from "lucide-vue-next";
import BaseModal from "@/components/BaseModal.vue";
import Sidebar from "./Sidebar.vue";
import TopBar from "./TopBar.vue";

const uiStore = useUiStore();
const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const isSidebarCollapsed = ref(false);
const isMobileSidebarOpen = ref(false);
const isLogoutModalOpen = ref(false);

watch(
    () => route.path,
    () => {
        isMobileSidebarOpen.value = false;
    }
);

const handleLogout = () => {
    isLogoutModalOpen.value = true;
};
const confirmLogout = async () => {
    isLogoutModalOpen.value = false;
    await authStore.logout();
    router.push({ name: "login" });
};
</script>
