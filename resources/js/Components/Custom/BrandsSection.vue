<template>
    <div class="bg-blue-900 flex flex-col px-2 md:px-8 text-white">
        <component :is="headingTag" data-aos="zoom-in" class="title text-3xl text-center font-semibold pt-12">{{ title }}</component>
        <Carousel
            :items-to-show="1"
            breakpoint-mode="carousel"
            :breakpoints="{
                300: {
                    itemsToShow: 3,
                    snapAlign: 'start',
                },
                1000: {
                    itemsToShow: 6,
                    snapAlign: 'start',
                }
            }"
            >
            <Slide v-for="brand in visibleBrands" :key="brand.name">
                <div class="carousel__item">
                    <div class="bg-[url()]"></div>
                    <img :src="brand.logoUrl" loading="lazy" decoding="async" width="360" height="180" :alt="brand.name" class="py-6 px-2 md:py-16 md:px-8">
                </div> 
            </Slide>

            <template #addons>
                <Navigation />
            </template>
        </Carousel>
        <p class="title text-center text-2xl font-semibold pb-8">...and more</p>
    </div>
</template>

<script setup>

import { computed, onMounted } from 'vue'
import Aos from 'aos'
import 'vue3-carousel/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

onMounted( async () => {
    Aos.init()
})

const props = defineProps({
    title: { type: String, default: 'Brands We Service' },
    headingTag: { type: String, default: 'h1' },
    brandNames: { type: Array, default: null },
})
const visibleBrands = computed(() => props.brandNames
    ? brands.filter(brand => props.brandNames.includes(brand.name))
    : brands)

const brands = [
    {
        name: 'Admiral',
        logoUrl: '/images/brands/admiral-brand-logo.webp'
    },
    {
        name: 'Amana',
        logoUrl: '/images/brands/amana-brand-logo.webp'
    },
    {
        name: 'Bosch',
        logoUrl: '/images/brands/bosch-brand-logo.webp'
    },
    {
        name: 'Electrolux',
        logoUrl: '/images/brands/electrolux-brand-logo.webp'
    },
    {
        name: 'Frigidaire',
        logoUrl: '/images/brands/frigidaire-brand-logo.webp'
    },
    {
        name: 'General Electric',
        logoUrl: '/images/brands/ge-brand-logo.webp'
    },
    {
        name: 'Haier',
        logoUrl: '/images/brands/haier-brand-logo.webp'
    },
    {
        name: 'Hotpoint',
        logoUrl: '/images/brands/hotpoint-brand-logo.webp'
    },
    {
        name: 'Jenn-Air',
        logoUrl: '/images/brands/jennair-brand-logo.webp'
    },
    {
        name: 'Kenmore',
        logoUrl: '/images/brands/kenmore-brand-logo.webp'
    },
    {
        name: 'KitchenAid',
        logoUrl: '/images/brands/kitchenaid-brand-logo.webp'
    },
    {
        name: 'LG',
        logoUrl: '/images/brands/lg-brand-logo.webp'
    },
    {
        name: 'Magic Chief',
        logoUrl: '/images/brands/magic-chef-brand-logo.webp'
    },
    {
        name: 'Maytag',
        logoUrl: '/images/brands/maytag-brand-logo.webp'
    },
    {
        name: 'Roper',
        logoUrl: '/images/brands/roper-brand-logo.webp'
    },
    {
        name: 'Samsung',
        logoUrl: '/images/brands/samsung-brand-logo.webp'
    },
    {
        name: 'SEARS',
        logoUrl: '/images/brands/sears-brand-logo.webp'
    },
    {
        name: 'U-Line',
        logoUrl: '/images/brands/uline-brand-logo.webp'
    },
    {
        name: 'Whirlpool',
        logoUrl: '/images/brands/whirlpool-brand-logo.webp'
    },


]


</script>

<style scoped>

.carousel {
  --vc-nav-background: rgba(0, 0, 0, 0.3);
  --vc-nav-color: white;
  --vc-nav-color-hover: #e5e5e5;
  --vc-nav-border-radius: 50%;
  --vc-nav-width: 40px;
  --vc-nav-height: 40px;
}

</style>
