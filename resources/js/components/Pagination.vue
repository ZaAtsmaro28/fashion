<template>
    <div
        class="flex items-center justify-between mt-8 max-sm:flex-col max-sm:gap-4"
    >
        <p class="text-sm text-[#768364]/60">
            Menampilkan <b>{{ meta.from }}</b> sampai <b>{{ meta.to }}</b> dari
            <b>{{ meta.total }}</b> produk
        </p>

        <div class="flex gap-2">
            <button
                v-for="link in meta.links"
                :key="link.label"
                v-html="formatLabel(link.label)"
                @click="$emit('change-page', link.url)"
                :disabled="!link.url || link.active"
                :class="[
                    'px-4 py-2 rounded-xl text-sm font-bold transition-all',
                    link.active
                        ? 'bg-[#768364] text-white shadow-md'
                        : 'bg-white text-[#768364] hover:bg-[#f3f0e7] disabled:opacity-30',
                ]"
            ></button>
        </div>
    </div>
</template>

<script setup>
defineProps({ meta: Object });
defineEmits(["change-page"]);

// Fungsi untuk membersihkan label dari Laravel
const formatLabel = (label) => {
    if (label.includes("pagination.previous") || label.includes("Previous")) {
        return "<< Prev";
    }
    if (label.includes("pagination.next") || label.includes("Next")) {
        return "Next >>";
    }
    return label;
};
</script>
