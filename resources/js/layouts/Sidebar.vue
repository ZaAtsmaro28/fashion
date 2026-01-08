<template>
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-50 bg-white border-r border-secondary/30 flex flex-col transition-all duration-300 ease-in-out lg:static lg:translate-x-0',
            isMobileOpen
                ? 'translate-x-0 w-64'
                : '-translate-x-full lg:translate-x-0',
            isCollapsed ? 'lg:w-20' : 'lg:w-64',
        ]"
    >
        <div class="p-6 flex items-center justify-between">
            <div
                class="flex items-center gap-3 overflow-hidden whitespace-nowrap"
            >
                <div
                    class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white shrink-0 shadow-sm"
                >
                    <Shirt :size="20" />
                </div>
                <span
                    v-show="!isCollapsed || isMobileOpen"
                    class="font-bold text-xl tracking-tight text-primary uppercase"
                >
                    SYARIHUB
                </span>
            </div>

            <button
                @click="$emit('close-sidebar')"
                class="cursor-pointer lg:hidden p-1 text-primary-light hover:text-primary transition-colors"
            >
                <X :size="22" />
            </button>
        </div>

        <nav
            class="flex-1 px-4 space-y-1 overflow-y-auto mt-4 overflow-x-hidden"
        >
            <router-link
                v-for="item in menuItems"
                :key="item.path"
                :to="item.path"
                v-show="!item.roles || item.roles.includes(authStore.userRole)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group relative"
                :class="[
                    $route.path === item.path
                        ? 'bg-base text-primary font-bold shadow-xs'
                        : 'text-primary-light hover:bg-base/50 hover:text-primary',
                ]"
            >
                <component :is="item.icon" :size="20" class="shrink-0" />
                <span
                    v-show="!isCollapsed || isMobileOpen"
                    class="font-medium whitespace-nowrap"
                >
                    {{ item.label }}
                </span>

                <div
                    v-if="isCollapsed"
                    class="hidden lg:block absolute left-14 bg-primary text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap"
                >
                    {{ item.label }}
                </div>
            </router-link>
        </nav>

        <div class="p-4 border-t border-secondary/20 overflow-hidden">
            <button
                @click="$emit('logout')"
                class="cursor-pointer flex items-center gap-3 w-full px-3 py-2 text-primary-light hover:text-danger hover:bg-danger/5 rounded-xl transition-colors font-medium whitespace-nowrap"
            >
                <LogOut :size="20" class="shrink-0" />
                <span v-show="!isCollapsed || isMobileOpen">Keluar</span>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { Shirt, LogOut, X } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";

defineProps({
    isCollapsed: Boolean,
    isMobileOpen: Boolean, // Tambahkan prop ini
    menuItems: Array,
});

defineEmits(["logout", "close-sidebar"]); // Tambahkan emit close-sidebar

const authStore = useAuthStore();
</script>
