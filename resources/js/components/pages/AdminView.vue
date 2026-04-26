<template>
    <div class="min-h-screen bg-gray-100 flex font-sans">
        <aside class="w-64 bg-gray-900 text-white min-h-screen p-6 hidden md:block fixed h-full shadow-xl z-10">
            <h1 class="text-3xl font-black mb-10 tracking-tighter">Admin<span class="text-purple-500">.</span></h1>
            <nav class="space-y-4">
                <button @click="activeTab = 'projects'" :class="{'bg-purple-600 text-white shadow-lg': activeTab === 'projects', 'text-gray-400 hover:text-white hover:bg-gray-800': activeTab !== 'projects'}" class="w-full text-left px-4 py-3 rounded-xl font-bold transition flex items-center gap-3">
                    Projects
                </button>
                <button @click="activeTab = 'certificates'" :class="{'bg-purple-600 text-white shadow-lg': activeTab === 'certificates', 'text-gray-400 hover:text-white hover:bg-gray-800': activeTab !== 'certificates'}" class="w-full text-left px-4 py-3 rounded-xl font-bold transition flex items-center gap-3">
                    Certificates
                </button>
                <button @click="activeTab = 'settings'" :class="{'bg-purple-600 text-white shadow-lg': activeTab === 'settings', 'text-gray-400 hover:text-white hover:bg-gray-800': activeTab !== 'settings'}" class="w-full text-left px-4 py-3 rounded-xl font-bold transition flex items-center gap-3">
                    Settings
                </button>
                
                <div class="pt-8 mt-8 border-t border-gray-800">
                    <button @click="handleLogout" class="w-full text-left px-4 py-3 rounded-xl text-red-400 hover:bg-red-900/20 font-bold transition flex items-center gap-3">
                        Log Out
                    </button>
                </div>
            </nav>
        </aside>

        <main class="flex-1 p-8 md:ml-64 bg-gray-50 min-h-screen">
            
            <div v-if="activeTab === 'projects'" class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold mb-8 text-gray-800 flex justify-between items-center">
                    Manajemen Project
                    <span v-if="isEditing" class="text-sm bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full border border-yellow-200 shadow-sm">Mode Edit</span>
                </h2>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 mb-10 transition-all hover:shadow-md">
                    <h3 class="text-xl font-bold mb-6 text-gray-700 border-b pb-4">{{ isEditing ? 'Edit Project' : '✨ Tambah Project Baru' }}</h3>
                    
                    <form @submit.prevent="submitProject" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Judul Project</label>
                                <input v-model="form.title" type="text" class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition" placeholder="Nama project..." required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Deskripsi</label>
                                <textarea v-model="form.description" class="w-full border bg-gray-50 p-3 rounded-xl h-32 focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition" placeholder="Deskripsi singkat..." required></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold mb-2 text-gray-600">Tech Stack (Pisahkan koma)</label>
                                    <input v-model="form.tech_stack" type="text" placeholder="Laravel, Vue, Tailwind" class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2 text-gray-600">Link Project (URL)</label>
                                    <input v-model="form.link" type="url" placeholder="https://..." class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Gambar {{ isEditing ? '(Biarkan kosong jika tidak ganti)' : '' }}</label>
                                <div class="flex items-center gap-4">
                                    <input @change="handleFileUpload" type="file" class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-purple-50 file:text-purple-700
                                      hover:file:bg-purple-100 transition cursor-pointer
                                    "/>
                                </div>
                                <p v-if="isEditing && form.existingImage" class="text-xs text-gray-500 mt-2 bg-gray-100 inline-block px-2 py-1 rounded">File saat ini: {{ form.existingImage }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t">
                            <button type="submit" class="px-6 py-3 rounded-xl text-white font-bold transition shadow-lg hover:-translate-y-1" :class="isEditing ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-black hover:bg-gray-800'">
                                {{ isEditing ? 'Update Project' : 'Simpan Project' }}
                            </button>
                            
                            <button v-if="isEditing" @click="cancelEdit" type="button" class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="project in projects" :key="project.id" class="bg-white p-5 rounded-3xl shadow-sm hover:shadow-xl transition border border-gray-100 flex flex-col group">
                        <div class="relative h-48 mb-4 overflow-hidden rounded-2xl bg-gray-100">
                             <img :src="project.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="font-bold text-xl mb-2 text-gray-800">{{ project.title }}</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">{{ project.description }}</p>
                        
                        <div class="mt-auto flex gap-3">
                            <button @click="editProject(project)" class="flex-1 bg-yellow-50 text-yellow-600 py-2.5 rounded-xl font-bold hover:bg-yellow-100 transition text-sm">
                                Edit
                            </button>
                            <button @click="deleteProject(project.id)" class="flex-1 bg-red-50 text-red-600 py-2.5 rounded-xl font-bold hover:bg-red-100 transition text-sm">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'certificates'" class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold mb-8 text-gray-800 flex justify-between items-center">
                    Manajemen Sertifikat
                    <span v-if="isEditingCert" class="text-sm bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full border border-yellow-200 shadow-sm">Mode Edit</span>
                </h2>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 mb-10 transition-all hover:shadow-md">
                    <h3 class="text-xl font-bold mb-6 text-gray-700 border-b pb-4">{{ isEditingCert ? 'Edit Sertifikat' : 'Tambah Sertifikat Baru' }}</h3>
                    
                    <form @submit.prevent="submitCertificate" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Nama Sertifikat</label>
                                <input v-model="certForm.name" type="text" class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Penerbit (Issuer)</label>
                                <input v-model="certForm.issuer" type="text" class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Tanggal Terbit</label>
                                <input v-model="certForm.issued_date" type="date" class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Link Kredensial</label>
                                <input v-model="certForm.credential_url" type="url" class="w-full border bg-gray-50 p-3 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white outline-none transition" placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 text-gray-600">Gambar (Opsional)</label>
                                <input @change="handleCertFile" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition cursor-pointer"/>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t">
                            <button type="submit" class="px-6 py-3 rounded-xl text-white font-bold transition shadow-lg hover:-translate-y-1" :class="isEditingCert ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-black hover:bg-gray-800'">
                                {{ isEditingCert ? 'Update Sertifikat' : 'Simpan Sertifikat' }}
                            </button>
                            <button v-if="isEditingCert" @click="cancelEditCert" type="button" class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-if="certificates.length === 0" class="col-span-2 text-center py-10 text-gray-400">
                        Tidak ada sertifikat ditemukan. Coba refresh atau tambah baru.
                    </div>

                    <div v-for="cert in certificates" :key="cert.id" class="bg-white p-5 rounded-3xl shadow-sm hover:shadow-md transition border border-gray-100 flex items-start gap-5 group">
                        <div class="w-20 h-20 rounded-2xl bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                             <img :src="cert.image || 'https://placehold.co/100'" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 truncate">{{ cert.name }}</h3>
                            <p class="text-sm text-purple-600 font-medium mb-1">{{ cert.issuer }}</p>
                            <p class="text-xs text-gray-400 mb-4">{{ cert.issued_date }}</p>
                            
                            <div class="flex gap-3">
                                <button @click="editCert(cert)" class="text-xs font-bold text-gray-500 hover:text-blue-600 bg-gray-50 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">Edit</button>
                                <button @click="deleteCert(cert.id)" class="text-xs font-bold text-gray-500 hover:text-red-600 bg-gray-50 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'settings'" class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold mb-8 text-gray-800 flex justify-between items-center">
                    Pengaturan Website
                </h2>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 mb-10 transition-all hover:shadow-md">
                    <h3 class="text-xl font-bold mb-6 text-gray-700 border-b pb-4">Update Foto "About Me"</h3>
                    
                    <form @submit.prevent="submitPhoto" class="space-y-4 max-w-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto Baru (Max 2MB)</label>
                            <input type="file" @change="handleFileChange" accept="image/jpeg, image/png, image/webp"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        </div>
                        
                        <button type="submit" :disabled="isUploading"
                                class="px-6 py-3 mt-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold transition shadow-lg hover:-translate-y-1 disabled:opacity-50">
                            {{ isUploading ? 'Mengupload...' : 'Simpan Foto Baru' }}
                        </button>
                    </form>
                </div>
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

// ======================= PROJECTS LOGIC =======================
const projects = ref([]);
const isEditing = ref(false);
const editId = ref(null);

// --- LOGIKA UNTUK FITUR UPLOAD FOTO ---
const photoFile = ref(null);
const isUploading = ref(false);

const handleFileChange = (event) => {
    photoFile.value = event.target.files[0];
};

const submitPhoto = async () => {
    if (!photoFile.value) {
        alert("Pilih foto terlebih dahulu!");
        return;
    }

    isUploading.value = true;
    
    const formData = new FormData();
    formData.append('photo', photoFile.value);

    try {
        const token = localStorage.getItem('token'); 

        const response = await axios.post('/api/settings/photo', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token}` 
            }
        });

        alert("Berhasil! " + response.data.message);
        photoFile.value = null; // Reset input file
        
    } catch (error) {
        console.error("Gagal upload:", error);
        alert("Gagal mengupload foto. Pastikan ukuran di bawah 2MB.");
    } finally {
        isUploading.value = false;
    }
};

const form = ref({
    title: '', description: '', tech_stack: '', link: '', image: null, existingImage: ''
});


// SAFETY CHECK: Pastikan data project selalu array
const loadProjects = async () => {
    try {
        const res = await axios.get('/api/projects');
        if(Array.isArray(res.data)) {
            projects.value = res.data;
        } else {
            console.error("Data Project bukan array:", res.data);
            projects.value = [];
        }
    } catch (e) { console.error(e); }
};

const handleFileUpload = (e) => {
    form.value.image = e.target.files[0];
};

const editProject = (project) => {
    isEditing.value = true;
    editId.value = project.id;
    form.value.title = project.title;
    form.value.description = project.description;
    form.value.link = project.link;
    form.value.existingImage = project.image;
    form.value.image = null;
    try {
        const stackArray = JSON.parse(project.tech_stack);
        form.value.tech_stack = Array.isArray(stackArray) ? stackArray.join(', ') : project.tech_stack;
    } catch (e) { form.value.tech_stack = project.tech_stack; }
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
    isEditing.value = false;
    editId.value = null;
    form.value = { title: '', description: '', tech_stack: '', link: '', image: null, existingImage: '' };
};

const submitProject = async () => {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('tech_stack', form.value.tech_stack);
    if(form.value.link) formData.append('link', form.value.link);
    if(form.value.image) formData.append('image', form.value.image);

    try {
        const token = localStorage.getItem('token');
        const config = { headers: { Authorization: `Bearer ${token}` } };
        if (isEditing.value) {
            formData.append('_method', 'PUT'); 
            await axios.post(`/api/projects/${editId.value}`, formData, config);
        } else {
            await axios.post('/api/projects', formData, config);
        }
        loadProjects();
        cancelEdit();
        alert('Sukses!');
    } catch (error) { alert('Gagal menyimpan project.'); }
};

const deleteProject = async (id) => {
    if(!confirm('Yakin hapus project ini?')) return;
    try {
        const token = localStorage.getItem('token');
        await axios.delete(`/api/projects/${id}`, { headers: { Authorization: `Bearer ${token}` } });
        loadProjects();
    } catch (e) { alert('Gagal hapus'); }
};


// ======================= CERTIFICATES LOGIC =======================
const certificates = ref([]);
const isEditingCert = ref(false);
const editCertId = ref(null);
const certForm = ref({ name: '', issuer: '', issued_date: '', credential_url: '', image: null, existingImage: '' });

const loadCertificates = async () => {
    try {
        const res = await axios.get('/api/certificates');
        if(Array.isArray(res.data)) {
             certificates.value = res.data;
             // Debugging: Cek data di console
             console.log("Data Certificates:", res.data);
        } else {
             console.error("Data Certificate bukan array (Mungkin Error HTML):", res.data);
             certificates.value = [];
        }
    } catch (e) { console.error("Error load certificates:", e); }
};

const handleCertFile = (e) => {
    certForm.value.image = e.target.files[0];
};

const editCert = (cert) => {
    isEditingCert.value = true;
    editCertId.value = cert.id;
    // PERBAIKAN: Gunakan nama field yang benar
    certForm.value.name = cert.name;
    certForm.value.issuer = cert.issuer;
    certForm.value.issued_date = cert.issued_date;
    certForm.value.credential_url = cert.credential_url;
    certForm.value.existingImage = cert.image;
    certForm.value.image = null;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEditCert = () => {
    isEditingCert.value = false;
    editCertId.value = null;
    certForm.value = { name: '', issuer: '', issued_date: '', credential_url: '', image: null, existingImage: '' };
};

const submitCertificate = async () => {
    const formData = new FormData();
    formData.append('name', certForm.value.name);
    formData.append('issuer', certForm.value.issuer);
    formData.append('issued_date', certForm.value.issued_date);
    if(certForm.value.credential_url) formData.append('credential_url', certForm.value.credential_url);
    if(certForm.value.image) formData.append('image', certForm.value.image);

    try {
        const token = localStorage.getItem('token');
        const config = { headers: { Authorization: `Bearer ${token}` } };
        if (isEditingCert.value) {
            formData.append('_method', 'PUT');
            await axios.post(`/api/certificates/${editCertId.value}`, formData, config);
        } else {
            await axios.post('/api/certificates', formData, config);
        }
        loadCertificates();
        cancelEditCert();
        alert('Sukses!');
    } catch (e) { alert('Gagal menyimpan sertifikat.'); console.error(e); }
};

const deleteCert = async (id) => {
    if(!confirm('Hapus sertifikat ini?')) return;
    try {
        const token = localStorage.getItem('token');
        await axios.delete(`/api/certificates/${id}`, { headers: { Authorization: `Bearer ${token}` } });
        loadCertificates();
    } catch (e) { alert('Gagal hapus'); }
};

// ======================= LOGOUT =======================
const handleLogout = async () => {
    try {
        const token = localStorage.getItem('token');
        await axios.post('/api/logout', {}, { headers: { Authorization: `Bearer ${token}` } });
        localStorage.removeItem('token');
        router.push('/secretdoor');
    } catch (e) {
        localStorage.removeItem('token');
        router.push('/secretdoor');
    }
};

onMounted(() => {
    loadProjects();
    loadCertificates();
});
</script>