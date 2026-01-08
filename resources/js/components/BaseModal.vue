<template>
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="show"
                class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm"
                @click.self="$emit('close')"
            >
                <Transition name="zoom">
                    <div
                        v-if="show"
                        class="bg-white w-full max-w-sm lg:max-w-md rounded-4xl shadow-2xl overflow-hidden"
                    >
                        <div class="p-6 flex items-start gap-4">
                            <div
                                v-if="$slots.icon"
                                class="shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center bg-danger/10 text-danger"
                            >
                                <slot name="icon" />
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="text-xl font-bold text-primary leading-tight"
                                >
                                    {{ title }}
                                </h3>
                                <p class="text-primary-light text-sm mt-1">
                                    <slot name="description" />
                                </p>
                            </div>
                        </div>

                        <div
                            class="p-6 bg-base/30 flex flex-col lg:flex-row gap-3"
                        >
                            <button
                                @click="$emit('close')"
                                class="cursor-pointer w-full lg:flex-1 py-3 px-4 rounded-2xl font-bold text-primary-light hover:bg-white transition-all order-2 lg:order-1"
                            >
                                {{ cancelText }}
                            </button>
                            <button
                                @click="$emit('confirm')"
                                :class="[
                                    'cursor-pointer w-full lg:flex-1 py-3 px-4 rounded-2xl font-bold text-white shadow-lg transition-all order-1 lg:order-2',
                                    variant === 'danger'
                                        ? 'bg-danger shadow-danger/20 hover:bg-danger/90'
                                        : 'bg-primary shadow-primary/20 hover:bg-primary/90',
                                ]"
                            >
                                {{ confirmText }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({
    show: Boolean,
    title: String,
    confirmText: { type: String, default: "Lanjutkan" },
    cancelText: { type: String, default: "Batal" },
    variant: { type: String, default: "primary" }, // 'primary' | 'danger'
});

defineEmits(["close", "confirm"]);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.zoom-enter-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.zoom-leave-active {
    transition: all 0.2s ease-in;
}
.zoom-enter-from {
    opacity: 0;
    transform: scale(0.9) translateY(10px);
}
.zoom-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
