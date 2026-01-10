<template>
    <div class="max-w-6xl mx-auto px-8 pb-20 pt-10">
        <div class="flex justify-between items-end mb-10">
            <div>
                <p
                    class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-1"
                >
                    Pengaturan Sistem
                </p>
                <h2
                    class="text-3xl font-black text-primary uppercase tracking-tighter"
                >
                    Manajemen User
                </h2>
            </div>

            <button
                @click="openCreateModal"
                class="bg-primary text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-2"
            >
                <UserPlus :size="18" stroke-width="3" />
                Tambah User
            </button>
        </div>

        <div
            class="bg-white rounded-4xl border border-gray-200 shadow-sm overflow-hidden"
        >
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400"
                        >
                            Nama & Email
                        </th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400"
                        >
                            Role
                        </th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right"
                        >
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-if="loading">
                        <td colspan="3" class="px-8 py-20 text-center">
                            <div class="flex justify-center">
                                <div
                                    class="w-8 h-8 border-4 border-primary/10 border-t-primary rounded-full animate-spin"
                                ></div>
                            </div>
                        </td>
                    </tr>

                    <tr v-else-if="users.length === 0">
                        <td
                            colspan="3"
                            class="px-8 py-20 text-center text-gray-400 font-bold uppercase text-xs tracking-widest"
                        >
                            Belum ada data user aktif
                        </td>
                    </tr>

                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="hover:bg-gray-50/20 transition-colors"
                    >
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span
                                    class="font-bold text-primary uppercase tracking-tight"
                                    >{{ user.name }}</span
                                >
                                <span class="text-xs text-gray-400">{{
                                    user.email
                                }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                :class="[
                                    'px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest',
                                    user.role === 'admin'
                                        ? 'bg-purple-100 text-purple-600'
                                        : 'bg-orange-100 text-orange-600',
                                ]"
                            >
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <template v-if="user.id !== loggedInUserId">
                                    <button
                                        @click="openEditModal(user)"
                                        class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest"
                                    >
                                        <Pencil :size="14" stroke-width="3" />
                                        Edit
                                    </button>

                                    <button
                                        @click="openDeleteModal(user)"
                                        class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest"
                                    >
                                        <Trash2 :size="14" stroke-width="3" />
                                        Hapus
                                    </button>
                                </template>

                                <div
                                    v-else
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 font-black uppercase text-[10px] tracking-[0.15em]"
                                >
                                    <div
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"
                                    ></div>
                                    Akun Anda
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <BaseModal
            :show="modal.show"
            :title="modal.isEditing ? 'Update User' : 'User Baru'"
            :confirm-text="submitting ? 'Menyimpan...' : 'Simpan User'"
            @close="closeModal"
            @confirm="handleSave"
        >
            <template #icon>
                <UserPlus v-if="!modal.isEditing" :size="24" />
                <UserCog v-else :size="24" />
            </template>

            <template #description>
                <div class="mt-4 space-y-4 text-left">
                    <div>
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1"
                            >Nama Lengkap</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-100 focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all font-bold text-primary text-sm"
                            placeholder="John Doe"
                        />
                    </div>

                    <div>
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1"
                            >Email Address</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-100 focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all font-bold text-primary text-sm"
                            placeholder="email@fashion.com"
                        />
                    </div>

                    <div>
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1"
                            >Role Akses</label
                        >
                        <select
                            v-model="form.role"
                            class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-100 focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all font-bold text-primary text-sm appearance-none"
                        >
                            <option value="admin">Admin</option>
                            <option value="gudang">Gudang</option>
                        </select>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <p
                            v-if="modal.isEditing"
                            class="text-[9px] text-orange-500 font-bold uppercase mb-3 italic"
                        >
                            * Kosongkan password jika tidak ingin diubah
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1"
                                    >Password</label
                                >
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-100 focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all font-bold text-primary text-sm"
                                    placeholder="••••••"
                                />
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1"
                                    >Konfirmasi</label
                                >
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-100 focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all font-bold text-primary text-sm"
                                    placeholder="••••••"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </BaseModal>

        <BaseModal
            :show="deleteModal.show"
            title="Hapus Akun User"
            confirm-text="Ya, Hapus User"
            variant="danger"
            @close="deleteModal.show = false"
            @confirm="handleDelete"
        >
            <template #icon>
                <UserX :size="24" />
            </template>
            <template #description>
                Apakah Anda yakin ingin menghapus user
                <span class="font-bold text-primary"
                    >"{{ deleteModal.data?.name }}"</span
                >? Akses user ini akan dicabut secara permanen.
            </template>
        </BaseModal>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import { UserPlus, UserCog, UserX, Pencil, Trash2 } from "lucide-vue-next";
import api from "@/api";
import BaseModal from "@/components/BaseModal.vue";

const users = ref([]);
const loading = ref(true);
const submitting = ref(false);
const loggedInUserId = ref(null);

const modal = reactive({ show: false, isEditing: false, id: null });
const deleteModal = reactive({ show: false, data: null });
const form = reactive({
    name: "",
    email: "",
    role: "gudang",
    password: "",
    password_confirmation: "",
});

const fetchUsers = async () => {
    try {
        loading.value = true;
        const response = await api.get("/users");
        users.value = response.data.data;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
};

const openCreateModal = () => {
    modal.isEditing = false;
    modal.id = null;
    Object.assign(form, {
        name: "",
        email: "",
        role: "gudang",
        password: "",
        password_confirmation: "",
    });
    modal.show = true;
};

const openEditModal = (user) => {
    modal.isEditing = true;
    modal.id = user.id;
    Object.assign(form, {
        name: user.name,
        email: user.email,
        role: user.role,
        password: "",
        password_confirmation: "",
    });
    modal.show = true;
};

const openDeleteModal = (user) => {
    deleteModal.data = user;
    deleteModal.show = true;
};

const closeModal = () => {
    modal.show = false;
};

const handleSave = async () => {
    if (!form.name || !form.email) return alert("Nama dan Email wajib diisi");
    if (!modal.isEditing && !form.password)
        return alert("Password wajib diisi");
    if (form.password !== form.password_confirmation)
        return alert("Konfirmasi password tidak cocok");

    try {
        submitting.value = true;
        const payload = { ...form };
        if (modal.isEditing && !payload.password) {
            delete payload.password;
            delete payload.password_confirmation;
        }
        if (modal.isEditing) {
            await api.put(`/users/${modal.id}`, payload);
        } else {
            await api.post("/users", payload);
        }
        await fetchUsers();
        closeModal();
    } catch (err) {
        alert("Gagal menyimpan data.");
    } finally {
        submitting.value = false;
    }
};

const handleDelete = async () => {
    try {
        await api.delete(`/users/${deleteModal.data.id}`);
        await fetchUsers();
        deleteModal.show = false;
    } catch (err) {
        alert(err.response?.data?.message || "Gagal menghapus.");
    }
};

onMounted(() => {
    fetchUsers();
    const storedUser = localStorage.getItem("user");
    if (storedUser) {
        const user = JSON.parse(storedUser);
        loggedInUserId.value = user.id;
    }
});
</script>
