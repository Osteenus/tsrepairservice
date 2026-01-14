<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/services/dishwasher-repair-1.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center leading-16 font-semibold py-24">Dishwasher Repair Service in Moorpark</h1>
    </div>

    <div class="bg-white flex flex-row md:flex-col m-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:pl-32 pt-8 flex flex-col">
                <h2 data-aos="fade-down" class="leading-12 text-3xl md:text-5xl text-center md:text-left text-black font-semibold py-12">Expert Dishwasher Repair for All Major Brands and Models</h2>
                <p data-aos="fade-right" class="leading-8 px-8 md:px-0">
                    A broken dishwasher can quickly turn everyday cleanup into a hassle. We provide professional dishwasher 
                    repair services in Moorpark, helping homeowners restore quiet, efficient, and leak-free operation. 
                    As a young, family-owned appliance repair business, we focus on honest diagnostics, fast response times, 
                    and high-quality repairs you can trust. We service built-in, portable, and drawer-style dishwashers, including 
                    premium brands like Bosch, KitchenAid, GE, Whirlpool, Samsung, LG, and Fisher & Paykel.
                </p>
            </div>
            <img data-aos="zoom-in-up" src="/storage/img/components/services/dishwasher-repair-2.jpg" alt="dishwasher repair" class="max-h-[600px] pb-12 md:py-8 md:pl-12">
        </div>

    </div>

    <div class="bg-slate-900 flex flex-col m-auto">
        <h3 data-aos="fade-right" class="leading-16 text-2xl text-white text-center font-semibold px-6 py-12">Dishwasher Problems We Fix</h3>
        
        <div class="flex flex-col md:flex-row justify-center md:px-24 pb-8 md:pb-24">
            <img data-aos="fade-down" class="basis-1/2 max-w-128 px-4 pb-6" src="/storage/img/components/services/dishwasher-repair-3.jpg" alt="dishwasher repair">
            <ul data-aos="zoom-in" class="basis-1/2 px-6 md:pl-24 text-white text-l md:text-xl">
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher not draining or draining slowly</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher not cleaning dishes properly</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Water leaking from door or bottom</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher not filling with water</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher not starting or stopping mid-cycle</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Loud or unusual noises during operation</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher leaving residue or spots on dishes</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Error codes on display or control panel</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher door not latching or sealing</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Dishwasher not drying dishes</li>
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

