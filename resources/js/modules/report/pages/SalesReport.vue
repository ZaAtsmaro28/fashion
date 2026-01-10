<template>
    <div class="max-w-6xl mx-auto px-8 pb-20 pt-10">
        <div class="flex justify-between items-end mb-10">
            <div>
                <p
                    class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-1"
                >
                    Manajemen Data
                </p>
                <h2
                    class="text-3xl font-black text-primary uppercase tracking-tighter"
                >
                    Laporan Keuangan
                </h2>
            </div>

            <button
                v-if="store.hasFetched"
                @click="handleExport"
                class="bg-primary text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-2"
            >
                <FileDown :size="18" stroke-width="3" />
                Ekspor Excel
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="lg:col-span-3 bg-white p-6 rounded-3xl shadow-sm flex flex-col md:flex-row items-end gap-6 border border-white"
            >
                <BaseInputDate
                    label="Mulai Tanggal"
                    v-model="store.filters.start_date"
                />
                <BaseInputDate
                    label="Sampai Tanggal"
                    v-model="store.filters.end_date"
                />
                <button
                    @click="store.fetchSalesReport()"
                    :disabled="store.isLoading"
                    class="bg-[#768364] text-white px-8 py-3 rounded-xl font-bold text-xs uppercase hover:bg-black transition-colors disabled:opacity-50"
                >
                    {{ store.isLoading ? "Memproses..." : "Tampilkan Data" }}
                </button>
            </div>

            <div
                class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100 flex flex-col justify-center"
            >
                <p
                    class="text-[10px] font-bold text-[#768364] uppercase opacity-60 mb-1"
                >
                    Total Omzet
                </p>
                <h2 class="text-lg font-black text-[#768364]">
                    {{ formatCurrency(store.salesData?.total_revenue || 0) }}
                </h2>
            </div>
        </div>

        <div
            class="bg-white rounded-4xl shadow-sm overflow-hidden border border-white"
        >
            <div
                class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4"
            >
                <h3
                    class="font-bold text-[#768364] uppercase text-sm tracking-wider flex items-center gap-2"
                >
                    <div class="w-1 h-5 bg-[#768364] rounded-full"></div>
                    Daftar Transaksi Produk
                </h3>
                <div class="w-full md:w-80">
                    <BaseSearch
                        v-model="searchQuery"
                        placeholder="Cari SKU atau nama produk di halaman ini..."
                    />
                </div>
            </div>

            <div
                v-if="store.isLoading && !store.hasFetched"
                class="p-32 text-center"
            >
                <p class="text-gray-400 font-bold uppercase animate-pulse">
                    Memuat Data...
                </p>
            </div>

            <div v-else-if="!store.hasFetched" class="p-32 text-center">
                <p
                    class="text-gray-400 font-bold uppercase tracking-widest text-sm"
                >
                    Silakan input rentang tanggal laporan
                </p>
            </div>

            <div v-else class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100"
                            >
                                <th class="px-8 py-4">No</th>
                                <th class="px-8 py-4">Nama Produk</th>
                                <th class="px-8 py-4 text-center">
                                    Qty Terjual
                                </th>
                                <th class="px-8 py-4 text-right">
                                    Total (IDR)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-[#768364]">
                            <tr
                                v-for="(item, index) in filteredItems"
                                :key="item?.product_id || index"
                                class="hover:bg-[#f3f0e7]/40 transition-colors"
                            >
                                <td
                                    class="px-8 py-6 text-sm font-medium opacity-50"
                                >
                                    {{
                                        (store.paginationMeta?.from || 0) +
                                        index
                                    }}
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-[10px] font-bold opacity-40 uppercase"
                                        >
                                            {{
                                                item?.product?.sku ||
                                                "TANPA SKU"
                                            }}
                                        </span>
                                        <span
                                            class="text-sm font-extrabold uppercase"
                                        >
                                            {{
                                                item?.product?.name ||
                                                "Produk Tidak Terdaftar"
                                            }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-8 py-6 text-center font-black text-gray-700 text-sm"
                                >
                                    {{ item?.total_qty || 0 }}
                                </td>
                                <td
                                    class="px-8 py-6 text-right font-black text-sm"
                                >
                                    {{
                                        formatCurrency(item?.total_amount || 0)
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination
                    v-if="store.paginationMeta"
                    :meta="store.paginationMeta"
                    @change-page="handlePageChange"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { FileDown, FileDownIcon } from "lucide-vue-next";
import { useSalesReportStore } from "../../../stores/report";
import api from "../../../api/index";
import BaseInputDate from "@/components/BaseInputDate.vue";
import BaseSearch from "@/components/BaseSearch.vue";
import Pagination from "@/components/Pagination.vue"; // Pastikan path benar

const store = useSalesReportStore();
const searchQuery = ref("");

// Otomatis panggil data saat halaman dibuka
onMounted(() => {
    store.fetchSalesReport();
});

// Penanganan Pindah Halaman
const handlePageChange = (url) => {
    if (url) {
        store.fetchSalesReport(url);
        // Scroll ke atas tabel agar user tahu konten berubah
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
};

// Filter client-side (hanya memfilter data yang ada di halaman saat ini)
const filteredItems = computed(() => {
    const data = store.items || [];
    if (!searchQuery.value) return data;

    const query = searchQuery.value.toLowerCase();
    return data.filter((item) => {
        return (
            item?.product?.name?.toLowerCase().includes(query) ||
            item?.product?.sku?.toLowerCase().includes(query)
        );
    });
});

const formatCurrency = (val) => {
    if (!val) return "Rp 0";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(val);
};

const handleExport = async () => {
    if (!store.filters.start_date || !store.filters.end_date) {
        alert("Silakan pilih rentang tanggal untuk ekspor.");
        return;
    }

    try {
        const response = await api.get("/reports/sales/export", {
            params: {
                start_date: store.filters.start_date,
                end_date: store.filters.end_date,
            },
            responseType: "blob",
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;

        const fileName = `Laporan_Penjualan_${store.filters.start_date}_${store.filters.end_date}.xlsx`;
        link.setAttribute("download", fileName);

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.error("Gagal mengunduh file:", error);
    }
};
</script>
