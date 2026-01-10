<template>
    <div class="relative w-full">
        <div
            class="absolute inset-y-0 left-4 flex items-center pointer-events-none"
        >
            <svg
                class="w-4 h-4 text-emerald-600 font-bold"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="3"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
            </svg>
        </div>
        <input
            type="text"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            class="w-full bg-emerald-50/50 border-none rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold placeholder:text-emerald-900/30 focus:ring-2 focus:ring-emerald-500 transition-all outline-none"
        />
    </div>
</template>

<script setup>
defineProps(["modelValue", "placeholder"]);
defineEmits(["update:modelValue"]);
</script>
