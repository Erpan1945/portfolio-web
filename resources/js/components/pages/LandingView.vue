<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import CardNav from '../CardNav.vue';

// --- 1. DATA UNTUK NAVBAR (UPDATED) ---
const logo = "https://placehold.co/100x40/transparent/000?text=ER."; 

const navItems = [
  {
    label: "Home",
    bgColor: "#0D0716",
    textColor: "#fff",
    links: [
      { label: "Intro", href: "#intro" },     // Link ke Hero Section
      { label: "About Me", href: "#about" }   // Link ke About Section
    ]
  },
  {
    label: "Portfolio",
    bgColor: "#5B21B6",
    textColor: "#fff",
    links: [
      { label: "Projects", href: "/portfolio" },     // Ke Halaman Portfolio
      { label: "Certificates", href: "/portfolio" }  // Ke Halaman Portfolio
    ]
  },
  {
    label: "Contact",
    bgColor: "#0F172A",
    textColor: "#fff",
    links: [
      { label: "Contact Form", href: "/contact" },
      { label: "Email Me", href: "mailto:irfan2904.ia@gmail.com" }
    ]
  }
];

const profilePhoto = ref('/images/default-profile.png'); 

onMounted(async () => {
    try {
        // 2. Ambil data dari API
        const response = await axios.get('/api/settings/photo');
        
        // 3. Jika URL foto ada di database, timpa foto default
        if (response.data.photo_url) {
            profilePhoto.value = response.data.photo_url;
        }
    } catch (error) {
        console.error("Gagal memuat foto profil:", error);
    }
});

// --- 2. LOGIC HALAMAN ---
const projects = ref([]);
const loading = ref(true);

const cardColors = [
    'bg-[#E83B46]', 'bg-[#FDD32A]', 'bg-[#FA5D37]', 
    'bg-[#CE96D8]', 'bg-[#4ADE80]', 'bg-[#60A5FA]'
];

const getCardColor = (index) => cardColors[index % cardColors.length];

const getRandomFloatStyle = (index) => {
    const duration = 4 + (index % 3) * 1.5; 
    const delay = -1 * ((index * 2.3) % 5);
    return { animationDuration: `${duration}s`, animationDelay: `${delay}s` };
};

