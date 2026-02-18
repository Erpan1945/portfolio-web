<template>
    <div class="antialiased text-gray-900 bg-gray-100 min-h-screen">
        
        <Navbar v-if="$route.path !== '/'" />

        <router-view></router-view>
        
    </div>

    <!-- <footer class="bg-gray-800 text-white text-center py-6 mt-20">
        <p>&copy; 2026 My Portofolio Gue. All rights reserved.</p>
    </footer> -->
</template>

<script setup>
//import Navbar from './components/Navbar.vue';
// Tidak perlu import CardNav disini, karena CardNav sudah ada di dalam LandingView.vue
</script>