<template>
    <div class="max-w-6xl mx-auto px-8 pb-20 pt-10">
        <div class="flex justify-between items-end mb-10">
            <div>
                <p
                    class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-1"
                >
                    Manajemen Data
                </p>
                <h2
                    class="text-3xl font-black text-primary uppercase tracking-tighter"
                >
                    Kategori Produk
                </h2>
            </div>

            <button
                @click="openCreateModal"
                class="bg-primary text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-2"
            >
                <Plus :size="18" stroke-width="3" />
                Tambah Kategori
            </button>
        </div>

        <div
            class="bg-white rounded-4xl border border-gray-200 shadow-sm overflow-hidden"
        >
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-20"
                        >
                            No
                        </th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400"
                        >
                            Nama Kategori
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

                    <tr v-else-if="categories.length === 0">
                        <td
                            colspan="3"
                            class="px-8 py-20 text-center text-gray-400 font-bold uppercase text-xs tracking-widest"
                        >
                            Belum ada data kategori
                        </td>
                    </tr>

                    <tr
                        v-for="(category, index) in categories"
                        :key="category.id"
                        class="hover:bg-gray-50/20 transition-colors"
                    >
                        <td class="px-8 py-5 font-mono text-sm text-gray-400">
                            {{ index + 1 }}
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="font-bold text-primary uppercase tracking-tight"
                                >{{ category.name }}</span
                            >
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-3">
                                <button
                                    @click="openEditModal(category)"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest"
                                >
                                    <Pencil :size="14" stroke-width="3" />
                                    Edit
                                </button>
                                <button
                                    @click="openDeleteModal(category)"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest"
                                >
                                    <Trash2 :size="14" stroke-width="3" />
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <BaseModal
            :show="modal.show"
            :title="modal.isEditing ? 'Update Kategori' : 'Kategori Baru'"
            :confirm-text="submitting ? 'Menyimpan...' : 'Simpan Kategori'"
            @close="closeModal"
            @confirm="handleSave"
        >
            <template #icon>
                <FolderPlus v-if="!modal.isEditing" :size="24" />
                <Pencil v-else :size="24" />
            </template>

            <template #description>
                <div class="mt-4">
                    <label
                        class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1"
                    >
                        Nama Kategori
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Misal: T-Shirt, Jacket, dll"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all font-bold text-primary"
                        @keyup.enter="handleSave"
                    />
                </div>
            </template>
        </BaseModal>

        <BaseModal
            :show="deleteModal.show"
            title="Hapus Kategori"
            confirm-text="Ya, Hapus"
            variant="danger"
            @close="deleteModal.show = false"
            @confirm="handleDelete"
        >
            <template #icon>
                <AlertTriangle :size="24" />
            </template>
            <template #description>
                Apakah Anda yakin ingin menghapus kategori
                <span class="font-bold text-primary"
                    >"{{ deleteModal.data?.name }}"</span
                >? Produk yang menggunakan kategori ini mungkin akan
                terpengaruh.
            </template>
        </BaseModal>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import {
    Plus,
    Pencil,
    Trash2,
    FolderPlus,
    AlertTriangle,
} from "lucide-vue-next";
import api from "@/api";
import BaseModal from "@/components/BaseModal.vue"; // Pastikan path benar

// STATE
const categories = ref([]);
const loading = ref(true);
const submitting = ref(false);

const modal = reactive({
    show: false,
    isEditing: false,
    id: null,
});

const deleteModal = reactive({
    show: false,
    data: null,
});

const form = reactive({
    name: "",
});

// FUNCTIONS
const fetchCategories = async () => {
    try {
        loading.value = true;
        const response = await api.get("/categories");
        categories.value = response.data.data;
    } catch (err) {
        console.error("Fetch error:", err);
    } finally {
        loading.value = false;
    }
};

const openCreateModal = () => {
    modal.isEditing = false;
    modal.id = null;
    form.name = "";
    modal.show = true;
};

const openEditModal = (category) => {
    modal.isEditing = true;
    modal.id = category.id;
    form.name = category.name;
    modal.show = true;
};

const openDeleteModal = (category) => {
    deleteModal.data = category;
    deleteModal.show = true;
};

const closeModal = () => {
    modal.show = false;
    form.name = "";
};

const handleSave = async () => {
    if (!form.name) return;

    try {
        submitting.value = true;
        if (modal.isEditing) {
            await api.put(`/categories/${modal.id}`, form);
        } else {
            await api.post("/categories", form);
        }
        await fetchCategories();
        closeModal();
    } catch (err) {
        alert("Gagal menyimpan data.");
    } finally {
        submitting.value = false;
    }
};

const handleDelete = async () => {
    try {
        await api.delete(`/categories/${deleteModal.data.id}`);
        await fetchCategories();
        deleteModal.show = false;
    } catch (err) {
        alert("Gagal menghapus kategori.");
    }
};

onMounted(() => fetchCategories());
</script>
