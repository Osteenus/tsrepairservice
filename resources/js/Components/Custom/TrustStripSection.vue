<template>
  <div class="flex flex-col bg-white">

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <dl class="grid grid-cols-1 md:grid-cols-4 gap-x-4 md:gap-x-24 gap-y-4 md:gap-y-16 text-center">
          <div data-aos="zoom-in-up" class=" flex flex-col">
            <div class="flex justify-center pb-2">
              <Clock class="w-18 h-18 text-red-600" />
            </div>
            <h3 class="text-2xl font-bold pt-4">Same-Day Service</h3>
          </div>
          <div data-aos="zoom-in-up" class=" flex flex-col">
            <div class="flex justify-center pb-2">
              <Building2 class="w-18 h-18 text-red-600" />
            </div>
            <h3 class="text-2xl font-bold pt-4">Residential & Commercial</h3>
          </div>
          <div data-aos="zoom-in-up" class=" flex flex-col">
            <div class="flex justify-center pb-2">
              <ShieldCheck class="w-18 h-18 text-red-600" />
            </div>
            <h3 class="text-2xl font-bold pt-4">Licensed & Insured</h3>
          </div>
          <div data-aos="zoom-in-up" class=" flex flex-col">
            <div class="flex justify-center pb-2">
              <Receipt class="w-18 h-18 text-red-600" />
            </div>
            <h3 class="text-2xl font-bold pt-4">Transparent Pricing</h3>
          </div>
        </dl>
      </div>
    </div>
  </div>


</template>

<script setup>

import { onMounted } from 'vue'
import Aos from 'aos'
import { Clock, Building2, ShieldCheck, Receipt } from 'lucide-vue-next'

onMounted(async () => {
  Aos.init()
})

</script>

<style scoped></style>
