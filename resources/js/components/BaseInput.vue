<template>
    <div class="flex flex-col gap-1.5 w-full">
        <label
            v-if="label"
            :for="id"
            class="text-sm font-semibold text-gray-700"
        >
            {{ label }}
        </label>

        <div class="relative">
            <div
                v-if="icon"
                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"
            >
                <component :is="icon" :size="19" />
            </div>

            <input
                :id="id"
                :type="type"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                v-bind="$attrs"
                class="block w-full py-2.5 border rounded-xl outline-none transition-all focus:ring-2 focus:border-transparent"
                :class="[
                    icon ? 'pl-11' : 'pl-4',
                    error
                        ? 'border-red-500 focus:ring-red-200 text-red-900 placeholder-red-300'
                        : 'border-gray-300 focus:ring-primary/40 focus:border-primary',
                ]"
            />

            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <slot name="right" />
            </div>
        </div>

        <p v-if="error" class="text-xs font-medium text-red-600 mt-0.5">
            {{ Array.isArray(error) ? error[0] : error }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    modelValue: [String, Number],
    label: String,
    type: { type: String, default: "text" },
    id: String,
    error: [String, Array],
    icon: [Object, Function],
});

defineEmits(["update:modelValue"]);
</script>

<script>
export default { inheritAttrs: false }; // Menghindari atribut menempel di wrapper div
</script>
