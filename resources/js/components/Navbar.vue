<template>
    <nav class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="shrink-0 flex items-center">
                    <router-link to="/" class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">
                        Er<span class="text-blue-600">.</span>
                    </router-link>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    <router-link to="/" active-class="text-blue-600 font-bold dark:text-blue-400" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md font-medium transition">
                        Home
                    </router-link>
                    
                    <router-link to="/portfolio" active-class="text-blue-600 font-bold dark:text-blue-400" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md font-medium transition">
                        Portfolio
                    </router-link>
                    
                    <router-link to="/contact" active-class="text-blue-600 font-bold dark:text-blue-400" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md font-medium transition">
                        Contact
                    </router-link>
                    
                    <button @click="toggleTheme" class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-yellow-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <router-link to="/cv" active-class="bg-blue-700 shadow-none ring-2 ring-offset-2 ring-blue-500" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center">
                        Lihat CV
                    </router-link>
                </div>

                <div class="flex items-center md:hidden gap-4">
                     <button @click="toggleTheme" class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-yellow-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>

                    <button @click="isOpen = !isOpen" type="button" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white focus:outline-none p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isOpen" class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <router-link to="/" active-class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 hover:bg-gray-50 dark:hover:bg-gray-800" @click="isOpen = false">
                    Home
                </router-link>
                <router-link to="/portfolio" active-class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 hover:bg-gray-50 dark:hover:bg-gray-800" @click="isOpen = false">
                    Portfolio
                </router-link>
                <router-link to="/contact" active-class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 hover:bg-gray-50 dark:hover:bg-gray-800" @click="isOpen = false">
                    Contact
                </router-link>
                
                <router-link to="/cv" active-class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold" class="block w-full text-center mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" @click="isOpen = false">
                    Lihat CV
                </router-link>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const isOpen = ref(false);
const isDark = ref(false);

// Fungsi Toggle Theme yang Lebih Kuat
const toggleTheme = () => {
    // 1. Balikkan nilai state
    isDark.value = !isDark.value;
    
    // 2. Update DOM (HTML Class) dan LocalStorage
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

onMounted(() => {
    // Logika Cek Awal (Sinkronisasi dengan script anti-flash di welcome.blade.php)
    // Prioritas: 1. LocalStorage, 2. System Preference
    const userPrefersDark = localStorage.theme === 'dark' || 
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (userPrefersDark) {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});
</script>