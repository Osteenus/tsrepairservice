<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/services/washer-repair-1.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24 leading-16">Washer Repair Service in Moorpark</h1>
    </div>

    <div class="bg-white flex flex-row md:flex-col m-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:pl-32 pt-8 flex flex-col">
                <h2 data-aos="fade-down" class="leading-12 text-3xl md:text-5xl text-center md:text-left text-black font-semibold py-12 px-4">
                    Expert Washer Repair for All Major Brands and Models
                </h2>
                <p data-aos="fade-right" class="leading-8 px-6 md:px-0">
                    When your washer stops working, you need help fast. Our certified appliance technicians 
                    specialize in washer repair in Moorpark, servicing all major brands like LG, Whirlpool, 
                    Samsung, GE, Maytag, and Bosch. Whether your machine won’t drain, spin, or even turn on — 
                    we’ll get it back to perfect working condition quickly and affordably.We’re a young, 
                    family-owned business proud to serve our local community with honest, high-quality service 
                    and friendly customer care.
                </p>
            </div>
            <img data-aos="zoom-in-up" src="/storage/img/components/services/washer-repair-2.jpeg" alt="washer repair" class="max-h-[600px] pb-12 md:pb-0 md:pl-12">
        </div>

    </div>

    <div class="bg-slate-900 flex flex-col m-auto">
        <h3 data-aos="fade-right" class="leading-16 text-2xl text-white text-center font-semibold px-6 py-12">Washer Problems We Fix</h3>
        
        <div class="flex flex-col md:flex-row justify-center md:px-24 pb-8 md:pb-24">
            <img data-aos="fade-down" class="basis-1/2 max-w-128 px-6 pb-4" src="/storage/img/components/services/washer-repair-3.jpeg" alt="washer repair">
            <ul data-aos="zoom-in" class="basis-1/2 px-6 md:pl-24 text-white text-l md:text-xl">
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Washer won’t start or power on</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Washer won’t spin or agitate</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Water not draining or draining slowly</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Washer leaking from bottom or door</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Strange noises or vibrations during cycle</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Washer not filling with water</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Burning smell or electrical issues</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Error codes on display or control board failure</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Clothes stay wet after cycle</li>
                <li class="flex flex-row items-center pb-2"><CheckIcon class="size-10 pr-2 text-white" />Washer shaking and walking when spining</li>
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

