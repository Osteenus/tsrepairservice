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
                    <img :src="brand.logoUrl" :alt="brand.name" class="py-6 px-2 md:py-16 md:px-8">
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
        logoUrl: '/storage/img/components/brands/admiral-brand-logo.png'
    },
    {
        name: 'Amana',
        logoUrl: '/storage/img/components/brands/amana-brand-logo.png'
    },
    {
        name: 'Bosch',
        logoUrl: '/storage/img/components/brands/bosch-brand-logo.png'
    },
    {
        name: 'Electrolux',
        logoUrl: '/storage/img/components/brands/electrolux-brand-logo.png'
    },
    {
        name: 'Frigidaire',
        logoUrl: '/storage/img/components/brands/frigidaire-brand-logo.png'
    },
    {
        name: 'General Electric',
        logoUrl: '/storage/img/components/brands/ge-brand-logo.png'
    },
    {
        name: 'Haier',
        logoUrl: '/storage/img/components/brands/haier-brand-logo.png'
    },
    {
        name: 'Hotpoint',
        logoUrl: '/storage/img/components/brands/hotpoint-brand-logo.png'
    },
    {
        name: 'Jenn-Air',
        logoUrl: '/storage/img/components/brands/jennair-brand-logo.png'
    },
    {
        name: 'Kenmore',
        logoUrl: '/storage/img/components/brands/kenmore-brand-logo.png'
    },
    {
        name: 'KitchenAid',
        logoUrl: '/storage/img/components/brands/kitchenaid-brand-logo.png'
    },
    {
        name: 'LG',
        logoUrl: '/storage/img/components/brands/lg-brand-logo.png'
    },
    {
        name: 'Magic Chief',
        logoUrl: '/storage/img/components/brands/magic-chef-brand-logo.png'
    },
    {
        name: 'Maytag',
        logoUrl: '/storage/img/components/brands/maytag-brand-logo.png'
    },
    {
        name: 'Roper',
        logoUrl: '/storage/img/components/brands/roper-brand-logo.png'
    },
    {
        name: 'Samsung',
        logoUrl: '/storage/img/components/brands/samsung-brand-logo.png'
    },
    {
        name: 'SEARS',
        logoUrl: '/storage/img/components/brands/sears-brand-logo.png'
    },
    {
        name: 'U-Line',
        logoUrl: '/storage/img/components/brands/uline-brand-logo.png'
    },
    {
        name: 'Whirlpool',
        logoUrl: '/storage/img/components/brands/whirlpool-brand-logo.png'
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
