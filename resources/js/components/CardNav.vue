<script setup lang="ts">
import { gsap } from 'gsap';
import { nextTick, onBeforeUpdate, onMounted, onUnmounted, ref, watch, type VNodeRef } from 'vue';

// --- Types ---
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

// --- Props ---
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
const navRef = ref<HTMLDivElement | null>(null);
const cardsRef = ref<HTMLDivElement[]>([]);
const tlRef = ref<gsap.core.Timeline | null>(null);

// --- Refs Management ---
const setCardRef = (i: number): VNodeRef => (el) => {
  if (el && el instanceof HTMLDivElement) {
    cardsRef.value[i] = el;
  }
};

onBeforeUpdate(() => {
  cardsRef.value = [];
});

// --- Logic Animasi ---
const calculateHeight = () => {
  const navEl = navRef.value;
  if (!navEl) return 60; // Default closed height

  // Logika Mobile vs Desktop
  const isMobile = window.matchMedia('(max-width: 768px)').matches;
  
  // Clone element secara virtual untuk kalkulasi tinggi jika perlu, 
  // atau gunakan hardcoded logic sesuai desain card
  if (isMobile) {
    // Estimasi tinggi untuk mobile: TopBar + (Jumlah Card * Tinggi Card) + Padding
    // Angka 260 adalah estimasi aman, bisa disesuaikan dengan konten
    return 60 + (props.items.slice(0, 3).length * 100) + 20; 
  }
  
  // Tinggi desktop saat expanded
  return 260; 
};

const createTimeline = () => {
  const navEl = navRef.value;
  if (!navEl) return null;

  // Set initial states
  gsap.set(navEl, { height: 60, overflow: 'hidden' });
  gsap.set(cardsRef.value, { y: 50, opacity: 0 });

  const tl = gsap.timeline({ paused: true });

  // Animasi Container membesar
  tl.to(navEl, {
    height: calculateHeight(),
    duration: 0.4,
    ease: props.ease
  });

  // Animasi Cards muncul (stagger)
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
    // Open
    isHamburgerOpen.value = true;
    isExpanded.value = true;
    // Recalculate height in case screen changed
    const newHeight = calculateHeight();
    // Update timeline destination if needed (advanced usage), 
    // for simplicity strictly re-playing works well for static content
    tl.play();
  } else {
    // Close
    isHamburgerOpen.value = false;
    tl.reverse();
    // Set expanded false after animation finishes
    tl.eventCallback('onReverseComplete', () => {
      isExpanded.value = false;
      tl.eventCallback('onReverseComplete', null); // clear callback
    });
  }
};

const handleResize = () => {
  // Re-create timeline on resize to handle responsive height changes
  if (tlRef.value) tlRef.value.kill();
  tlRef.value = createTimeline();
  
  // Jika sedang terbuka, paksa buka ulang timeline baru ke posisi akhir
  if (isExpanded.value && tlRef.value) {
    tlRef.value.progress(1);
  }
};

// --- Lifecycle ---
onMounted(() => {
  tlRef.value = createTimeline();
  window.addEventListener('resize', handleResize);
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
  <div :class="`card-nav-container absolute left-1/2 -translate-x-1/2 w-[90%] max-w-[800px] z-[99] top-[1.2em] md:top-[2em] ${props.className}`">
    <nav
      ref="navRef"
      :class="[
        'card-nav block h-[60px] p-0 rounded-xl shadow-lg relative overflow-hidden will-change-[height] border border-gray-100',
        { open: isExpanded }
      ]"
      :style="{ backgroundColor: props.baseColor }"
    >
      <div class="card-nav-top top-0 z-[2] absolute inset-x-0 flex justify-between items-center px-4 h-[60px]">
        
        <div
          :class="[
            'hamburger-menu group h-full flex flex-col items-center justify-center cursor-pointer gap-[6px] order-2 md:order-none',
            { open: isHamburgerOpen }
          ]"
          @click="toggleMenu"
          role="button"
          :aria-label="isExpanded ? 'Close menu' : 'Open menu'"
          :style="{ color: props.menuColor }"
        >
          <div :class="['w-[24px] h-[2px] bg-current transition-all duration-300 origin-center', { 'translate-y-[4px] rotate-45': isHamburgerOpen }]" />
          <div :class="['w-[24px] h-[2px] bg-current transition-all duration-300 origin-center', { '-translate-y-[4px] -rotate-45': isHamburgerOpen }]" />
        </div>

        <div class="md:absolute md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 flex items-center order-1 md:order-none">
          <img :src="props.logo" :alt="props.logoAlt" class="h-[24px] w-auto object-contain" />
        </div>

        <button
          type="button"
          class="hidden md:inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-300"
          :style="{ backgroundColor: props.buttonBgColor, color: props.buttonTextColor }"
        >
          Get Started
        </button>
      </div>

      <div
        class="absolute left-0 right-0 top-[60px] bottom-0 p-2 flex flex-col md:flex-row items-stretch md:items-end gap-2 z-[1]"
        :class="isExpanded ? 'visible pointer-events-auto' : 'invisible pointer-events-none'"
        :aria-hidden="!isExpanded"
      >
        <div
          v-for="(item, idx) in (props.items || []).slice(0, 3)"
          :key="`${item.label}-${idx}`"
          :ref="setCardRef(idx)"
          class="relative flex flex-col flex-1 gap-2 p-4 rounded-lg min-h-[80px] md:h-full justify-between group cursor-default"
          :style="{ backgroundColor: item.bgColor, color: item.textColor }"
        >
          <div class="font-medium text-lg md:text-xl tracking-tight">
            {{ item.label }}
          </div>
          
          <div class="flex flex-col gap-1 mt-auto">
            <a
              v-for="(lnk, i) in item.links"
              :key="`${lnk.label}-${i}`"
              class="inline-flex items-center gap-1 opacity-80 hover:opacity-100 transition-opacity text-sm cursor-pointer hover:underline"
              :href="lnk.href || '#'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
              </svg>
              {{ lnk.label }}
            </a>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>