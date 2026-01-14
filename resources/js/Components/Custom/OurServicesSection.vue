<template>
    <h1 class="mt-10 text-5xl text-center font-semibold">What We Repair</h1>
    <div class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <dl class="grid grid-cols-2 gap-x-4 gap-y-4 md:grid-cols-3 md:gap-x-8 md:gap-y-16 animate-appear text-center">
                <a v-for="service in ourServices" :href="service.url" :key="service.id" class="flex max-w-xs flex-col p-4 cursor-pointer">
                    <img data-aos="zoom-in" :src="service.logoUrl" :alt="service.name" class="rounded-3xl">
                    <div data-aos="zoom-in" class="pt-2 text-lg text black">{{ service.name }}</div>
                </a>
            </dl>
        </div>
    </div>
</template>

<script setup>

import { onMounted, inject } from 'vue'
import Layout from '../../Layouts/Layout.vue'
import Aos from 'aos';

const ourServices = inject('services')

onMounted( async () => {
    Aos.init()
})

</script>

<style scoped>

.fade-scale-enter-active, .fade-scale-leave-active {
    transition: all 1s ease;
}
.fade-scale-enter-from, .fade-scale-leave-to {
    opacity: 0;
    transform: scale(.9);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

</style>
