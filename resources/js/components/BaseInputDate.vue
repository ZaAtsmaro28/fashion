<template>
    <div class="flex flex-col w-full">
        <label
            class="text-[10px] font-black text-emerald-900/40 uppercase tracking-widest mb-1.5 ml-1"
        >
            {{ label }}
        </label>
        <input
            type="date"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="bg-white border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 focus:border-emerald-500 focus:ring-0 transition-all outline-none"
        />
    </div>
</template>

<script setup>
defineProps(["modelValue", "label"]);
defineEmits(["update:modelValue"]);
</script>
