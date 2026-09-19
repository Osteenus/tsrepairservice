<template>
  <div class="flex flex-col bg-white">

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <dl class="grid grid-cols-1 md:grid-cols-4 gap-x-4 md:gap-x-24 gap-y-4 md:gap-y-16 text-center">
          <div v-for="item in items" :key="item.title" data-aos="zoom-in-up" class="flex flex-col">
            <div class="flex justify-center pb-2">
              <component :is="item.icon" class="w-18 h-18 text-red-600" aria-hidden="true" />
            </div>
            <h3 class="text-2xl font-bold pt-4">{{ item.title }}</h3>
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

defineProps({
  items: {
    type: Array,
    default: () => [
      { title: 'Same-Day Service', icon: Clock },
      { title: 'Residential & Commercial', icon: Building2 },
      { title: 'Licensed & Insured', icon: ShieldCheck },
      { title: 'Transparent Pricing', icon: Receipt },
    ],
  },
})

onMounted(async () => {
  Aos.init()
})

</script>

<style scoped></style>
