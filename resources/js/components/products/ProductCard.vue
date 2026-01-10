<template>
    <div
        @click="$emit('click', product.id)"
        class="bg-white rounded-3xl md:rounded-[2.5rem] border border-secondary/10 shadow-sm overflow-hidden flex flex-col group transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 cursor-pointer"
    >
        <div class="relative h-64 md:h-56 bg-base shrink-0 overflow-hidden">
            <img
                v-if="product.image_url"
                :src="product.image_url"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center text-primary-light/20 bg-base"
            >
                <Shirt :size="80" stroke-width="1" />
            </div>

            <div
                class="absolute top-5 left-5 px-4 py-1.5 rounded-full bg-white/90 backdrop-blur-md text-primary text-[10px] font-black uppercase tracking-widest shadow-sm"
            >
                Stok: {{ product.stock }}
            </div>
            <div
                class="absolute top-5 right-5 px-4 py-1.5 rounded-full bg-primary/10 backdrop-blur-md text-primary text-[10px] font-black uppercase tracking-widest"
            >
                {{ product.category.name }}
            </div>
        </div>

        <div class="p-6 md:p-6 flex flex-col flex-1">
            <div class="mb-5 md:mb-7">
                <p
                    class="text-[10px] text-primary-light/40 font-black uppercase tracking-[0.2em] mb-1"
                >
                    {{ product.sku }}
                </p>
                <h4
                    class="font-black text-xl md:text-lg text-primary leading-tight mb-2 group-hover:text-primary-light transition-colors"
                >
                    {{ product.name }}
                </h4>
                <p class="font-black text-2xl md:text-xl text-primary">
                    {{ formatIDR(product.price) }}
                </p>
            </div>

            <div class="bg-base/40 rounded-2xl md:rounded-lg p-5 mt-auto">
                <div class="flex justify-between items-center mb-3">
                    <span
                        class="text-[10px] text-primary-light/60 font-black uppercase tracking-[0.2em]"
                    >
                        {{ product.variants?.length || 0 }} Varian Tersedia
                    </span>
                </div>

                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="v in product.variants.slice(0, 3)"
                        :key="v.id"
                        class="bg-white border border-secondary/10 px-3 py-1 rounded-lg text-[10px] font-bold text-primary-light shadow-sm"
                    >
                        {{ v.name }}
                    </span>
                    <span
                        v-if="product.variants.length > 3"
                        class="text-[10px] font-black text-primary/40 self-center"
                    >
                        +{{ product.variants.length - 3 }}
                    </span>
                </div>
            </div>

            <div
                class="mt-4 border-t border-secondary/5 flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            >
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-primary"
                    >Lihat Detail</span
                >
                <div
                    class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center"
                >
                    <ChevronRight :size="16" stroke-width="3" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Shirt, ChevronRight } from "lucide-vue-next";
defineProps({ product: Object });
defineEmits(["click"]);

const formatIDR = (val) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(val);
</script>
