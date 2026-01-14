<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/services/refrigerator-repair-1.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24 leading-16">Refrigerator Repair Service in Moorpark</h1>
    </div>

    <div class="bg-white flex flex-row md:flex-col">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="md:pl-32 pt-8 px-4 flex flex-col">
                <h2 data-aos="fade-down" class="leading-14 text-3xl md:text-5xl text-center md:text-left text-black font-semibold py-12">
                    Expert Refrigerator & Freezer Repair for All Major Brands and Models
                </h2>
                <p data-aos="fade-right" class="leading-8 px-6 md:px-0">
                    Your refrigerator is one of the hardest-working appliances in your home, and when it stops cooling, 
                    leaks, or makes unusual noises, you need fast, reliable service you can trust. As a young, family-owned 
                    local business, we provide honest diagnostics, same-day repairs, and high-quality part replacements to 
                    keep your fridge running efficiently. Whether it's a cooling issue, a faulty control board, or a worn-out 
                    gasket, we deliver professional results at a fair price.
                </p>
            </div>
            <img data-aos="zoom-in-up" src="/storage/img/components/services/refrigerator-repair-4.webp" alt="refrigerator repair" class="max-h-[600px] pb-12 md:pb-0 md:pl-12">
        </div>
    </div>

    <div class="bg-slate-900 flex flex-col m-auto">
        <h3 data-aos="fade-right" class="leading-16 text-2xl text-white text-center font-semibold px-6 py-12">Refrigerator Problems We Fix</h3>
        
        <div class="flex flex-col md:flex-row justify-center md:px-24 pb-8 md:pb-24">
            <img data-aos="fade-down" class="basis-1/2 max-w-128 px-4 pb-6" src="/storage/img/components/services/refrigerator-repair-2.jpg" alt="refrigerator repair">
            <ul data-aos="zoom-in" class="basis-1/2 px-6 md:pl-24 text-white text-l md:text-xl">
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Refrigerator not cooling or cooling inconsistently</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Freezer not freezing or temperature fluctuations</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Water leaking from under or inside the fridge</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Ice maker not producing ice or producing too little</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Loud noises, rattling, or buzzing during operation</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Fridge running constantly or cycling too often</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Bad smells caused by blocked vents or mold buildup</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Ice buildup in freezer or around evaporator coils</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Error codes on digital display or control panel</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Water dispenser not working properly</li>
            </ul>
            
        </div>
    </div>
    <ScheduleFormSection>

    </ScheduleFormSection>



</template>

<script setup>

import { onMounted,inject } from 'vue';
import ScheduleFormSection from '@/Components/Custom/ScheduleFormSection.vue';
import { CheckIcon } from '@heroicons/vue/16/solid';
import Layout from '../../Layouts/Layout.vue'
import Aos from 'aos';

const ourServices = inject('services')

onMounted( async () => {
    Aos.init()
})

</script>

<script>

export default {
    layout: Layout
}    
</script>

