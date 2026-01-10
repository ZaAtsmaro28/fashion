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
                    SYAR'IHUB
                </span>
            </div>
            <button
                @click="$emit('close-sidebar')"
                class="cursor-pointer lg:hidden p-1 text-primary-light hover:text-primary"
            >
                <X :size="22" />
            </button>
        </div>

        <nav
            class="flex-1 px-4 space-y-1 overflow-y-auto mt-4 overflow-x-hidden no-scrollbar"
        >
            <div v-for="item in filteredMenu" :key="item.label">
                <router-link
                    v-if="!item.children"
                    :to="item.path"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group relative"
                    :class="[
                        $route.path === item.path
                            ? 'bg-base text-primary font-bold shadow-sm'
                            : 'text-primary-light hover:bg-base/50 hover:text-primary',
                    ]"
                >
                    <component :is="item.icon" :size="20" class="shrink-0" />
                    <span
                        v-show="!isCollapsed || isMobileOpen"
                        class="font-medium whitespace-nowrap"
                        >{{ item.label }}</span
                    >

                    <div
                        v-if="isCollapsed"
                        class="hidden lg:block absolute left-14 bg-primary text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap"
                    >
                        {{ item.label }}
                    </div>
                </router-link>

                <div v-else class="space-y-1">
                    <button
                        @click="toggleDropdown(item.label)"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl transition-all group relative text-primary-light hover:bg-base/50 hover:text-primary"
                        :class="{
                            'text-primary font-bold bg-base/30':
                                isChildActive(item),
                        }"
                    >
                        <div class="flex items-center gap-3">
                            <component
                                :is="item.icon"
                                :size="20"
                                class="shrink-0"
                            />
                            <span
                                v-show="!isCollapsed || isMobileOpen"
                                class="font-medium whitespace-nowrap"
                                >{{ item.label }}</span
                            >
                        </div>
                        <ChevronDown
                            v-show="!isCollapsed || isMobileOpen"
                            :size="16"
                            class="transition-transform duration-300"
                            :class="{
                                'rotate-180': openMenus.includes(item.label),
                            }"
                        />
                    </button>

                    <transition name="expand">
                        <div
                            v-show="
                                openMenus.includes(item.label) &&
                                (!isCollapsed || isMobileOpen)
                            "
                            class="pl-9 space-y-1 overflow-hidden"
                        >
                            <router-link
                                v-for="child in item.children"
                                :key="child.path"
                                :to="child.path"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all group/child"
                                :class="[
                                    $route.path === child.path
                                        ? 'text-primary font-bold bg-primary/5'
                                        : 'text-primary-light hover:text-primary hover:bg-base/30',
                                ]"
                            >
                                <component
                                    :is="child.icon"
                                    :size="16"
                                    class="shrink-0 opacity-70 group-hover/child:opacity-100"
                                />
                                <span class="whitespace-nowrap">{{
                                    child.label
                                }}</span>
                            </router-link>
                        </div>
                    </transition>
                </div>
            </div>
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
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import {
    Shirt,
    LogOut,
    X,
    ChevronDown,
    Layers,
    LayoutDashboard,
    ShoppingCart,
    Warehouse,
    Package,
    FileText,
    Users,
    AlertTriangle,
    ArrowLeftRight,
    ClipboardList,
    PlusCircle,
    History,
} from "lucide-vue-next";

const props = defineProps({
    isCollapsed: Boolean,
    isMobileOpen: Boolean,
});

defineEmits(["logout", "close-sidebar"]);

const authStore = useAuthStore();
const route = useRoute();
const openMenus = ref([]);

const menuItems = [
    { path: "/", label: "Dashboard", icon: LayoutDashboard },
    {
        label: "Penjualan",
        icon: ShoppingCart,
        children: [
            { path: "/pos", label: "Transaksi", icon: PlusCircle },
            { path: "/pos/history", label: "Riwayat", icon: History },
        ],
    },
    { path: "/categories", label: "Kategori", icon: Layers },
    {
        path: "/products",
        label: "Produk",
        icon: Package,
        roles: ["owner", "gudang"],
    },
    {
        label: "Stok",
        icon: Warehouse,
        roles: ["owner", "gudang"],
        children: [
            {
                path: "/inventory/low-stock",
                label: "Stok Kritis",
                icon: AlertTriangle,
            },
            {
                path: "/inventory/mutations",
                label: "Mutasi Barang",
                icon: ArrowLeftRight,
            },
            {
                path: "/inventory/adjustments",
                label: "Adjustment",
                icon: ClipboardList,
            },
        ],
    },
    {
        path: "/reports/sales",
        label: "Laporan",
        icon: FileText,
        roles: ["owner"],
    },
    {
        path: "/users",
        label: "Manajemen Pengguna",
        icon: Users,
        roles: ["owner"],
    },
];

const filteredMenu = computed(() => {
    return menuItems.filter(
        (item) => !item.roles || item.roles.includes(authStore.userRole)
    );
});

const toggleDropdown = (label) => {
    const index = openMenus.value.indexOf(label);
    if (index > -1) {
        openMenus.value.splice(index, 1);
    } else {
        openMenus.value.push(label);
    }
};

const isChildActive = (item) => {
    if (!item.children) return false;
    return item.children.some((child) => route.path === child.path);
};

const autoOpenActiveDropdown = () => {
    filteredMenu.value.forEach((item) => {
        if (isChildActive(item) && !openMenus.value.includes(item.label)) {
            openMenus.value.push(item.label);
        }
    });
};

watch(() => route.path, autoOpenActiveDropdown);
onMounted(autoOpenActiveDropdown);
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.3s ease-in-out;
    max-height: 400px;
}
.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-10px);
}
</style>
