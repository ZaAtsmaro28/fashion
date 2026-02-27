<template>
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="show"
                class="fixed inset-0 z-90 flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm overflow-y-auto"
                @click.self="$emit('close')"
            >
                <Transition name="zoom">
                    <div
                        v-if="show"
                        class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden my-8"
                    >
                        <div
                            class="p-6 border-b border-base/50 flex justify-between items-center"
                        >
                            <h3 class="text-xl font-bold text-primary">
                                Pengaturan Profil
                            </h3>
                            <button
                                @click="$emit('close')"
                                class="text-primary-light hover:text-danger transition-colors cursor-pointer"
                            >
                                <span class="text-2xl font-bold leading-none"
                                    >&times;</span
                                >
                            </button>
                        </div>

                        <div class="p-6 space-y-8">
                            <section>
                                <h4
                                    class="text-sm font-bold text-primary-light uppercase tracking-wider mb-4"
                                >
                                    Informasi Pribadi
                                </h4>
                                <form
                                    @submit.prevent="submitProfile"
                                    class="space-y-4"
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-primary mb-1"
                                            >Nama Lengkap</label
                                        >
                                        <input
                                            v-model="profileForm.name"
                                            type="text"
                                            required
                                            class="w-full px-4 py-2 rounded-xl border border-secondary/30 focus:outline-none focus:ring-2 focus:ring-primary/50 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-primary mb-1"
                                            >Email</label
                                        >
                                        <input
                                            v-model="profileForm.email"
                                            type="email"
                                            required
                                            class="w-full px-4 py-2 rounded-xl border border-secondary/30 focus:outline-none focus:ring-2 focus:ring-primary/50 text-primary"
                                        />
                                    </div>
                                    <div class="flex justify-end">
                                        <button
                                            type="submit"
                                            :disabled="
                                                !isProfileChanged ||
                                                isLoadingProfile
                                            "
                                            :class="[
                                                'px-5 py-2 rounded-xl text-sm font-bold transition-all duration-300',
                                                isProfileChanged &&
                                                !isLoadingProfile
                                                    ? 'bg-primary text-white shadow-md hover:bg-primary/90 cursor-pointer'
                                                    : 'bg-transparent border-2 border-secondary/20 text-secondary/60 cursor-not-allowed',
                                            ]"
                                        >
                                            {{
                                                isLoadingProfile
                                                    ? "Menyimpan..."
                                                    : "Simpan Profil"
                                            }}
                                        </button>
                                    </div>
                                </form>
                            </section>

                            <hr class="border-secondary/20" />

                            <section>
                                <h4
                                    class="text-sm font-bold text-primary-light uppercase tracking-wider mb-4"
                                >
                                    Keamanan
                                </h4>
                                <form
                                    @submit.prevent="submitPassword"
                                    class="space-y-4"
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-primary mb-1"
                                            >Password Saat Ini</label
                                        >
                                        <input
                                            v-model="
                                                passwordForm.current_password
                                            "
                                            type="password"
                                            required
                                            class="w-full px-4 py-2 rounded-xl border border-secondary/30 focus:outline-none focus:ring-2 focus:ring-primary/50 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-primary mb-1"
                                            >Password Baru</label
                                        >
                                        <input
                                            v-model="passwordForm.new_password"
                                            type="password"
                                            required
                                            class="w-full px-4 py-2 rounded-xl border border-secondary/30 focus:outline-none focus:ring-2 focus:ring-primary/50 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-primary mb-1"
                                            >Konfirmasi Password Baru</label
                                        >
                                        <input
                                            v-model="
                                                passwordForm.new_password_confirmation
                                            "
                                            type="password"
                                            required
                                            class="w-full px-4 py-2 rounded-xl border border-secondary/30 focus:outline-none focus:ring-2 focus:ring-primary/50 text-primary"
                                        />
                                    </div>
                                    <div class="flex justify-end">
                                        <button
                                            type="submit"
                                            :disabled="
                                                !isPasswordValid ||
                                                isLoadingPassword
                                            "
                                            :class="[
                                                'px-5 py-2 rounded-xl text-sm font-bold transition-all duration-300',
                                                isPasswordValid &&
                                                !isLoadingPassword
                                                    ? 'bg-primary text-white shadow-md hover:bg-primary/90 cursor-pointer'
                                                    : 'bg-transparent border-2 border-secondary/20 text-secondary/60 cursor-not-allowed',
                                            ]"
                                        >
                                            {{
                                                isLoadingPassword
                                                    ? "Memperbarui..."
                                                    : "Perbarui Password"
                                            }}
                                        </button>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>

    <BaseAlert
        :show="alertState.show"
        :title="alertState.title"
        :message="alertState.message"
        :variant="alertState.variant"
        @close="alertState.show = false"
    />
