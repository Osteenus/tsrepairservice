<template>
  <div :style="{ 'background-image': 'url(' + '../storage/img/components/laundromat-room.jpg' + ')' }"
    class="py-14 md:py-28 bg-cover bg-center bg-stone-600 bg-blend-multiply">
    <h1 data-aos="zoom-in-left"
      class="relative opacity-100 top-2 pb-5 text-white text-center text-5xl leading-18 font-bold">Fast & Reliable
      Appliance Repair in Moorpark, CA</h1>
    <h3 data-aos="zoom-in-left" class="relative opacity-100 top-2 text-white text-center text-3xl px-6 leading-12">
      Same-day service, certified technicians, guaranteed repairs.</h3>
    <a href="/contact#schedule" class="flex justify-center py-10">
      <button data-aos="zoom-in-left" class="
               flex
                btn-primary 
                text-xl
                cursor-pointer
                py-3
                transition 
                duration-300
                over:bg-red-600
              ">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M3 8.50002C3 6.6144 3 5.67159 3.58579 5.08581C4.17157 4.50002 5.11438 4.50002 6.99999 4.50002L17 4.5C18.8856 4.5 19.8284 4.5 20.4142 5.08578C21 5.67157 21 6.61438 21 8.5V17C21 18.8856 21 19.8285 20.4142 20.4142C19.8284 21 18.8856 21 17 21H7C5.11438 21 4.17157 21 3.58579 20.4142C3 19.8285 3 18.8856 3 17V8.50002Z"
            stroke="#ffffff" stroke-width="1.5" class="my-path"></path>
          <path d="M3 10H21" stroke="#ffffff" stroke-width="1.5" class="my-path"></path>
          <path d="M8.05 14L8 14" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M8.05 17L8 17" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M12.05 14L12 14" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M12.05 17L12 17" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M16.05 14L16 14" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M16.05 17L16 17" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M8 3V6" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
          <path d="M16 3L16 6" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" class="my-path"></path>
        </svg>
        <span class="px-2">BOOK ONLINE</span>
      </button>
    </a>
  </div>
</template>

<script setup>

import Aos from 'aos';
import { onMounted } from 'vue';

onMounted(async () => {
  Aos.init()
})

</script>

<style>
.fade-left-enter-active,
.fade-left-leave-active {
  transition: top 1s ease, opacity 1s ease;
}

.fade-left-enter-from,
.fade-left-leave-to {

  top: 0;
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<!--

transition: opacity 0.8s ease;


opacity: 0;
-->