<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 font-sans text-gray-900 dark:text-white overflow-x-hidden selection:bg-purple-200 selection:text-purple-900 transition-colors duration-300">
        
        <section class="relative pt-24 pb-32 px-6 max-w-7xl mx-auto flex flex-col justify-center min-h-[85vh]">
            <div class="absolute top-20 right-4 md:right-20 lg:right-32 z-20 transform rotate-6 animate-float">
                <div class="bg-purple-600 text-white px-6 py-3 md:px-8 md:py-4 rounded-xl shadow-xl border-2 border-white dark:border-gray-800 transform hover:scale-110 transition cursor-pointer group">
                    <span class="font-bold text-lg md:text-xl block group-hover:hidden">A new dimension ✨</span>
                    <span class="font-bold text-lg md:text-xl hidden group-hover:block">Hire Me Now! 🚀</span>
                    <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-purple-800 rounded-full -z-10"></div>
                </div>
            </div>

            <div class="relative z-10 text-center mt-10">
                <h1 class="text-[14vw] md:text-[11rem] leading-[0.85] font-black tracking-tighter text-gray-900 dark:text-white mb-4 md:mb-0 transition-colors">
                    CREATIVE
                </h1>
                
                <div class="relative inline-block">
                    <h1 class="text-[14vw] md:text-[11rem] leading-[0.85] font-black tracking-tighter text-gray-900 dark:text-white transition-colors">
                        DEVELOPER
                    </h1>
                    
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -rotate-3 z-20">
                        <span class="bg-orange-500 text-white text-sm md:text-2xl font-bold px-6 py-2 md:px-10 md:py-3 rounded-full border-4 border-white dark:border-gray-900 shadow-lg whitespace-nowrap transition-colors">
                            FULLSTACK
                        </span>
                    </div>

                    <div class="absolute top-[17%] right-[15%] md:right-[22%] text-yellow-400 animate-spin-slow pointer-events-none hidden md:block">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mt-16 flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16 max-w-4xl mx-auto w-full">
                <div class="flex gap-4 items-center text-left">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden border-2 border-black dark:border-white shrink-0 transition-colors">
                        <img src="https://ui-avatars.com/api/?name=Me&background=random" alt="Me" class="w-full h-full object-cover">
                    </div>
                    <p class="max-w-xs text-sm md:text-base font-medium text-gray-600 dark:text-gray-300">
                        Menciptakan pengalaman digital yang tahan lama, fungsional, dan estetik.
                    </p>
                </div>
                <div>
                     <router-link to="/contact" class="px-8 py-4 bg-black dark:bg-white text-white dark:text-black font-bold rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-xl whitespace-nowrap">
                        Mulai Kolaborasi →
                    </router-link>
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
                
                <div class="flex flex-col md:flex-row justify-between items-start mb-16 md:mb-32">
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
                    
                    <div v-for="(project, index) in projects" 
                         :key="project.id"
                         class="relative h-full w-full group"
                         :class="{ 'mt-12 md:mt-32': index % 2 !== 0 }">
                         
                        <div class="animate-float-card h-full w-full"
                             :style="getRandomFloatStyle(index)">

                            <div class="relative rounded-[2.5rem] aspect-4/7 shadow-xl transition-transform group-hover:-translate-y-4 duration-300 cursor-pointer overflow-hidden transform -rotate-6 origin-center hover:rotate-0 hover:scale-105"
                                 :class="getCardColor(index)">
                                
                                <a :href="project.link || '#'" target="_blank" class="block w-full h-full relative">
                                    <div class="absolute inset-0 p-4 h-full w-full">
                                        <div class="h-full w-full rounded-[2rem] overflow-hidden shadow-sm relative bg-gray-200 dark:bg-gray-800 border-4 border-transparent">
                                            
                                            <img :src="project.image || 'https://placehold.co/600x800/eee/333?text=No+Image'" 
                                                 :alt="project.title" 
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                 
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

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const projects = ref([]);
const loading = ref(true);

const cardColors = [
    'bg-[#E83B46]', 
    'bg-[#FDD32A]', 
    'bg-[#FA5D37]', 
    'bg-[#CE96D8]',
    'bg-[#4ADE80]',
    'bg-[#60A5FA]'
];

const getCardColor = (index) => {
    return cardColors[index % cardColors.length];
};

const getRandomFloatStyle = (index) => {
    const duration = 4 + (index % 3) * 1.5; 
    const delay = -1 * ((index * 2.3) % 5);

    return {
        animationDuration: `${duration}s`,
        animationDelay: `${delay}s`
    };
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
</script>

<style scoped>
/* CSS KHUSUS UNTUK TEXT STROKE (OurGallery) */
/* Default (Light Mode): Stroke Hitam */
.stroke-text {
    -webkit-text-stroke: 2px black;
}

/* Dark Mode: Stroke Putih */
/* Kita pakai syntax .dark .stroke-text karena scoped style */
:global(.dark) .stroke-text {
    -webkit-text-stroke: 2px rgba(255, 255, 255, 0.3); /* Transparan dikit biar elegan */
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(6deg); }
    50% { transform: translateY(-10px) rotate(8deg); }
}
.animate-float {
    animation: float 4s ease-in-out infinite;
}

@keyframes float-vertical {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}
.animate-float-card {
    animation: float-vertical 5s ease-in-out infinite;
}

@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spin-slow 12s linear infinite;
}

/* 1. Kondisi Normal (Light Mode): Garis Hitam */
.stroked-text {
    -webkit-text-stroke: 2px black;
    color: transparent; /* Isinya tetap transparan/bolong */
}

/* 2. Kondisi Dark Mode: Garis Putih */
/* :global(.dark) mendeteksi jika mode gelap aktif di seluruh halaman */
:global(.dark) .stroked-text {
    -webkit-text-stroke: 2px rgba(255, 255, 255, 0.2); /* Putih transparan dikit biar estetik */
}

</style>