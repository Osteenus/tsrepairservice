<template>
    <component :is="headingTag" :class="compact ? 'mt-10 text-3xl md:text-4xl' : 'mt-10 text-5xl'" class="text-center font-semibold px-4">What We Repair</component>
    <div class="bg-white" :class="compact ? 'home-services py-8 md:py-10' : 'py-16'">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <ul class="grid grid-cols-2 gap-x-4 gap-y-4 md:grid-cols-3 md:gap-x-8 md:gap-y-16 animate-appear text-center">
                <li v-for="(service, index) in ourServices" :key="service.id" :data-aos="compact ? 'fade-up' : undefined" :data-aos-once="compact ? 'true' : undefined" :data-aos-duration="compact ? '500' : undefined" :data-aos-delay="compact ? (index % 3) * 50 : undefined" class="flex max-w-xs flex-col p-4">
                    <a :href="service.url" class="service-card block">
                    <img :data-aos="compact ? undefined : 'zoom-in'" :src="service.logoUrl" loading="lazy" decoding="async" :alt="service.name" class="rounded-3xl">
                    <div :data-aos="compact ? undefined : 'zoom-in'" class="pt-2 text-lg text black">{{ service.name }}</div>
                </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

import { onMounted, inject } from 'vue'
import Layout from '../../Layouts/Layout.vue'
import Aos from 'aos';

defineProps({ headingTag: { type: String, default: 'h1' }, compact: { type: Boolean, default: false } });
const ourServices = inject('services')

onMounted( async () => {
    Aos.init()
})

</script>

<style scoped>
.home-services ul { gap: 24px; }
.home-services li { width:100%; max-width:none; padding:0; }
.service-card { height:100%; border:1px solid #e5e7eb; border-radius:20px; padding:12px; background:#fff; }
.service-card img { width:100%; aspect-ratio:4/3; object-fit:contain; background:#f8fafc; border-radius:12px; }
.service-card:hover { border-color:#cbd5e1; }
.home-services a:hover { background: #f9fafb; }
.home-services a:focus-visible { outline: 2px solid #b91c1c; outline-offset: 3px; }
.home-services img { width: 100%; aspect-ratio: 4 / 3; object-fit: contain; }
.home-services [data-aos="fade-up"] { transform: translate3d(0, 16px, 0); }
.home-services [data-aos].aos-animate, .home-services [data-aos]:focus-within { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
  .home-services [data-aos] { opacity: 1 !important; transform: none !important; transition: none !important; }
}
@media (min-width: 768px) { .home-services ul { gap: 24px 32px; } }


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
