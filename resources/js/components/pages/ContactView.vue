<script setup>
import { ref } from 'vue';
import axios from 'axios';
import CardNav from '../CardNav.vue';

const navItems = [ 
  { label: "Home", bgColor: "#0D0716", textColor: "#fff", links: [{ label: "Intro", href: "/" }, { label: "About Me", href: "/#about" }] },
  { label: "Portfolio", bgColor: "#5B21B6", textColor: "#fff", links: [{ label: "Projects", href: "/portfolio" }, { label: "Certificates", href: "/portfolio" }] },
  { label: "Contact", bgColor: "#0F172A", textColor: "#fff", links: [{ label: "Contact Form", href: "#" }, { label: "Email Me", href: "mailto:irfan2904.ia@gmail.com" }] }
];

// Siapkan variabel reaktif untuk menampung inputan user
const form = ref({
    name: '',
    email: '',
    message: ''
});

const isSubmitting = ref(false);
const successMessage = ref('');

// simpan pesan validasi
const errors = ref({});

// Fungsi untuk mengirim data
const submitForm = async () => {
    isSubmitting.value = true;
    successMessage.value = '';
    errors.value = {};

    try {
        // GANTI BAGIAN INI: Tambahkan headers khusus
        const response = await axios.post('/api/send-message', form.value, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        successMessage.value = response.data.message;
        form.value = { name: '', email: '', message: '' };
        
        setTimeout(() => { successMessage.value = ''; }, 5000);
    } catch (error) {
        console.error("Gagal mengirim pesan:", error);
        if (error.response && error.response.status === 422) {
            // ambil pesan validasi dari server
            console.log('validation response', error.response.data);
            if (error.response.data && error.response.data.errors) {
                errors.value = error.response.data.errors;
            }
            alert("Gagal: Mohon periksa kembali form Anda.");
        } else {
            alert("Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.");
        }
    } finally {
        isSubmitting.value = false;
    }
};

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

            <div v-if="successMessage" class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl font-bold border border-green-200">
                ✅ {{ successMessage }}
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-bold text-sm uppercase tracking-wide text-gray-500">Nama</label>
                        <input v-model="form.name" type="text" required class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-purple-600 outline-none transition" placeholder="John Doe">
                        <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-sm uppercase tracking-wide text-gray-500">Email</label>
                        <input v-model="form.email" type="email" required class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-purple-600 outline-none transition" placeholder="john@example.com">
                        <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="font-bold text-sm uppercase tracking-wide text-gray-500">Pesan</label>
                    <textarea v-model="form.message" rows="6" required class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-purple-600 outline-none transition" placeholder="Ceritakan projectmu..."></textarea>
                    <p v-if="errors.message" class="text-red-600 text-sm mt-1">{{ errors.message[0] }}</p>
                </div>
                
                <button type="submit" :disabled="isSubmitting" class="w-full md:w-auto px-10 py-4 bg-black dark:bg-white text-white dark:text-black font-bold rounded-full text-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-xl disabled:opacity-50">
                    {{ isSubmitting ? 'Mengirim...' : 'Kirim Pesan 🚀' }}
                </button>
            </form>

            <div class="mt-16 pt-16 border-t border-gray-200 dark:border-gray-800 flex flex-col md:flex-row justify-between text-gray-500 gap-4">
                <p>© 2026 Creative Developer.</p>
                <div class="flex gap-6">
                    <a href="https://www.linkedin.com/in/erpan1945/" target="_blank" rel="noopener noreferrer" class="hover:text-purple-600 transition font-bold">LinkedIn</a>
                    <a href="https://github.com/Erpan1945" target="_blank" rel="noopener noreferrer" class="hover:text-purple-600 transition font-bold">GitHub</a>
                    <a href="https://www.instagram.com/yrpan29" target="_blank" rel="noopener noreferrer" class="hover:text-purple-600 transition font-bold">Instagram</a>
                </div>
            </div>
        </section>
    </div>
</template>