<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login Admin</h2>
            
            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Password</label>
                    <input v-model="form.password" type="password" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <p v-if="errorMessage" class="text-red-500 text-sm">{{ errorMessage }}</p>

                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({ email: '', password: '' });
const errorMessage = ref('');

const handleLogin = async () => {
    try {
        const response = await axios.post('/api/login', form.value);
        localStorage.setItem('token', response.data.token);
        
        // router.push('/admin');
        router.push('/letmecook');
        
    } catch (error) {
        errorMessage.value = "Login gagal! Periksa email/password.";
    }
};
</script>