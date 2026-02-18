<template>
    <Navbar />

    <router-view></router-view>

    <footer class="bg-gray-800 text-white text-center py-6 mt-20">
        <p>&copy; 2026 My Portofolio Gue. All rights reserved.</p>
    </footer>
</template>

<script setup>
import Navbar from './Navbar.vue';
</script>