<template>
    <tr class="hover:bg-base/30 transition-colors">
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 rounded-xl bg-base flex items-center justify-center overflow-hidden border border-secondary/10 shrink-0"
                >
                    <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.name"
                        class="w-full h-full object-cover"
                    />
                    <Shirt v-else class="text-primary-light/40" :size="20" />
                </div>
                <div>
                    <p class="font-bold text-primary leading-tight">
                        {{ product.name }}
                    </p>
                    <p
                        class="text-xs text-primary-light uppercase tracking-wider mt-0.5"
                    >
                        {{ product.sku }}
                    </p>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <span
                class="px-3 py-1 rounded-full bg-secondary/10 text-primary-light text-xs font-medium"
            >
                {{ product.category.name }}
            </span>
        </td>
        <td class="px-6 py-4 font-bold text-primary">
            {{ formatCurrency(product.price) }}
        </td>
        <td class="px-6 py-4">
            <div class="flex flex-col">
                <span
                    :class="[
                        'font-bold',
                        product.stock < 10 ? 'text-danger' : 'text-primary',
                    ]"
                >
                    {{ product.stock }} {{ product.unit }}
                </span>
                <span
                    v-if="product.stock < 10"
                    class="text-[10px] text-danger uppercase font-bold"
                    >Stok Tipis</span
                >
            </div>
        </td>
        <td class="px-6 py-4 text-right">
            <div class="flex justify-end gap-2">
                <button
                    @click="$emit('edit', product)"
                    class="p-2 rounded-lg hover:bg-white hover:shadow-sm text-primary-light hover:text-primary transition-all"
                >
                    <Edit3 :size="18" />
                </button>
                <button
                    @click="$emit('delete', product.id)"
                    class="p-2 rounded-lg hover:bg-danger/5 text-primary-light hover:text-danger transition-all"
                >
                    <Trash2 :size="18" />
                </button>
            </div>
        </td>
    </tr>
</template>

<script setup>
import { Shirt, Edit3, Trash2 } from "lucide-vue-next";
const props = defineProps({ product: Object });
defineEmits(["edit", "delete"]);

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};
</script>
