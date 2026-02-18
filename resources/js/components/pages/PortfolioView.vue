<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import CardNav from '../CardNav.vue';

const activeTab = ref('projects'); // 'projects' or 'certificates'
const projects = ref([]);
const certificates = ref([]);

const navItems = [ /* Copy navItems yang sama persis dari LandingView */
  { label: "Home", bgColor: "#0D0716", textColor: "#fff", links: [{ label: "Intro", href: "/" }, { label: "About Me", href: "/#about" }] },
  { label: "Portfolio", bgColor: "#5B21B6", textColor: "#fff", links: [{ label: "Projects", href: "#" }, { label: "Certificates", href: "#" }] },
  { label: "Contact", bgColor: "#0F172A", textColor: "#fff", links: [{ label: "Contact Form", href: "/contact" }, { label: "Email Me", href: "mailto:email@example.com" }] }
];

const fetchData = async () => {
    try {
        // Ambil Projects
        const resProjects = await axios.get('/api/projects');
        projects.value = resProjects.data;

        // Ambil Certificates (DARI DATABASE)
        const resCerts = await axios.get('/api/certificates');
        certificates.value = resCerts.data;
        
    } catch (e) { console.error("Gagal load data:", e); }
};

onMounted(() => {
    fetchData();
});

const fetchProjects = async () => {
    try {
        const res = await axios.get('/api/projects');
        projects.value = res.data;
    } catch (e) { console.error(e); }
};

onMounted(() => fetchProjects());
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 font-sans text-gray-900 dark:text-white transition-colors duration-300">
        <div class="relative z-50">
             <CardNav logo="https://placehold.co/100x40/transparent/000?text=ER." :items="navItems" />
        </div>

        <section class="pt-48 pb-24 px-6 max-w-7xl mx-auto min-h-screen">
            <h1 class="text-6xl md:text-9xl font-black tracking-tighter mb-12 text-center">PORTFOLIO<span class="text-purple-600">.</span></h1>

            <div class="flex justify-center gap-4 mb-16">
                <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'bg-black text-white dark:bg-white dark:text-black' : 'bg-gray-200 text-gray-600 dark:bg-gray-800 dark:text-gray-400'" class="px-8 py-3 rounded-full font-bold text-lg transition-all">Projects</button>
                <button @click="activeTab = 'certificates'" :class="activeTab === 'certificates' ? 'bg-black text-white dark:bg-white dark:text-black' : 'bg-gray-200 text-gray-600 dark:bg-gray-800 dark:text-gray-400'" class="px-8 py-3 rounded-full font-bold text-lg transition-all">Certificates</button>
            </div>

            <div v-if="activeTab === 'projects'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="project in projects" :key="project.id" class="group relative rounded-3xl overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-lg aspect-square cursor-pointer">
                    <img :src="project.image" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-center items-center text-center p-6">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ project.title }}</h3>
                        <p class="text-gray-300 text-sm mb-4 line-clamp-3">{{ project.description }}</p>
                        <a :href="project.link" target="_blank" class="px-6 py-2 bg-white text-black font-bold rounded-full hover:bg-gray-200">View Project</a>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'certificates'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="cert in certificates" :key="cert.id" 
                    class="p-6 rounded-3xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 hover:border-purple-500 transition-all group flex flex-col h-full cursor-pointer"
                    @click="cert.credential_url ? window.open(cert.credential_url, '_blank') : null">
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-gray-100 dark:border-gray-700 bg-gray-50">
                            <img :src="cert.image || 'https://placehold.co/100x100?text=CERT'" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">
                                {{ cert.issuer }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ cert.issued_date ? cert.issued_date.substring(0, 4) : '-' }}
                            </p>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white leading-tight group-hover:text-purple-600 transition-colors">
                        {{ cert.name }}
                    </h3>
                    
                    <div class="mt-auto pt-4">
                        <div class="w-full h-px bg-gray-100 dark:bg-gray-700 group-hover:bg-purple-500 transition-colors mb-4"></div>
                        <p v-if="cert.credential_url" class="text-sm font-bold text-gray-400 group-hover:text-black dark:group-hover:text-white flex items-center gap-2 transition-colors">
                            View Credential ↗
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>