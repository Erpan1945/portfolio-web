<script setup lang="ts">
import { gsap } from 'gsap';
import { nextTick, onBeforeUpdate, onMounted, onUnmounted, ref, watch, type VNodeRef } from 'vue';
import { useRouter, useRoute } from 'vue-router'; // Import Router

// --- Tipe Data ---
type CardNavLink = {
  label: string;
  href?: string;
  ariaLabel: string;
};

export type CardNavItem = {
  label: string;
  bgColor: string;
  textColor: string;
  links: CardNavLink[];
};

export interface CardNavProps {
  logo: string;
  logoAlt?: string;
  items: CardNavItem[];
  className?: string;
  ease?: string;
  baseColor?: string;
  menuColor?: string;
  buttonBgColor?: string;
  buttonTextColor?: string;
}

const props = withDefaults(defineProps<CardNavProps>(), {
  logoAlt: 'Logo',
  className: '',
  ease: 'power3.out',
  baseColor: '#fff',
  menuColor: '#000',
  buttonBgColor: '#000',
  buttonTextColor: '#fff'
});

// --- State ---
const isHamburgerOpen = ref(false);
const isExpanded = ref(false);
const isDark = ref(false);
const navRef = ref<HTMLDivElement | null>(null);
const cardsRef = ref<HTMLDivElement[]>([]);
const tlRef = ref<gsap.core.Timeline | null>(null);

const router = useRouter(); // Init Router
const route = useRoute();   // Init Route

const setCardRef = (i: number): VNodeRef => (el) => {
  if (el && el instanceof HTMLDivElement) {
    cardsRef.value[i] = el;
  }
};

onBeforeUpdate(() => {
  cardsRef.value = [];
});

// --- LOGIC SCROLL TO TOP ---
const handleLogoClick = () => {
  if (route.path === '/') {
    // Jika sedang di Home, scroll ke atas dengan smooth
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  } else {
    // Jika di halaman lain, pindah ke Home dulu
    router.push('/');
  }
};

// --- Logic Dark Mode ---
const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

// --- Logic GSAP (Animasi) ---
const calculateHeight = () => {
  const navEl = navRef.value;
  if (!navEl) return 60; 

  const isMobile = window.matchMedia('(max-width: 768px)').matches;
  if (isMobile) {
    return 60 + (props.items.slice(0, 3).length * 100) + 20; 
  }
  return 260; 
};

const createTimeline = () => {
  const navEl = navRef.value;
  if (!navEl) return null;

  gsap.set(navEl, { height: 60, overflow: 'hidden' });
  gsap.set(cardsRef.value, { y: 50, opacity: 0 });

  const tl = gsap.timeline({ paused: true });

  tl.to(navEl, {
    height: calculateHeight(),
    duration: 0.4,
    ease: props.ease
  });

  tl.to(cardsRef.value, { 
    y: 0, 
    opacity: 1, 
    duration: 0.4, 
    ease: props.ease, 
    stagger: 0.08 
  }, '-=0.2');

  return tl;
};

const toggleMenu = () => {
  const tl = tlRef.value;
  if (!tl) return;

  if (!isExpanded.value) {
    isHamburgerOpen.value = true;
    isExpanded.value = true;
    const newHeight = calculateHeight();
    tl.play();
  } else {
    isHamburgerOpen.value = false;
    tl.reverse();
    tl.eventCallback('onReverseComplete', () => {
      isExpanded.value = false;
      tl.eventCallback('onReverseComplete', null); 
    });
  }
};

const handleResize = () => {
  if (tlRef.value) tlRef.value.kill();
  tlRef.value = createTimeline();
  
  if (isExpanded.value && tlRef.value) {
    tlRef.value.progress(1);
  }
};

onMounted(() => {
  tlRef.value = createTimeline();
  window.addEventListener('resize', handleResize);

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

onUnmounted(() => {
  tlRef.value?.kill();
  tlRef.value = null;
  window.removeEventListener('resize', handleResize);
});

watch(() => [props.ease, props.items], () => {
  nextTick(() => {
    if (tlRef.value) tlRef.value.kill();
    tlRef.value = createTimeline();
  });
});
</script>

<template>
  <div :class="`card-nav-container fixed left-1/2 -translate-x-1/2 w-[90%] max-w-200 z-99 top-[1.2em] md:top-[2em] pointer-events-none ${props.className}`">
    
    <nav
      ref="navRef"
      :class="[
        'card-nav block h-15 p-0 rounded-xl shadow-lg relative overflow-hidden will-change-[height] border border-gray-100 dark:border-gray-700 pointer-events-auto transition-colors duration-300',
        { open: isExpanded }
      ]"
      :style="{ backgroundColor: isDark ? '#1f2937' : props.baseColor }"
    >
      <div class="card-nav-top top-0 z-2 absolute inset-x-0 flex justify-between items-center px-4 h-15">
        
        <div
          :class="[
            'hamburger-menu group h-full flex flex-col items-center justify-center cursor-pointer gap-1.5 order-2 md:order-0',
            { open: isHamburgerOpen }
          ]"
          @click="toggleMenu"
          role="button"
          :aria-label="isExpanded ? 'Close menu' : 'Open menu'"
          :style="{ color: isDark ? '#fff' : props.menuColor }"
        >
          <div :class="['w-6 h-0.5 bg-current transition-all duration-300 origin-center', { 'translate-y-1 rotate-45': isHamburgerOpen }]" />
          <div :class="['w-6 h-0.5 bg-current transition-all duration-300 origin-center', { '-translate-y-1 -rotate-45': isHamburgerOpen }]" />
        </div>

        <div class="md:absolute md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 flex items-center order-1 md:order-0 cursor-pointer">
            <a href="#" @click.prevent="handleLogoClick">
                <img 
                    :src="props.logo" 
                    :alt="props.logoAlt" 
                    class="h-6 w-auto object-contain transition-all duration-300"
                    :style="{ filter: isDark ? 'invert(1)' : 'none' }"
                />
            </a>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <button @click="toggleTheme" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
                 <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                 <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </button>
            <router-link
              to="/cv"
              class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-lg transition-colors duration-300 shadow-md"
              :style="{ backgroundColor: isDark ? '#3b82f6' : props.buttonBgColor, color: props.buttonTextColor }"
            >
              View CV
            </router-link>
        </div>
        
        <button @click="toggleTheme" class="md:hidden p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none order-3">
             <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
             <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>

      </div>

      <div
        class="absolute left-0 right-0 top-15 bottom-0 p-2 flex flex-col md:flex-row items-stretch md:items-end gap-2 z-1"
        :class="isExpanded ? 'visible pointer-events-auto' : 'invisible pointer-events-none'"
        :aria-hidden="!isExpanded"
      >
        <div
          v-for="(item, idx) in (props.items || []).slice(0, 3)"
          :key="`${item.label}-${idx}`"
          :ref="setCardRef(idx)"
          class="relative flex flex-col flex-1 gap-2 p-4 rounded-lg min-h-20 md:h-full justify-between group cursor-default shadow-sm"
          :style="{ backgroundColor: item.bgColor, color: item.textColor }"
        >
          <div class="font-medium text-lg md:text-xl tracking-tight">
            {{ item.label }}
          </div>
          
          <div class="flex flex-col gap-1 mt-auto">
            <router-link
              v-for="(lnk, i) in item.links"
              :key="`${lnk.label}-${i}`"
              class="inline-flex items-center gap-1 opacity-80 hover:opacity-100 transition-opacity text-sm cursor-pointer hover:underline"
              :to="lnk.href || '#'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
              </svg>
              {{ lnk.label }}
            </router-link>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>