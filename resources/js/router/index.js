import { createRouter, createWebHistory } from 'vue-router';

// Import komponen (tetap sama)
import LandingView from '../components/pages/LandingView.vue';
import PortfolioView from '../components/pages/PortfolioView.vue';
import ContactView from '../components/pages/ContactView.vue';
import LoginView from '../components/pages/LoginView.vue';
import AdminView from '../components/pages/AdminView.vue';

// Tentukan nama rahasia di sini agar mudah diganti-ganti
const SECRET_LOGIN_URL = '/secretdoor'; // <--- GANTI INI SESUKA HATIMU
const SECRET_ADMIN_URL = '/letmecook';    // <--- GANTI INI SESUKA HATIMU

const routes = [
    { path: '/', name: 'landing', component: LandingView },
    { path: '/portfolio', name: 'portfolio', component: PortfolioView },
    { path: '/contact', name: 'contact', component: ContactView },
    
    // --- JALUR RAHASIA ---
    { 
        path: SECRET_LOGIN_URL,  // Dulu '/login'
        name: 'login', 
        component: LoginView 
    },
    { 
        path: SECRET_ADMIN_URL,  // Dulu '/admin'
        name: 'admin', 
        component: AdminView,
        meta: { requiresAuth: true } 
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// SATPAM ROUTER (Navigation Guard)
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    
    // 1. Jika mau masuk ke Admin (Dapur Saya) TAPI tidak punya token
    if (to.meta.requiresAuth && !token) {
        // Tendang ke halaman login rahasia
        next(SECRET_LOGIN_URL); 
    } 
    // 2. Jika sudah login, tapi iseng buka halaman login lagi
    else if (to.path === SECRET_LOGIN_URL && token) {
        // Langsung lempar ke admin
        next(SECRET_ADMIN_URL); 
    }
    else {
        next(); 
    }
});

export default router;