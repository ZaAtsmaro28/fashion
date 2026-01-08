<template>
    <div
        class="flex items-center justify-between mt-8 max-sm:flex-col max-sm:gap-4"
    >
        <p class="text-sm text-primary-light">
            Menampilkan <b>{{ meta.from }}</b> sampai <b>{{ meta.to }}</b> dari
            <b>{{ meta.total }}</b> produk
        </p>

        <div class="flex gap-2">
            <button
                v-for="link in meta.links"
                :key="link.label"
                v-html="link.label"
                @click="$emit('change-page', link.url)"
                :disabled="!link.url || link.active"
                :class="[
                    'px-4 py-2 rounded-xl text-sm font-bold transition-all',
                    link.active
                        ? 'bg-primary text-white shadow-md'
                        : 'bg-white text-primary-light hover:bg-base disabled:opacity-30',
                ]"
            ></button>
        </div>
    </div>
</template>

<script setup>
defineProps({ meta: Object });
defineEmits(["change-page"]);
</script>
