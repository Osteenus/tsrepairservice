<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/services/dryer-repair-1.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24 leading-16">Dryer Repair Service in Moorpark</h1>
    </div>

    <div class="bg-white flex flex-row md:flex-col m-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:pl-32 pt-8 flex flex-col">
                <h2 data-aos="fade-down" class="leading-12 text-3xl md:text-5xl text-center md:text-left text-black font-semibold py-12 px-4">Expert Dryer Repair for All Major Brands and Models</h2>
                <p data-aos="fade-right" class="leading-8 px-6 md:px-0">
                    A broken dryer can quickly disrupt your routine, especially when clothes won’t dry or the unit overheats. 
                    We provide professional dryer repair services in Moorpark, helping homeowners restore safe and efficient 
                    operation fast. As a young, family-owned business, we take pride in honest diagnostics, quality workmanship, 
                    and friendly service you can rely on.We repair electric and gas dryers, servicing all major brands including 
                    LG, Samsung, Whirlpool, GE, Maytag, Bosch, and more.
                </p>
            </div>
            <img data-aos="zoom-in-up" src="/storage/img/components/services/dryer-repair-2.jpg" alt="dryer repair" class="max-h-[600px] pb-12 md:py-8 md:pl-12">
        </div>

    </div>

    <div class="bg-slate-900 flex flex-col m-auto">
        <h3 data-aos="fade-right" class="leading-16 text-2xl text-white text-center font-semibold px-6 py-12">Dryer Problems We Fix</h3>
        
        <div class="flex flex-col md:flex-row justify-center md:px-24 pb-8 md:pb-24">
            <img data-aos="fade-down" class="basis-1/2 max-w-128 px-4 pb-6" src="/storage/img/components/services/dryer-repair-3.jpeg" alt="dryer repair">
            <ul data-aos="zoom-in" class="basis-1/2 px-6 md:pl-24 text-white text-l md:text-xl">
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dryer not heating or not drying clothes</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dryer takes too long to dry</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dryer won’t start or suddenly stops</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Drum not spinning or squealing noises</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Burning smell or excessive heat</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dryer shuts off mid-cycle</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Error codes or control panel not responding</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dryer making loud banging or rattling noises</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Clothes coming out wrinkled or damp</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Gas dryer not igniting</li>
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

