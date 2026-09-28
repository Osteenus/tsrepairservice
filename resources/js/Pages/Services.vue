<script>
import Layout from '../Layouts/Layout.vue'
import OurServicesSection from '@/Components/Custom/OurServicesSection.vue';

export default {
    // Using a render function...
    // layout: (h, page) => h(Layout, [page]),

    // Using shorthand syntax...
    layout: Layout,
}
</script>

<script setup>
import { Head } from '@inertiajs/vue3';
import Breadcrumbs from '@/Components/Custom/Breadcrumbs.vue';
defineProps({ user: Object })
</script>

<template>
    <Head>
        <title>Appliance Repair Services Moorpark CA | TS Repair Service</title>
        <meta name="description" content="Explore appliance repair services in Moorpark and Ventura County, from refrigerators and washers to ovens and dishwashers. Contact TS Repair Service." head-key="description">
        <link rel="canonical" href="https://tsrepairservice.com/services" head-key="canonical">
        <meta property="og:title" content="Appliance Repair Services Moorpark CA | TS Repair Service">
        <meta property="og:description" content="Explore kitchen and laundry appliance repair services in Moorpark and Ventura County. Contact TS Repair Service to discuss your equipment and location.">
        <meta property="og:url" content="https://tsrepairservice.com/services">
        <meta property="og:type" content="website">
    </Head>
    <Breadcrumbs :items="[{ label: 'Services' }]" />

    <div style="background-image: url('/images/site/services-repair-background.webp')"
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24">Services</h1>
    </div>

    <OurServicesSection heading-tag="h2">

    </OurServicesSection>

</template>
