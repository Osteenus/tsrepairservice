<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/services/range-hood-repair-1.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24 leading-16">Range Hood Repair Service in Moorpark</h1>
    </div>

    <div class="bg-white flex flex-row md:flex-col m-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:pl-32 pt-8 flex flex-col">
                <h2 data-aos="fade-down" class="leading-12 text-3xl md:text-5xl text-center md:text-left text-black font-semibold py-12 px-4">Expert Range Hood Repair for All Major Brands and Models</h2>
                <p data-aos="fade-right" class="leading-8 px-6 md:px-0">
                    A malfunctioning range hood can leave your kitchen filled with smoke, odors, 
                    and excess grease. We provide professional range hood repair services in Moorpark, 
                    restoring proper ventilation, airflow, and quiet operation. As a young, family-owned 
                    appliance repair company, we offer honest diagnostics, fast service, and reliable 
                    repairs you can trust.

                    We repair under-cabinet, wall-mounted, island, insert, and built-in range hoods, 
                    including ducted and ductless models.
                </p>
            </div>
            <img data-aos="zoom-in-up" src="/storage/img/components/services/range-hood-repair-2.jpg" alt="range hood repair" class="max-h-[600px] pb-12 md:py-8 md:pl-12">
        </div>

    </div>

    <div class="bg-slate-900 flex flex-col m-auto">
        <h3 data-aos="fade-right" class="leading-16 text-2xl text-white text-center font-semibold px-6 py-12">Range Hood Problems We Fix</h3>
        
        <div class="flex flex-col md:flex-row justify-center md:px-24 pb-8 md:pb-24">
            <img class="basis-1/2 object-contain max-h-108 px-4 pb-6" src="/storage/img/components/services/range-hood-repair-3.jpeg" alt="range hood repair">
            <ul data-aos="zoom-in" class="basis-1/2 px-6 md:pl-24 text-white text-l md:text-xl">
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Range hood not turning on</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Weak airflow or poor ventilation</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />OLoud or unusual noises during operation</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Fan not working or spinning slowly</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Lights not turning on or flickering</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Range hood vibrating or rattling</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Error codes on digital controls</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Control buttons or touch panel not responding</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Hood shutting off unexpectedly</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Door not closing properly or heat escaping</li>
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

