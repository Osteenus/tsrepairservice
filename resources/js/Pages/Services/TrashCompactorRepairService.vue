<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/services/trash-compactor-repair-1.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24 leading-16">Trash Compactor Repair Service in Moorpark</h1>
    </div>

    <div class="bg-white flex flex-row md:flex-col m-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:pl-32 pt-8 flex flex-col">
                <h2 data-aos="fade-down" class="leading-12 text-3xl md:text-5xl text-center md:text-left text-black font-semibold py-12 px-4">Expert Trash Compactor Repair for All Major Brands and Models</h2>
                <p data-aos="fade-right" class="leading-8 px-6 md:px-0">
                    A malfunctioning trash compactor can quickly create inconvenience and 
                    odors in your kitchen. We provide professional trash compactor repair 
                    services in Moorpark, restoring smooth operation, safe compaction, and 
                    reliable performance. As a young, family-owned appliance repair company, 
                    we deliver honest diagnostics, fast service, and dependable repairs 
                    you can trust.

                    We service built-in and under-counter trash compactors from all major brands.
                </p>
            </div>
            <img data-aos="zoom-in-up" src="/storage/img/components/services/trash-compactor-repair-2.jpg" alt="trash compactor repair" class="max-h-[600px] pb-12 md:py-8 md:pl-12">
        </div>

    </div>

    <div class="bg-slate-900 flex flex-col m-auto">
        <h3 data-aos="fade-right" class="leading-16 text-2xl text-white text-center font-semibold px-6 py-12">Trash Compactor Problems We Fix</h3>
        
        <div class="flex flex-col md:flex-row justify-center md:px-24 pb-8 md:pb-24">
            <img data-aos="fade-down" class="basis-1/2 object-contain max-h-108 px-4 pb-6" src="/storage/img/components/services/trash-compactor-repair-3.jpg" alt="trash compactor repair">
            <ul data-aos="zoom-in" class="basis-1/2 px-6 md:pl-24 text-white text-l md:text-xl">
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Trash compactor not turning on</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Compactor ram not moving or stuck</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Compactor won’t compress trash</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Loud grinding or clicking noises</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Drawer won’t open or close properly</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Compactor stops mid-cycle</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Control buttons not responding</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Bad odors caused by leaks or residue buildup</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Safety switch issues</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Motor overheating or shutting off</li>
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

