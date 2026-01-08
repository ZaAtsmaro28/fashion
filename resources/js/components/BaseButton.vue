<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        class="cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed active:scale-95"
        :class="[variantClasses[variant], fullWidth ? 'w-full' : '']"
    >
        <Loader2 v-if="loading" class="animate-spin" :size="20" />

        <component v-if="icon && !loading" :is="icon" :size="20" />

        <slot />
    </button>
</template>

<script setup>
import { Loader2 } from "lucide-vue-next";

const props = defineProps({
    type: { type: String, default: "button" },
    variant: { type: String, default: "primary" }, // primary, success, danger, outline
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    fullWidth: { type: Boolean, default: false },
    icon: { type: Object, default: null }, // Untuk icon dari Lucide
});

const variantClasses = {
    // Menggunakan Sage Green (Primary)
    primary:
        "bg-primary text-white hover:bg-primary-light focus:ring-primary-light shadow-sm",

    // Menggunakan Sandy Brown (Accent) untuk kesan sukses/pencapaian yang hangat
    success:
        "bg-accent text-white hover:opacity-90 focus:ring-accent shadow-sm",

    // Menggunakan Muted Red (Danger)
    danger: "bg-danger text-white hover:opacity-90 focus:ring-danger shadow-sm",

    // Menggunakan Dusty Grey (Secondary) dan Eggshell (Base)
    outline:
        "bg-transparent border border-secondary text-primary hover:bg-base focus:ring-secondary",
};
</script>
