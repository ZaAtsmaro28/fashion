<template>
    <div class="w-full">
        <div
            class="flex justify-between items-end mb-12 max-sm:flex-col max-sm:items-stretch max-sm:gap-8"
        >
            <div>
                <nav
                    class="text-[10px] uppercase tracking-[0.3em] text-primary-light mb-3 font-black opacity-40"
                >
                    Inventori Sistem
                </nav>
                <h1 class="text-4xl font-black text-primary tracking-tighter">
                    Katalog Produk
                </h1>
            </div>
            <button
                class="bg-primary text-white px-10 py-5 rounded-[2rem] font-bold shadow-2xl shadow-primary/20 hover:translate-y-[-4px] active:scale-95 transition-all max-sm:text-center"
            >
                Tambah Produk Baru
            </button>
        </div>

        <div v-if="loading" class="py-20 text-center">
            <div
                class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary/20 border-t-primary"
            ></div>
            <p class="mt-4 text-primary-light font-bold">
                Mengambil koleksi...
            </p>
        </div>

        <template v-else>
            <div
                class="grid grid-cols-4 gap-8 max-sm:grid-cols-1 max-sm:gap-6 lg:grid-cols-3 xl:grid-cols-4"
            >
                <ProductCard
                    v-for="p in products"
                    :key="p.id"
                    :product="p"
                    @delete="handleDelete"
                />
            </div>

            <div
                v-if="meta"
                class="mt-16 flex justify-between items-center max-sm:flex-col max-sm:gap-8"
            >
                <p
                    class="text-xs font-black text-primary-light/40 uppercase tracking-[0.2em]"
                >
                    Menampilkan {{ meta.from }}-{{ meta.to }} dari
                    {{ meta.total }} Produk
                </p>
                <div class="flex gap-3">
                    <button
                        v-for="link in meta.links"
                        :key="link.label"
                        v-html="link.label"
                        @click="fetchProducts(link.url)"
                        :disabled="!link.url || link.active"
                        :class="[
                            'px-6 py-3 rounded-2xl text-xs font-black transition-all border',
                            link.active
                                ? 'bg-primary border-primary text-white shadow-xl shadow-primary/20'
                                : 'bg-white border-secondary/10 text-primary-light hover:border-primary/50 disabled:opacity-20',
                        ]"
                    ></button>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/api";
// Import modular sesuai kontrak folder
import ProductCard from "@/components/products/ProductCard.vue";

const products = ref([]);
const meta = ref(null);
const loading = ref(true);

const fetchProducts = async (url = "/products") => {
    loading.value = true;
    try {
        const endpoint = url.includes("?") ? url : url;
        const response = await api.get(endpoint);
        products.value = response.data.data;
        meta.value = response.data.meta;
    } catch (err) {
        console.error("Fetch Error:", err);
    } finally {
        loading.value = false;
    }
};

const handleDelete = (id) => {
    console.log("Hapus ID:", id);
};

onMounted(() => fetchProducts());
</script>