const fetchProjects = async () => {
    try {
        const response = await axios.get('/api/projects');
        projects.value = response.data.slice(0, 4); 
    } catch (error) {
        console.error("Gagal mengambil project:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchProjects();
});

const certificates = ref([]);

const fetchCertificates = async () => {
    try {
        const response = await axios.get('/api/certificates');
        // Pastikan data yang diambil adalah Array
        if (Array.isArray(response.data)) {
            // Ambil hanya 3 atau 4 sertifikat terbaru untuk dipajang di Home
            certificates.value = response.data.slice(0, 3);
        }
    } catch (error) {
        console.error("Gagal mengambil data sertifikat:", error);
    }
};

onMounted(() => {
    fetchProjects(); // Fungsi lama kamu
    fetchCertificates(); // Panggil fungsi baru ini
});
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 font-sans text-gray-900 dark:text-white overflow-x-hidden selection:bg-purple-200 selection:text-purple-900 transition-colors duration-300 relative scroll-smooth">
        
        <div class="relative z-50">
             <CardNav
              :logo="logo"
              logoAlt="My Portfolio"
              :items="navItems"
              baseColor="#ffffff"
              menuColor="#000000"
              buttonBgColor="#111111"
              buttonTextColor="#ffffff"
              ease="power3.out"
            />
        </div>

        <section id="intro" class="relative pt-54 pb-32 px-6 max-w-7xl mx-auto flex flex-col justify-center min-h-[85vh]">
            
            <div class="absolute top-42 right-4 md:right-20 lg:right-32 z-20 transform rotate-6 animate-float">
                <div class="bg-purple-600 text-white px-6 py-3 md:px-8 md:py-4 rounded-xl shadow-xl border-2 border-white dark:border-gray-800 transform hover:scale-110 transition cursor-pointer group">
                    <span class="font-bold text-lg md:text-xl block group-hover:hidden">A new dimension ✨</span>
                    <span class="font-bold text-lg md:text-xl hidden group-hover:block">Hire Me Now! 🚀</span>
                    <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-purple-800 rounded-full -z-10"></div>
                </div>
            </div>

            <div class="relative z-10 text-center mt-10">
                <h1 class="text-[14vw] md:text-[11rem] leading-[0.85] font-black tracking-tighter text-gray-900 dark:text-white mb-4 md:mb-0 transition-colors">CREATIVE</h1>
                <div class="relative inline-block">
                    <h1 class="text-[14vw] md:text-[11rem] leading-[0.85] font-black tracking-tighter text-gray-900 dark:text-white transition-colors">DEVELOPER</h1>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -rotate-3 z-20">
                        <span class="bg-orange-500 text-white text-sm md:text-2xl font-bold px-6 py-2 md:px-10 md:py-3 rounded-full border-4 border-white dark:border-gray-900 shadow-lg whitespace-nowrap transition-colors">FULLSTACK</span>
                    </div>
                    <div class="absolute top-[17%] right-[12%] md:right-[22%] z-30 text-yellow-400 animate-spin-slow pointer-events-none hidden md:block">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" /></svg>
                    </div>
                </div>
            </div>

            <div class="mt-24 flex flex-col md:flex-row items-center md:justify-between gap-8 w-full">
                <div class="flex flex-col gap-3 text-left md:max-w-lg">
                    <div class="inline-flex items-center gap-2 self-start px-3 py-1.5 rounded-full bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">Available for Projects</span>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white leading-tight">Expert Fullstack Developer.</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">Spesialisasi dalam membangun aplikasi web modern dengan <span class="text-purple-600 dark:text-purple-400 font-semibold">Laravel</span> & <span class="text-green-600 dark:text-green-400 font-semibold">Vue.js</span>.</p>
                    </div>
                </div>
                <div class="shrink-0">
                     <router-link to="/contact" class="px-8 py-4 md:px-10 md:py-5 bg-black dark:bg-white text-white dark:text-black font-bold rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-xl whitespace-nowrap text-lg">Mulai Kolaborasi →</router-link>
                </div>
            </div>
        </section>

        <section id="about" class="py-32 bg-gray-50 dark:bg-gray-800/30 transition-colors border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row gap-16 items-start">
                    <div class="w-full md:w-1/3 relative group">
                        <div class="absolute inset-0 bg-purple-600 rounded-4xl transform rotate-3 transition-transform group-hover:rotate-6"></div>
                        <img :src="profilePhoto" alt="About Erpan" class="relative rounded-4xl shadow-xl w-full object-cover aspect-3/4 grayscale group-hover:grayscale-0 transition duration-500 border-4 border-white dark:border-gray-900">
                    </div>
                    
                    <div class="w-full md:w-2/3 space-y-8">
                        <h2 class="text-5xl md:text-7xl font-black text-gray-900 dark:text-white tracking-tighter">
                            About <span class="text-purple-600">Me.</span>
                        </h2>
                        
                        <div class="space-y-4 text-lg md:text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                            <p>
                                Halo! Perkenalkan, saya <strong class="text-gray-900 dark:text-white">Erpan</strong>, seorang mahasiswa program studi <strong class="text-blue-600 dark:text-blue-400">Sistem Informasi</strong> di <strong class="text-blue-600 dark:text-blue-400">Fakultas Ilmu Komputer Universitas Brawijaya</strong>. Saya adalah seorang developer yang bersemangat mengubah baris kode menjadi solusi nyata. Saya percaya bahwa website bukan hanya sekedar tampilan, melainkan sebuah <strong class="text-gray-900 dark:text-white">pengalaman digital</strong>.
                            </p>
                            <p>
                                Dengan latar belakang kuat di <strong class="text-purple-600 dark:text-purple-400">Laravel</strong> dan <strong class="text-green-600 dark:text-green-400">Vue.js</strong>, saya terbiasa menangani proyek dari arsitektur database hingga antarmuka pengguna yang interaktif dan responsif.
                            </p>
                        </div>
                        
                        <div class="pt-8">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-6 border-b border-gray-200 dark:border-gray-700 pb-2 inline-block">Tech Stack & Tools</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div v-for="skill in ['Laravel', 'Vue.js', 'Tailwind', 'MySQL', 'PostgreSQL', 'Git', 'Inertia', 'JavaScript', 'Figma']" :key="skill" 
                                    class="flex items-center gap-3 p-3 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:-translate-y-1 transition-transform">
                                    
                                    <div class="w-2 h-2 rounded-full" 
                                        :class="skill === 'Laravel' ? 'bg-red-500' : skill === 'Vue.js' ? 'bg-green-500' : 'bg-blue-500'"></div>
                                    <span class="font-bold text-sm text-gray-800 dark:text-white">{{ skill }}</span>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden min-h-screen flex flex-col justify-center transition-colors duration-300">
            
            <div class="absolute top-1/2 left-2/5 -translate-x-1/2 -translate-y-1/2 w-full text-center select-none pointer-events-none z-10 opacity-10 md:opacity-100">
               <h2 class="text-[20vw] md:text-[25vw] font-black text-transparent leading-none tracking-tighter stroked-text">
                    myProject
               </h2>
            </div>

            <div class="max-w-7xl mx-auto px-6 relative z-20 w-full">
                
                <div class="flex flex-col md:flex-row justify-between items-start mb-16 md:mb-23">
                    <div class="md:max-w-xs mb-8 md:mb-0">
                       <p class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                         Selected projects showing the combination of logic and aesthetics.
                       </p>
                    </div>
                    <div class="md:max-w-xs md:text-right">
                        <p class="text-xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                          Explore my latest work derived directly from the database.
                        </p>
                        <router-link to="/portfolio" class="inline-flex items-center font-black text-black dark:text-white hover:underline text-lg">
                          VIEW ALL <span class="ml-2 text-xl">→</span>
                        </router-link>
                    </div>
                </div>

                <div v-if="loading" class="text-center py-20">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 dark:border-white mx-auto"></div>
                </div>
                <div v-else-if="projects.length === 0" class="text-center py-20 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-white/80 dark:bg-gray-800/80 backdrop-blur">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada project.</p>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-10">
                    <div v-for="(project, index) in projects" :key="project.id" class="relative h-full w-full group" :class="{ 'mt-12 md:mt-32': index % 2 !== 0 }">
                        <div class="animate-float-card h-full w-full" :style="getRandomFloatStyle(index)">
                            <div class="relative rounded-[2.5rem] aspect-4/7 shadow-xl transition-transform group-hover:-translate-y-4 duration-300 cursor-pointer overflow-hidden transform -rotate-6 origin-center hover:rotate-0 hover:scale-105" :class="getCardColor(index)">
                                <a :href="project.link || '#'" target="_blank" class="block w-full h-full relative">
                                    <div class="absolute inset-0 p-4 h-full w-full">
                                        <div class="h-full w-full rounded-4xl overflow-hidden shadow-sm relative bg-gray-200 dark:bg-gray-800 border-4 border-transparent">
                                            <img :src="project.image || 'https://placehold.co/600x800/eee/333?text=No+Image'" :alt="project.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                                <div class="text-center p-4">
                                                    <h4 class="text-white font-black text-xl uppercase">{{ project.title }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>
</template>

<style scoped>
/* Animasi Float */
@keyframes float { 0%, 100% { transform: translateY(0) rotate(6deg); } 50% { transform: translateY(-10px) rotate(8deg); } }
.animate-float { animation: float 4s ease-in-out infinite; }
@keyframes float-vertical { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
.animate-float-card { animation: float-vertical 5s ease-in-out infinite; }
@keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin-slow { animation: spin-slow 12s linear infinite; }

/* Stroke Text Logic */
.stroked-text { -webkit-text-stroke: 2px black; color: transparent; }
:global(.dark) .stroked-text { -webkit-text-stroke: 2px rgba(255, 255, 255, 0.2); }
</style>