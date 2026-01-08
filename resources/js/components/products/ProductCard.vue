<template>
    <div
        class="bg-white rounded-[2.5rem] border border-secondary/10 shadow-sm overflow-hidden flex flex-col"
    >
        <div class="relative h-56 bg-base shrink-0">
            <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="product.name"
                class="w-full h-full object-cover"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center text-primary-light/30"
            >
                <Shirt :size="64" stroke-width="1.5" />
            </div>

            <div
                :class="[
                    'absolute top-4 left-4 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider backdrop-blur-sm',
                    product.stock < 10
                        ? 'bg-danger/10 text-danger'
                        : 'bg-primary/10 text-primary',
                ]"
            >
                Stok: {{ product.stock }}
            </div>

            <div
                class="absolute top-4 right-4 px-4 py-1.5 rounded-full bg-secondary/10 text-primary-light text-[10px] font-black uppercase tracking-wider backdrop-blur-sm"
            >
                {{ product.category.name }}
            </div>
        </div>

        <div class="p-6 flex flex-col gap-5 flex-1">
            <div>
                <h4
                    class="font-black text-xl text-primary leading-tight mb-2 line-clamp-2 h-[3.2rem]"
                >
                    {{ product.name }}
                </h4>
                <p class="font-black text-2xl text-primary">
                    {{ formatIDR(product.price) }}
                </p>
            </div>

            <div
                class="bg-base/60 rounded-2xl p-4 flex items-center justify-between"
            >
                <div class="flex flex-col">
                    <span
                        class="text-[10px] text-primary-light/70 font-bold uppercase tracking-widest"
                        >Kode SKU</span
                    >
                    <span
                        class="text-sm font-bold text-primary-light font-mono tracking-tight"
                        >{{ product.sku }}</span
                    >
                </div>
                <div class="flex flex-col items-end">
                    <span
                        class="text-[10px] text-primary-light/70 font-bold uppercase tracking-widest"
                        >Satuan</span
                    >
                    <span
                        class="text-sm font-bold text-primary-light capitalize"
                        >{{ product.unit }}</span
                    >
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-auto pt-2">
                <button
                    @click="$emit('edit', product)"
                    class="flex items-center justify-center gap-2 py-3.5 rounded-2xl font-bold bg-base text-primary hover:bg-primary/10 hover:scale-[1.02] active:scale-100 transition-all"
                >
                    <Edit3 :size="18" stroke-width="2.5" />
                    <span>Edit</span>
                </button>
                <button
                    @click="$emit('delete', product.id)"
                    class="flex items-center justify-center gap-2 py-3.5 rounded-2xl font-bold bg-danger/10 text-danger hover:bg-danger/20 hover:scale-[1.02] active:scale-100 transition-all"
                >
                    <Trash2 :size="18" stroke-width="2.5" />
                    <span>Hapus</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Shirt, Edit3, Trash2 } from "lucide-vue-next";
defineProps({ product: Object });
defineEmits(["edit", "delete"]); // Menambahkan emit untuk aksi

const formatIDR = (val) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(val);
</script>
