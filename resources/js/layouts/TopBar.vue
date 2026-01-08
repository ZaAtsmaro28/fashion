<template>
    <header
        class="h-16 bg-white border-b border-secondary/20 flex items-center justify-between px-6 shrink-0"
    >
        <div class="flex items-center gap-4">
            <button
                @click="$emit('open-mobile-sidebar')"
                class="cursor-pointer lg:hidden p-2 rounded-lg hover:bg-base text-primary-light hover:text-primary transition-colors"
            >
                <Menu :size="24" />
            </button>

            <button
                @click="$emit('toggleSidebar')"
                class="cursor-pointer hidden lg:block p-2 rounded-lg hover:bg-base text-primary-light hover:text-primary transition-colors"
            >
                <PanelsTopLeft v-if="isCollapsed" :size="24" />
                <PanelLeftClose v-else :size="24" />
            </button>

            <h2
                class="font-bold text-lg text-primary capitalize tracking-tight hidden md:block"
            >
                {{ currentRouteName }}
            </h2>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-primary leading-none">
                    {{ authStore.user?.name }}
                </p>
                <p
                    class="text-xs text-primary-light capitalize leading-none mt-1"
                >
                    {{ authStore.userRole }}
                </p>
            </div>
            <div
                class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-white border border-secondary/20 uppercase font-bold shadow-sm"
            >
                {{ authStore.user?.name?.charAt(0) }}
            </div>
        </div>
    </header>
</template>

<script setup>
import { PanelsTopLeft, Menu, PanelLeftClose } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";
import { computed } from "vue";
import { useRoute } from "vue-router";

defineProps({ isCollapsed: Boolean });

// PASTIKAN KEDUA EMIT INI ADA
defineEmits(["toggleSidebar", "open-mobile-sidebar"]);

const authStore = useAuthStore();
const route = useRoute();

const currentRouteName = computed(
    () => route.name?.replace(".", " ") || "Dashboard"
);
</script>
