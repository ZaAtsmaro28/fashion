<template>
    <div class="space-y-6 md:space-y-8 pb-10">
        <div class="flex flex-col gap-1">
            <h1
                class="text-xl md:text-2xl font-extrabold text-primary tracking-tight"
            >
                Ringkasan Bisnis
            </h1>
            <p class="text-primary-light text-sm md:text-base font-medium">
                Pantau performa koleksi fashion Anda hari ini.
            </p>
        </div>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6"
        >
            <div
                class="bg-white p-5 md:p-6 rounded-4xl border border-secondary/20 shadow-sm flex items-center gap-4 md:gap-5 group hover:border-primary/30 transition-all"
            >
                <div
                    class="w-12 h-12 md:w-14 md:h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shrink-0"
                >
                    <BadgeDollarSign :size="24" class="md:hidden" />
                    <BadgeDollarSign :size="28" class="hidden md:block" />
                </div>
                <div class="min-w-0">
                    <p
                        class="text-[10px] md:text-xs font-bold text-primary-light uppercase tracking-wider truncate"
                    >
                        Pendapatan
                    </p>
                    <h3 class="md:text-lg font-black text-primary truncate">
                        {{ formatCurrency(stats.today_turnover) }}
                    </h3>
                </div>
            </div>

            <div
                class="bg-white p-5 md:p-6 rounded-4xl border border-secondary/20 shadow-sm flex items-center gap-4 md:gap-5 group hover:border-secondary transition-all"
            >
                <div
                    class="w-12 h-12 md:w-14 md:h-14 bg-secondary/10 text-secondary rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shrink-0"
                >
                    <ShoppingCart :size="24" class="md:hidden" />
                    <ShoppingCart :size="28" class="hidden md:block" />
                </div>
                <div class="min-w-0">
                    <p
                        class="text-[10px] md:text-xs font-bold text-primary-light uppercase tracking-wider truncate"
                    >
                        Pesanan
                    </p>
                    <h3 class="md:text-lg font-black text-primary truncate">
                        {{ stats.today_transactions }}
                        <span class="text-sm font-medium text-primary-light"
                            >Trx</span
                        >
                    </h3>
                </div>
            </div>

            <div
                class="bg-white p-5 md:p-6 rounded-4xl border border-secondary/20 shadow-sm flex items-center gap-4 md:gap-5 group hover:border-accent transition-all"
            >
                <div
                    class="w-12 h-12 md:w-14 md:h-14 bg-accent/10 text-accent rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shrink-0"
                >
                    <PackageCheck :size="24" class="md:hidden" />
                    <PackageCheck :size="28" class="hidden md:block" />
                </div>
                <div class="min-w-0">
                    <p
                        class="text-[10px] md:text-xs font-bold text-primary-light uppercase tracking-wider truncate"
                    >
                        Total Koleksi
                    </p>
                    <h3 class="md:text-lg font-black text-primary truncate">
                        {{ stats.total_products }}
                        <span class="text-sm font-medium text-primary-light"
                            >Produk</span
                        >
                    </h3>
                </div>
            </div>

            <div
                class="p-5 md:p-6 rounded-4xl border shadow-sm flex items-center gap-4 md:gap-5 group transition-all"
                :class="
                    stats.critical_stock_count > 0
                        ? 'bg-danger/5 border-danger/20'
                        : 'bg-white border-secondary/20'
                "
            >
                <div
                    class="w-12 h-12 md:w-14 md:h-14 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shrink-0"
                    :class="
                        stats.critical_stock_count > 0
                            ? 'bg-danger text-white'
                            : 'bg-secondary/10 text-secondary'
                    "
                >
                    <AlertTriangle :size="24" class="md:hidden" />
                    <AlertTriangle :size="28" class="hidden md:block" />
                </div>
                <div class="min-w-0">
                    <p
                        class="text-[10px] md:text-xs font-bold text-primary-light uppercase tracking-wider truncate"
                    >
                        Stok Kritis
                    </p>
                    <h3
                        class="text-base md:text-lg font-black truncate"
                        :class="
                            stats.critical_stock_count > 0
                                ? 'text-danger'
                                : 'text-primary'
                        "
                    >
                        {{ stats.critical_stock_count }}
                        <span class="text-sm font-medium opacity-70">Item</span>
                    </h3>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-4xl border border-secondary/20 shadow-sm overflow-hidden"
        >
            <div
                class="p-5 md:p-6 border-b border-base flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
            >
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                    <h3 class="font-bold text-primary md:text-lg">
                        Produk Terlaris
                    </h3>
                </div>
                <router-link
                    to="/reports/sales"
                    class="text-xs md:text-sm font-bold text-accent hover:text-primary transition-colors flex items-center gap-1 group"
                >
                    Lihat Semua
                    <ArrowRight
                        :size="14"
                        class="group-hover:translate-x-1 transition-transform"
                    />
                </router-link>
            </div>

            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full text-left border-collapse min-w-112.5">
                    <thead>
                        <tr
                            class="bg-base/30 text-primary-light text-[10px] uppercase tracking-[0.15em]"
                        >
                            <th class="px-6 md:px-8 py-4 font-black">
                                Nama Produk
                            </th>
                            <th
                                class="px-6 py-4 font-black text-center text-secondary"
                            >
                                Qty Terjual
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base">
                        <tr
                            v-for="(item, index) in topProducts"
                            :key="index"
                            class="hover:bg-base/10 transition-colors group"
                        >
                            <td class="px-6 md:px-8 py-4">
                                <span
                                    class="font-bold text-sm md:text-base text-primary group-hover:text-accent transition-colors"
                                >
                                    {{ item.name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-secondary/10 text-primary text-xs md:text-sm font-bold"
                                >
                                    {{ item.sold }}
                                </span>
                            </td>
                        </tr>

                        <tr v-if="topProducts.length === 0">
                            <td
                                colspan="2"
                                class="px-6 py-12 md:py-16 text-center"
                            >
                                <div class="text-secondary/40 italic text-sm">
                                    Belum ada aktivitas penjualan hari ini
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import {
    BadgeDollarSign,
    ShoppingCart,
    PackageCheck,
    AlertTriangle,
    ArrowRight,
} from "lucide-vue-next";
import api from "@/api";
import { formatCurrency } from "@/utils/formatters";

const stats = reactive({
    total_products: 0,
    critical_stock_count: 0,
    today_turnover: 0,
    today_transactions: 0,
});

const topProducts = ref([]);

const fetchDashboardStats = async () => {
    try {
        const response = await api.get("/dashboard/summary");
        const resData = response.data.data;
        Object.assign(stats, resData.statistics);
        topProducts.value = resData.top_selling_products;
    } catch (error) {
        console.error("Gagal memuat data dashboard:", error);
    }
};

onMounted(() => {
    fetchDashboardStats();
});
</script>
