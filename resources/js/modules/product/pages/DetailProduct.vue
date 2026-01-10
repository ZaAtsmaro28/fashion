<template>
    <div class="max-w-7xl mx-auto px-8 pb-20 pt-10">
        <div class="mb-10 flex items-center gap-6">
            <button
                @click="useRouterApi.back()"
                class="cursor-pointer w-14 h-14 rounded-2xl bg-white border border-gray-200 flex items-center justify-center text-primary hover:bg-gray-50 transition-all shadow-sm"
            >
                <ArrowLeft :size="24" stroke-width="2.5" />
            </button>
            <div>
                <nav
                    class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-1"
                >
                    <router-link to="/products" class="hover:text-primary"
                        >Produk</router-link
                    >
                    <span>/</span>
                    <span class="text-primary/60">Detail</span>
                </nav>
                <h2
                    class="text-2xl font-black text-primary uppercase tracking-tighter"
                >
                    Informasi Produk
                </h2>
            </div>
        </div>

        <div
            v-if="loading"
            class="py-20 flex flex-col items-center justify-center"
        >
            <div
                class="w-12 h-12 border-4 border-primary/10 border-t-primary rounded-full animate-spin"
            ></div>
            <p
                class="mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest"
            >
                Memuat...
            </p>
        </div>

        <div v-else-if="product" class="grid grid-cols-12 gap-12 items-start">
            <div class="col-span-5">
                <div class="sticky top-10">
                    <div
                        class="aspect-3/4 rounded-4xl overflow-hidden bg-white border border-gray-200 shadow-xl relative"
                    >
                        <img
                            v-if="product.image_url"
                            :src="getImageUrl(product.image_url)"
                            class="w-full h-full object-cover"
                            :alt="product.name"
                        />
                        <div
                            v-else
                            class="w-full h-full flex flex-col items-center justify-center text-gray-200 bg-gray-50"
                        >
                            <Shirt :size="80" stroke-width="1" />
                            <span
                                class="text-xs font-bold mt-2 text-gray-300 uppercase tracking-widest"
                                >No Image</span
                            >
                        </div>

                        <div
                            class="absolute bottom-6 right-6 bg-primary text-white px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg"
                        >
                            Total: {{ totalStock }} Unit
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-7 space-y-6">
                <div class="">
                    <span
                        class="inline-block px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4"
                    >
                        {{ product.category?.name || "Kategori Umum" }}
                    </span>
                    <h1
                        class="text-5xl font-black text-primary tracking-tighter uppercase leading-tight mb-4"
                    >
                        {{ product.name }}
                    </h1>
                    <p class="text-3xl font-bold text-primary/40">
                        {{ formatIDR(product.price) }}
                    </p>
                    <p class="mt-2 text-sm text-primary">
                        {{ product.desc }}
                    </p>
                </div>

                <div>
                    <h3
                        class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4"
                    >
                        Daftar Stok Varian
                    </h3>
                    <div
                        class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden"
                    >
                        <table class="w-full border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50/50 border-b border-gray-100 font-mono"
                                >
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400"
                                    >
                                        SKU
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest text-gray-400"
                                    >
                                        Warna
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest text-gray-400"
                                    >
                                        Size
                                    </th>
                                    <th
                                        class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400"
                                    >
                                        Stok
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr
                                    v-for="v in product.variants"
                                    :key="v.id"
                                    class="hover:bg-gray-50/50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-5 font-mono text-sm font-bold text-primary"
                                    >
                                        {{ v.sku }}
                                    </td>
                                    <td
                                        class="px-6 py-5 text-center text-sm text-gray-600"
                                    >
                                        {{ v.color }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span
                                            class="px-3 py-1 rounded bg-white border border-gray-200 text-xs font-black text-primary"
                                        >
                                            {{ v.size }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-5 text-right font-black"
                                        :class="
                                            v.stock > 5
                                                ? 'text-green-600'
                                                : 'text-red-400'
                                        "
                                    >
                                        {{ v.stock }}
                                    </td>
                                </tr>
                                <tr v-if="!product.variants?.length">
                                    <td
                                        colspan="4"
                                        class="px-6 py-10 text-center text-xs font-bold text-gray-300 uppercase italic"
                                    >
                                        Belum ada data varian
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button
                        @click="goToEdit"
                        class="cursor-pointer px-3 bg-primary text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3"
                    >
                        <Edit3 :size="18" />
                        Edit Produk
                    </button>

                    <button
                        @click="confirmDelete"
                        class="cursor-pointer px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest text-red-500 border border-red-100 bg-red-50/50 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-3"
                    >
                        <Trash2 :size="18" />
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { ArrowLeft, Shirt, Edit3, Trash2 } from "lucide-vue-next";
import api from "@/api";

const route = useRoute();
const useRouterApi = useRouter();
const product = ref(null);
const loading = ref(true);

// Hitung total stok secara otomatis
const totalStock = computed(() => {
    return (
        product.value?.variants?.reduce((acc, curr) => acc + curr.stock, 0) || 0
    );
});

const getImageUrl = (path) => {
    if (!path) return null;
    if (path.startsWith("http")) return path;
    return `${import.meta.env.VITE_API_BASE_URL || ""}/storage/${path}`;
};

const fetchProductDetail = async () => {
    try {
        const response = await api.get(`/products/${route.params.id}`);
        product.value = response.data.data;
    } catch (err) {
        console.error("Gagal mengambil detail:", err);
    } finally {
        loading.value = false;
    }
};

const goToEdit = () => {
    useRouterApi.push({
        name: "products.edit",
        params: { id: product.value.id },
    });
};

const confirmDelete = async () => {
    if (confirm(`Hapus "${product.value.name}"?`)) {
        try {
            await api.delete(`/products/${product.value.id}`);
            useRouterApi.push({ name: "products.index" });
        } catch (err) {
            alert("Gagal menghapus produk.");
        }
    }
};

const formatIDR = (val) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(val || 0);

onMounted(() => fetchProductDetail());
</script>
