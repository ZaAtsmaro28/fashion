<template>
    <Teleport to="body">
        <Transition name="slide-fade">
            <div
                v-if="show"
                :class="[
                    'fixed top-6 right-6 z-200 w-full max-w-sm p-4 rounded-xl shadow-lg border-l-4 flex items-start gap-3',
                    variant === 'success'
                        ? 'bg-white border-green-500 text-green-700'
                        : 'bg-white border-danger text-danger',
                ]"
            >
                <div class="shrink-0 mt-0.5">
                    <svg
                        v-if="variant === 'success'"
                        class="w-5 h-5 text-green-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5 text-danger"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </div>

                <div class="flex-1">
                    <h3 class="text-sm font-bold">{{ title }}</h3>
                    <p class="text-xs mt-1 opacity-80">{{ message }}</p>
                </div>

                <button
                    @click="$emit('close')"
                    class="shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                >
                    <span class="text-lg leading-none">&times;</span>
                </button>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({
    show: Boolean,
    title: String,
    message: String,
    variant: { type: String, default: "success" }, // 'success' | 'danger'
});

defineEmits(["close"]);
</script>

<style scoped>
.slide-fade-enter-active {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.slide-fade-leave-active {
    transition: all 0.2s ease-in;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
</style>
