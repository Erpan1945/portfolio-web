<template>
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="w-64 bg-gray-800 text-white min-h-screen p-4 hidden md:block">
            <h1 class="text-2xl font-bold mb-8">Admin Panel</h1>
            <nav class="space-y-2">
                <button @click="activeTab = 'projects'" :class="{'bg-gray-700': activeTab === 'projects'}" class="w-full text-left px-4 py-2 rounded">Projects</button>
                <button @click="activeTab = 'cv'" :class="{'bg-gray-700': activeTab === 'cv'}" class="w-full text-left px-4 py-2 rounded">CV Manager</button>
                <button @click="handleLogout" class="w-full text-left px-4 py-2 rounded text-red-400 hover:bg-gray-700 mt-8">Logout</button>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            
            <div v-if="activeTab === 'projects'">
                <h2 class="text-2xl font-bold mb-6 flex justify-between items-center">
                    Manajemen Project
                    <span v-if="isEditing" class="text-sm bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full">Mode Edit</span>
                </h2>

                <div class="bg-white p-6 rounded shadow mb-8 border-l-4" :class="isEditing ? 'border-yellow-500' : 'border-blue-500'">
                    <h3 class="text-lg font-bold mb-4">{{ isEditing ? 'Edit Project' : 'Tambah Project Baru' }}</h3>
                    
                    <form @submit.prevent="submitProject" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-1">Judul Project</label>
                            <input v-model="form.title" type="text" class="w-full border p-2 rounded" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold mb-1">Deskripsi</label>
                            <textarea v-model="form.description" class="w-full border p-2 rounded h-24" required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-1">Tech Stack (Pisahkan koma)</label>
                                <input v-model="form.tech_stack" type="text" placeholder="Laravel, Vue, Tailwind" class="w-full border p-2 rounded">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-1">Link Project (URL)</label>
                                <input v-model="form.link" type="url" placeholder="https://..." class="w-full border p-2 rounded">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1">Gambar {{ isEditing ? '(Biarkan kosong jika tidak ganti)' : '' }}</label>
                            <input @change="handleFileUpload" type="file" class="w-full border p-2 rounded">
                            <p v-if="isEditing && form.existingImage" class="text-xs text-gray-500 mt-1">Gambar saat ini: {{ form.existingImage }}</p>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 rounded text-white font-bold" :class="isEditing ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-600 hover:bg-blue-700'">
                                {{ isEditing ? 'Update Project' : 'Simpan Project' }}
                            </button>
                            
                            <button v-if="isEditing" @click="cancelEdit" type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="project in projects" :key="project.id" class="bg-white p-4 rounded shadow flex flex-col">
                        <img :src="project.image" class="w-full h-40 object-cover rounded mb-4 bg-gray-200">
                        <h3 class="font-bold text-lg">{{ project.title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ project.description }}</p>
                        
                        <div class="mt-auto flex gap-2">
                            <button @click="editProject(project)" class="flex-1 bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 transition">
                                Edit
                            </button>
                            <button @click="deleteProject(project.id)" class="flex-1 bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'cv'">
               <p>Fitur CV ada di sini (sesuai kode sebelumnya)</p>
            </div>

        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const activeTab = ref('projects');
const projects = ref([]);

// State untuk Form Project
const isEditing = ref(false); // Penanda apakah sedang mode edit
const editId = ref(null);     // ID project yang sedang diedit

const form = ref({
    title: '',
    description: '',
    tech_stack: '',
    link: '',
    image: null,
    existingImage: '' // Hanya untuk preview nama file lama
});

// Load Projects
const loadProjects = async () => {
    try {
        const res = await axios.get('/api/projects');
        projects.value = res.data;
    } catch (e) { console.error(e); }
};

// Handle File Select
const handleFileUpload = (e) => {
    form.value.image = e.target.files[0];
};

// 1. FUNGSI UNTUK MEMULAI EDIT (Dipanggil saat tombol Edit diklik)
const editProject = (project) => {
    isEditing.value = true;
    editId.value = project.id;
    
    // Isi form dengan data project yang dipilih
    form.value.title = project.title;
    form.value.description = project.description;
    form.value.link = project.link;
    form.value.existingImage = project.image; // Simpan URL gambar lama
    form.value.image = null; // Reset input file (karena user belum tentu ganti gambar)

    // Parse Tech Stack dari JSON string ke text biasa (koma separated)
    // Supaya enak diedit di input text
    try {
        const stackArray = JSON.parse(project.tech_stack);
        form.value.tech_stack = Array.isArray(stackArray) ? stackArray.join(', ') : project.tech_stack;
    } catch (e) {
        form.value.tech_stack = project.tech_stack;
    }

    // Scroll ke atas biar kelihatan formnya
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// 2. FUNGSI BATAL EDIT
const cancelEdit = () => {
    isEditing.value = false;
    editId.value = null;
    resetForm();
};

// 3. FUNGSI SUBMIT (Bisa Simpan Baru atau Update)
const submitProject = async () => {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('tech_stack', form.value.tech_stack);
    if(form.value.link) formData.append('link', form.value.link);
    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    try {
        const token = localStorage.getItem('token');
        const config = { headers: { Authorization: `Bearer ${token}` } };

        if (isEditing.value) {
            // --- LOGIKA UPDATE ---
            // Kita pakai POST tapi tambahkan _method: PUT (Trik Laravel untuk Upload File)
            formData.append('_method', 'PUT'); 
            await axios.post(`/api/projects/${editId.value}`, formData, config);
            alert('Project berhasil diupdate!');
        } else {
            // --- LOGIKA CREATE BARU ---
            await axios.post('/api/projects', formData, config);
            alert('Project berhasil disimpan!');
        }

        loadProjects(); // Refresh list
        cancelEdit();   // Reset mode ke awal
        
    } catch (error) {
        console.error(error);
        alert('Gagal menyimpan project.');
    }
};

const deleteProject = async (id) => {
    if(!confirm('Yakin hapus project ini?')) return;
    try {
        const token = localStorage.getItem('token');
        await axios.delete(`/api/projects/${id}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        loadProjects();
    } catch (e) { alert('Gagal hapus'); }
};

const handleLogout = async () => {
    try {
        const token = localStorage.getItem('token');
        await axios.post('/api/logout', {}, {
             headers: { Authorization: `Bearer ${token}` }
        });
        localStorage.removeItem('token');
        router.push('/secretdoor');
    } catch (e) {
        localStorage.removeItem('token');
        router.push('/secretdoor');
    }
};

onMounted(() => {
    loadProjects();
});
</script>