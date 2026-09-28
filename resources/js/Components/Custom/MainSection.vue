<script setup>
import { inject } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Phone, ClipboardList } from 'lucide-vue-next';
const phone = inject('contactNumber');
</script>

<template>
  <section class="bg-cover bg-center bg-stone-700 bg-blend-multiply px-6 py-12 md:py-24 text-white text-center"
    style="background-image: url('/images/site/home-laundry-background.webp')">
    <div class="max-w-4xl mx-auto">
      <p class="text-sm font-semibold uppercase tracking-widest text-red-200">Local Family-Owned Service</p>
      <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl leading-tight font-semibold">Appliance Repair in Moorpark and Ventura County</h1>
      <p class="max-w-2xl mx-auto mt-5 text-base md:text-xl leading-8 text-gray-100">Residential and commercial appliance repair. Tell us what is wrong with your appliance and where you are located, and we’ll discuss service availability.</p>
      <div class="flex flex-col sm:flex-row justify-center gap-3 mt-7">
        <a :href="`tel:${phone}`" class="hero-cta bg-red-700 hover:bg-red-800"><Phone class="size-5" aria-hidden="true" />Call {{ phone }}</a>
        <Link href="/contact#service-request" class="hero-cta bg-white text-red-700 hover:bg-red-50"><ClipboardList class="size-5" aria-hidden="true" />Request Service</Link>
      </div>
    </div>
  </section>
</template>

<style scoped>
@reference "tailwindcss";
.hero-cta { @apply inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl font-semibold text-base transition-colors focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white; }
</style>