</template>

<script setup>
import { ref, reactive, watch, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import BaseAlert from "@/components/BaseAlert.vue";

const props = defineProps({ show: Boolean });
const emit = defineEmits(["close"]);
const authStore = useAuthStore();

const isLoadingProfile = ref(false);
const isLoadingPassword = ref(false);

const originalProfile = reactive({ name: "", email: "" });
const profileForm = reactive({ name: "", email: "" });
const passwordForm = reactive({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
});

const alertState = reactive({
    show: false,
    title: "",
    message: "",
    variant: "success",
});

const showAlert = (title, message, variant = "success") => {
    alertState.title = title;
    alertState.message = message;
    alertState.variant = variant;
    alertState.show = true;
    setTimeout(() => {
        alertState.show = false;
    }, 3000); // Auto-close dalam 3 detik
};

watch(
    () => props.show,
    (isOpen) => {
        if (isOpen && authStore.user) {
            profileForm.name = authStore.user.name;
            profileForm.email = authStore.user.email;
            originalProfile.name = authStore.user.name;
            originalProfile.email = authStore.user.email;

            passwordForm.current_password = "";
            passwordForm.new_password = "";
            passwordForm.new_password_confirmation = "";
        }
    },
);

const isProfileChanged = computed(() => {
    const isDifferent =
        profileForm.name !== originalProfile.name ||
        profileForm.email !== originalProfile.email;
    const isNotEmpty =
        profileForm.name.trim() !== "" && profileForm.email.trim() !== "";
    return isDifferent && isNotEmpty;
});

const isPasswordValid = computed(() => {
    const isCurrentFilled = passwordForm.current_password.length > 0;
    const isNewFilled = passwordForm.new_password.length > 0;
    const isMatch =
        passwordForm.new_password === passwordForm.new_password_confirmation;
    const isDifferentFromCurrent =
        passwordForm.current_password !== passwordForm.new_password;

    return isCurrentFilled && isNewFilled && isMatch && isDifferentFromCurrent;
});

const submitProfile = async () => {
    if (!isProfileChanged.value) return;

    isLoadingProfile.value = true;
    try {
        await authStore.updateProfile({
            name: profileForm.name,
            email: profileForm.email,
        });

        originalProfile.name = profileForm.name;
        originalProfile.email = profileForm.email;

        showAlert(
            "Berhasil",
            "Data profil Anda berhasil diperbarui.",
            "success",
        );
    } catch (error) {
        const errMsg =
            error.response?.data?.message ||
            "Terjadi kesalahan saat menyimpan profil.";
        showAlert("Gagal", errMsg, "danger");
    } finally {
        isLoadingProfile.value = false;
    }
};

const submitPassword = async () => {
    if (!isPasswordValid.value) return;

    isLoadingPassword.value = true;
    try {
        await authStore.updatePassword({
            current_password: passwordForm.current_password,
            new_password: passwordForm.new_password,
            new_password_confirmation: passwordForm.new_password_confirmation,
        });

        showAlert("Berhasil", "Password Anda berhasil diperbarui.", "success");

        passwordForm.current_password = "";
        passwordForm.new_password = "";
        passwordForm.new_password_confirmation = "";
    } catch (error) {
        const errMsg =
            error.response?.data?.message ||
            "Gagal memperbarui password. Pastikan password saat ini benar.";
        showAlert("Gagal", errMsg, "danger");
    } finally {
        isLoadingPassword.value = false;
    }
};
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
