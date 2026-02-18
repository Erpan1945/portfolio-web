<template>
  <section class="relative py-24 bg-white overflow-hidden min-h-screen flex flex-col justify-center">
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full text-center select-none pointer-events-none z-10">
       <h2 class="text-[20vw] md:text-[25vw] font-black text-transparent leading-none tracking-tighter"
           style="-webkit-text-stroke: 2px black;">
          ourGallery
       </h2>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-20 w-full">
      
      <div class="flex flex-col md:flex-row justify-between items-start mb-16 md:mb-32">
         <div class="md:max-w-xs mb-8 md:mb-0">
           <p class="text-xl font-bold text-gray-900 leading-tight">
             Photography is the art, application, and practice of creating durable images.
           </p>
         </div>
         <div class="md:max-w-xs md:text-right">
            <p class="text-xl font-bold text-gray-900 mb-4 leading-tight">
              The International Center of Photography in New York City is the world's.
            </p>
            <a href="#" class="inline-flex items-center font-black text-black hover:underline text-lg">
              READ MORE <span class="ml-2 text-xl">→</span>
            </a>
         </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-10">
        
        <div v-for="(card, index) in cards" :key="card.id"
             class="relative rounded-[3rem] overflow-hidden aspect-4/7 shadow-xl transition-transform hover:-translate-y-2 duration-300"
             :class="[
                card.bgColor, // Kelas warna background dinamis
                // Logika Zig-Zag: Jika index ganjil (1, 3), tambahkan margin atas besar
                { 'mt-12 md:mt-32': index % 2 !== 0 }
             ]">
             
             <div class="absolute inset-0 p-3 md:p-5 h-full w-full">
                
                <div class="h-full w-full rounded-[2.5rem] overflow-hidden transform -rotate-6 scale-105 origin-center shadow-sm relative">
                    
                    <img :src="card.img" alt="Gallery Image" 
                         class="w-full h-full object-cover transform rotate-6 scale-125 origin-center transition-transform duration-700 hover:scale-150">
                         
                    <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent pointer-events-none"></div>
                </div>
             </div>

        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

// Data Mockup untuk 4 Kartu
// Nanti bisa diganti dengan data dari database (props)
const cards = ref([
  { 
    id: 1, 
    // Warna Merah (Sesuai referensi)
    bgColor: 'bg-[#E83B46]', 
    // Ganti dengan foto projectmu nanti. Ini placeholder yang mirip referensi.
    img: 'https://images.unsplash.com/photo-1563713753-43097001e472?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80' 
  }, 
  { 
    id: 2, 
    // Warna Kuning
    bgColor: 'bg-[#FDD32A]', 
    img: 'https://images.unsplash.com/photo-1494959764136-6be9eb3c261e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
  }, 
  { 
    id: 3, 
    // Warna Oranye
    bgColor: 'bg-[#FA5D37]', 
    img: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
  }, 
  { 
    id: 4, 
    // Warna Ungu Muda
    bgColor: 'bg-[#CE96D8]', 
    img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
  },
]);
</script>