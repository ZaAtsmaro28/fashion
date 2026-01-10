<template>
    <div class="max-w-6xl mx-auto px-8 pb-20 pt-10">
        <div class="flex justify-between items-end mb-10">
            <div>
                <p
                    class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-1"
                >
                    Inventori Sistem
                </p>
                <h2
                    class="text-3xl font-black text-primary uppercase tracking-tighter"
                >
                    Katalog Produk
                </h2>
            </div>

            <button
                @click="router.push({ name: 'products.create' })"
                class="bg-primary text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-2"
            >
                <Plus :size="20" stroke-width="3" />
                Tambah Produk Baru
            </button>
        </div>

        <div
            v-if="loading"
            class="py-20 flex flex-col items-center justify-center"
        >
            <div
                class="w-12 h-12 border-4 border-primary/10 border-t-primary rounded-full animate-spin"
            ></div>
            <p
                class="mt-4 text-primary-light font-bold text-sm uppercase tracking-widest"
            >
                Memuat Koleksi...
            </p>
        </div>

        <div
            v-else-if="products.length === 0"
            class="py-20 text-center bg-base/30 rounded-[3rem] border-2 border-dashed border-secondary/20"
        >
            <p class="text-primary-light font-bold">
                Belum ada produk yang terdaftar.
            </p>
        </div>

        <div
            v-else
            class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8"
        >
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
                @click="goToDetail"
            />
        </div>

        <Pagination
            v-if="meta && products.length > 0"
            :meta="meta"
            @change-page="fetchProducts"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { Plus } from "lucide-vue-next";
import api from "@/api";
import ProductCard from "@/components/products/ProductCard.vue";
import Pagination from "@/components/Pagination.vue"; // Import komponen Pagination

const router = useRouter();
const products = ref([]);
const meta = ref(null);
const loading = ref(true);

// Fungsi Ambil Data
const fetchProducts = async (url = "/products") => {
    if (!url) return;

    loading.value = true;
    try {
        const response = await api.get(url);
        products.value = response.data.data;
        meta.value = response.data.meta;

        // Scroll ke atas setelah pindah halaman
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (err) {
        console.error("Fetch Error:", err);
    } finally {
        loading.value = false;
    }
};

// Navigasi ke Detail
const goToDetail = (id) => {
    router.push({ name: "products.show", params: { id } });
};

onMounted(() => fetchProducts());
</script>
