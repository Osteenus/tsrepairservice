<script setup>
import { computed, onMounted, nextTick } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Aos from 'aos';
import { Phone } from 'lucide-vue-next';
import Layout from '@/Layouts/Layout.vue';
import MainSection from '@/Components/Custom/MainSection.vue';
import OurServicesSection from '@/Components/Custom/OurServicesSection.vue';
import BrandsSection from '@/Components/Custom/BrandsSection.vue';
import WhyChooseUsSection from '@/Components/Custom/WhyChooseUsSection.vue';
import ServiceAreaSection from '@/Components/Custom/ServiceAreaSection.vue';

defineOptions({ layout: Layout });
const page = usePage();
const phone = computed(() => page.props.repair.phone);
const registration = computed(() => page.props.repair.registration);
const title = 'Appliance Repair Moorpark CA | TS Repair Service';
const description = 'Residential and commercial appliance repair in Moorpark and Ventura County. Contact TS Repair Service to discuss your appliance, location, and service availability.';
const canonical = 'https://tsrepairservice.com/';
const schema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        { '@type': 'Organization', '@id': `${canonical}#organization`, name: 'TS Repair Service',
            legalName: registration.value.business_name, url: canonical, telephone: phone.value,
            logo: `${canonical}storage/img/logo.png`,
            identifier: { '@type': 'PropertyValue', propertyID: 'California BHGS Major Appliance Repair Registration', value: registration.value.number } },
        { '@type': 'WebSite', '@id': `${canonical}#website`, url: canonical, name: 'TS Repair Service', publisher: { '@id': `${canonical}#organization` } },
    ],
}).replace(/</g, '\\u003c'));
const reveal = (delay = 0) => ({
    'data-aos': 'fade-up', 'data-aos-once': 'true', 'data-aos-duration': '550',
    'data-aos-delay': String(delay), 'data-aos-offset': '40',
});
const steps = [
    { title: 'Tell Us About the Problem', text: 'Share your appliance type, symptoms, and city or ZIP code.' },
    { title: 'Confirm Service Availability', text: 'We review the equipment and location, then contact you to discuss scheduling.' },
    { title: 'Discuss the Repair', text: 'After diagnosis, we explain the findings and proposed work before proceeding.' },
];
onMounted(async () => { await nextTick(); Aos.refreshHard(); });
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta name="description" :content="description" head-key="description">
        <link rel="canonical" :href="canonical" head-key="canonical">
        <meta property="og:title" :content="title" head-key="og:title">
        <meta property="og:description" :content="description" head-key="og:description">
        <meta property="og:url" :content="canonical" head-key="og:url">
        <meta property="og:type" content="website" head-key="og:type">
        <meta property="og:image" content="https://tsrepairservice.com/images/site/home-laundry-background.webp" head-key="og:image">
        <component :is="'script'" type="application/ld+json" head-key="home-schema">{{ schema }}</component>
    </Head>

    <MainSection />
    <OurServicesSection heading-tag="h2" compact />
    <BrandsSection heading-tag="h2" class="home-brands" />
    <WhyChooseUsSection />

    <section class="max-w-7xl mx-auto px-6 py-10 md:py-12">
        <h2 class="text-3xl md:text-4xl font-semibold text-center text-slate-900">How It Works</h2>
        <div class="grid md:grid-cols-3 gap-5 mt-8">
            <article v-for="(step, index) in steps" :key="step.title" v-bind="reveal(index * 75)" class="rounded-2xl bg-gray-100 p-6">
                <span class="flex items-center justify-center size-10 rounded-full bg-red-700 text-white font-semibold" aria-hidden="true">{{ index + 1 }}</span>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ step.title }}</h3>
                <p class="mt-3 text-base leading-7 text-gray-600">{{ step.text }}</p>
            </article>
        </div>
    </section>

    <!-- CustomerReviewsSection is retained in the project pending verification of review sources. -->
    <ServiceAreaSection heading-tag="h2"
        description="We provide appliance repair in Moorpark, Ventura County, and surrounding communities, including Simi Valley and Santa Clarita. Share your city or ZIP code to confirm availability for your equipment." />

    <section v-bind="reveal()" class="m-4 md:m-12 rounded-3xl bg-slate-900 px-6 py-10 md:p-12 text-white text-center">
        <h2 class="text-3xl md:text-4xl font-semibold">Need Help With an Appliance?</h2>
        <p class="max-w-2xl mx-auto mt-4 text-base md:text-lg leading-8 text-slate-200">Call us or send the appliance type, brand, symptoms, and location. We’ll review your request and discuss service availability.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3 mt-6">
            <a :href="`tel:${phone}`" class="home-cta bg-red-700 hover:bg-red-800"><Phone class="size-5" aria-hidden="true" />Call {{ phone }}</a>
            <Link href="/contact#service-request" class="home-cta bg-white text-red-700 hover:bg-red-50">Request Service</Link>
        </div>
        <p class="mt-5 text-sm text-slate-300">A service request is not a confirmed appointment.</p>
    </section>
</template>

<style scoped>
@reference "tailwindcss";
.home-cta { @apply inline-flex items-center justify-center gap-2 rounded-2xl px-6 py-4 font-semibold text-base transition-colors focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white; }
.home-brands :deep([data-aos]) { opacity: 1 !important; transform: none !important; }
[data-aos="fade-up"] { transform: translate3d(0, 20px, 0); }
[data-aos="fade-up"].aos-animate, [data-aos]:focus-within { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
    [data-aos] { opacity: 1 !important; transform: none !important; transition: none !important; }
}
</style>
