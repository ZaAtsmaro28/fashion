<template>
    <div class="space-y-6">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-900">Selamat Datang</h2>
            <p class="text-slate-500 text-sm">
                Silakan masukkan akun Anda untuk akses dashboard.
            </p>
        </div>

        <div
            v-if="errorMessage"
            class="p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm font-medium flex items-start gap-3"
        >
            <AlertCircle class="shrink-0 mt-0.5" :size="18" />
            <span>{{ errorMessage }}</span>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-5">
            <BaseInput
                v-model="form.email"
                label="Email Address"
                type="email"
                placeholder="admin@fashion.com"
                :icon="Mail"
                :error="errors.email"
                required
            />

            <BaseInput
                v-model="form.password"
                label="Password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="••••••••"
                :icon="Lock"
                :error="errors.password"
                required
            >
                <template #right>
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="cursor-pointer text-slate-400 hover:text-primary p-1 rounded-md transition-colors"
                    >
                        <Eye v-if="!showPassword" :size="19" />
                        <EyeOff v-else :size="19" />
                    </button>
                </template>
            </BaseInput>

            <div class="flex items-center justify-end">
                <a
                    href="#"
                    class="text-sm font-semibold text-primary hover:text-accent transition-colors"
                >
                    Lupa Password?
                </a>
            </div>

            <BaseButton
                type="submit"
                fullWidth
                variant="primary"
                :loading="isLoading"
            >
                Masuk ke Sistem
            </BaseButton>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Mail, Lock, Eye, EyeOff, AlertCircle } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import BaseInput from "@/components/BaseInput.vue";
import BaseButton from "@/components/BaseButton.vue";

const authStore = useAuthStore();
const router = useRouter();

const isLoading = ref(false);
const showPassword = ref(false);
const errorMessage = ref("");
const errors = ref({});

const form = reactive({ email: "", password: "" });

const handleSubmit = async () => {
    isLoading.value = true;
    errors.value = {};
    errorMessage.value = "";

    try {
        await authStore.login(form);
        console.log("test1");
        router.push({ name: "dashboard" });
        console.log("test2");
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            errorMessage.value =
                error.response?.data?.message ||
                "Kredensial yang Anda masukkan salah.";
        }
    } finally {
        isLoading.value = false;
    }
};
</script>
