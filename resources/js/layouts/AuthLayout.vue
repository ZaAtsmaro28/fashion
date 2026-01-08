<template>
    <div
        class="min-h-screen bg-base flex flex-col justify-center items-center p-6 text-primary"
    >
        <div class="w-full max-w-110">
            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-2xl shadow-xl shadow-gray-400/20 mb-4 text-white"
                >
                    <Shirt :size="32" />
                </div>
                <h1 class="text-3xl font-black tracking-tight text-primary">
                    SYAR'I<span class="text-accent">HUB</span>
                </h1>
                <p class="text-primary-light mt-2 font-medium italic">
                    Manajemen Modest Fashion
                </p>
            </div>

            <div
                class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-secondary/20"
            >
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Shirt } from "lucide-vue-next";
</script>
