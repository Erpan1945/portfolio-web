<template>
    <div class="min-h-screen bg-gray-100 py-10 print:bg-white print:py-0">
        
        <div class="max-w-4xl mx-auto mb-6 flex justify-end px-4 print:hidden">
            <button @click="printCv" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Download PDF / Print
            </button>
        </div>

        <div class="max-w-4xl mx-auto bg-white shadow-2xl p-10 md:p-16 rounded-lg print:shadow-none print:w-full print:max-w-none">
            
            <header class="border-b-2 border-gray-800 pb-8 mb-8">
                <h1 class="text-4xl font-bold text-gray-900 uppercase tracking-wider">Nama Kamu Disini</h1>
                <p class="text-xl text-gray-600 mt-2">Web Developer & Designer</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500">
                    <span>📧 email@kamu.com</span>
                    <span>📱 0812-3456-7890</span>
                    <span>📍 Malang, Indonesia</span>
                    <span>🌐 portofolio.com</span>
                </div>
            </header>

            <section class="mb-10" v-if="experiences.length">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 uppercase border-b pb-2">Pengalaman Kerja</h2>
                <div v-for="item in experiences" :key="item.id" class="mb-6">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-xl font-bold text-gray-900">{{ item.title }}</h3>
                        <span class="text-sm text-gray-500 font-medium">
                            {{ formatDate(item.start_date) }} - {{ item.end_date ? formatDate(item.end_date) : 'Sekarang' }}
                        </span>
                    </div>
                    <div class="text-blue-600 font-semibold mb-2">{{ item.subtitle }}</div>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ item.description }}</p>
                </div>
            </section>

            <section class="mb-10" v-if="educations.length">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 uppercase border-b pb-2">Pendidikan</h2>
                <div v-for="item in educations" :key="item.id" class="mb-4">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ item.title }}</h3>
                            <div class="text-gray-600">{{ item.subtitle }}</div>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ formatDateYear(item.start_date) }} - {{ formatDateYear(item.end_date) }}
                        </div>
                    </div>
                </div>
            </section>

            <section v-if="skills.length">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 uppercase border-b pb-2">Keahlian</h2>
                <div class="flex flex-wrap gap-2">
                    <span v-for="item in skills" :key="item.id" class="px-3 py-1 bg-gray-200 text-gray-800 rounded font-medium text-sm print:border print:border-gray-300">
                        {{ item.title }}
                    </span>
                </div>
            </section>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const cvData = ref([]);

// Filter Data berdasarkan Type
const experiences = computed(() => cvData.value.filter(i => i.type === 'experience'));
const educations = computed(() => cvData.value.filter(i => i.type === 'education'));
const skills = computed(() => cvData.value.filter(i => i.type === 'skill'));

const fetchCv = async () => {
    try {
        const res = await axios.get('/api/cv');
        cvData.value = res.data;
    } catch (e) { console.error(e); }
};

// Fungsi Print
const printCv = () => {
    window.print();
};

// Format Tanggal (Jan 2023)
const formatDate = (dateString) => {
    if(!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
};

// Format Tahun Saja (2023)
const formatDateYear = (dateString) => {
    if(!dateString) return '';
    return new Date(dateString).getFullYear();
};

onMounted(() => {
    fetchCv();
});
</script>