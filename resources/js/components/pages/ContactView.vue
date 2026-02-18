<script setup>
import CardNav from '../CardNav.vue';

const navItems = [ /* Copy navItems yang sama persis */
  { label: "Home", bgColor: "#0D0716", textColor: "#fff", links: [{ label: "Intro", href: "/" }, { label: "About Me", href: "/#about" }] },
  { label: "Portfolio", bgColor: "#5B21B6", textColor: "#fff", links: [{ label: "Projects", href: "/portfolio" }, { label: "Certificates", href: "/portfolio" }] },
  { label: "Contact", bgColor: "#0F172A", textColor: "#fff", links: [{ label: "Contact Form", href: "#" }, { label: "Email Me", href: "mailto:email@example.com" }] }
];
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 font-sans text-gray-900 dark:text-white transition-colors duration-300">
        <div class="relative z-50">
             <CardNav logo="https://placehold.co/100x40/transparent/000?text=ER." :items="navItems" />
        </div>

        <section class="pt-48 pb-20 px-6 max-w-4xl mx-auto flex flex-col justify-center min-h-[90vh]">
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-4">Let's Talk.</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-12 max-w-2xl">
                Punya ide menarik atau ingin membangun sesuatu yang hebat? Kirimkan pesan di bawah ini atau email saya langsung.
            </p>

            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-bold text-sm uppercase tracking-wide text-gray-500">Nama</label>
                        <input type="text" class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-purple-600 outline-none transition" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-sm uppercase tracking-wide text-gray-500">Email</label>
                        <input type="email" class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-purple-600 outline-none transition" placeholder="john@example.com">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="font-bold text-sm uppercase tracking-wide text-gray-500">Pesan</label>
                    <textarea rows="6" class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-purple-600 outline-none transition" placeholder="Ceritakan projectmu..."></textarea>
                </div>
                <button type="button" class="w-full md:w-auto px-10 py-4 bg-black dark:bg-white text-white dark:text-black font-bold rounded-full text-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-xl">
                    Kirim Pesan 🚀
                </button>
            </form>

            <div class="mt-16 pt-16 border-t border-gray-200 dark:border-gray-800 flex flex-col md:flex-row justify-between text-gray-500 gap-4">
                <p>© 2024 Creative Developer.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-black dark:hover:text-white transition">LinkedIn</a>
                    <a href="#" class="hover:text-black dark:hover:text-white transition">GitHub</a>
                    <a href="#" class="hover:text-black dark:hover:text-white transition">Instagram</a>
                </div>
            </div>
        </section>
    </div>
</template>