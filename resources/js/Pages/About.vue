<script setup>
import Breadcrumbs from '@/Components/Custom/Breadcrumbs.vue';
import { computed, nextTick, onMounted } from 'vue';
import Aos from 'aos';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { House, Building2, MessagesSquare, SearchCheck, ClipboardList, Wrench, ArrowUpRight, MapPin, Phone } from 'lucide-vue-next';
import Layout from '@/Layouts/Layout.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
// TrustStripSection initializes the shared AOS instance; refresh after this page mounts.
onMounted(async () => {
    await nextTick();
    Aos.refreshHard();
});
const reveal = (delay = 0) => ({
    'data-aos': 'fade-up',
    'data-aos-duration': '550',
    'data-aos-delay': String(delay),
    'data-aos-offset': '40',
    'data-aos-once': 'true',
    'data-aos-easing': 'ease-out',
});
const page = usePage();
const phone = computed(() => page.props.repair.phone);
const services = computed(() => page.props.repair.services);
const title = 'About Us | Moorpark CA | TS Repair Service';
const description = 'Meet TS Repair Service, a local family-owned appliance repair business serving Moorpark and surrounding areas. Learn about our approach to diagnostics and service for homes and businesses.';
const canonical = 'https://tsrepairservice.com/about';
const trustItems = [
    { title: 'Local Family-Owned Service', icon: House },
    { title: 'Residential & Commercial', icon: Building2 },
    { title: 'Clear Communication', icon: MessagesSquare },
    { title: 'Careful Diagnostics', icon: SearchCheck },
];
const approach = [
    { title: 'Start With the Symptoms', icon: ClipboardList, text: 'Tell us what changed, when the issue occurs, and whether you see an error code. Your observations help guide the diagnosis.' },
    { title: 'Check Before Replacing', icon: SearchCheck, text: 'A leak, noise, or loss of power can have more than one cause. We examine the appliance before recommending a repair.' },
    { title: 'Explain the Options', icon: MessagesSquare, text: 'We discuss the findings and proposed work so you can decide how to proceed. The appliance’s condition and parts availability matter.' },
    { title: 'Pay Attention to the Details', icon: Wrench, text: 'We take care around your appliance and workspace, and explain what was addressed and any next steps after the repair.' },
];
</script>

<template>
    <Breadcrumbs :items="[{ label: 'About Us' }]" />
    <Head>
        <title>{{ title }}</title>
        <meta name="description" :content="description" head-key="description">
        <link rel="canonical" :href="canonical" head-key="canonical">
        <meta property="og:title" :content="title" head-key="og:title">
        <meta property="og:description" :content="description" head-key="og:description">
        <meta property="og:url" :content="canonical" head-key="og:url">
        <meta property="og:type" content="website" head-key="og:type">
    </Head>

    <section class="bg-slate-900 text-white text-center px-6 py-10 md:py-14">
        <p class="text-sm uppercase tracking-widest font-semibold text-red-300">Local Service · Moorpark, California</p>
        <h1 class="text-3xl md:text-5xl font-semibold leading-tight mt-3">About TS Repair Service</h1>
        <p class="mt-5 max-w-2xl mx-auto text-slate-200 leading-7 md:text-lg">Appliance repair with a personal approach, careful diagnosis, and a clear conversation about what comes next.</p>
    </section>

    <section class="max-w-7xl mx-auto grid md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-10 md:p-12">
        <div v-bind="reveal()">
            <p class="eyebrow">A Local, Family-Owned Business</p>
            <h2 class="text-3xl lg:text-4xl font-semibold leading-tight text-slate-900 mt-3">People You Can Talk to About Your Repair</h2>
            <p class="body-copy mt-5">TS Repair Service is a family-owned business based in Moorpark, serving homes and businesses in Ventura County and surrounding service areas. We help with kitchen and laundry appliances, as well as selected electronic controls and circuit boards.</p>
            <p class="body-copy mt-4">A broken appliance disrupts your day. Our approach starts with listening to the problem, checking the equipment, and explaining the available repair options in plain language.</p>
            <div class="flex flex-col gap-3 mt-6">
                <a :href="`tel:${phone}`" class="call-button"><Phone class="size-5" aria-hidden="true" />Call {{ phone }}</a>
                <Link href="/contact#service-request" class="request-button">Request Service</Link>
            </div>
        </div>
        <figure v-bind="reveal(100)" class="overflow-hidden rounded-3xl border border-gray-200 bg-gray-100">
            <img src="/storage/img/components/technician-1.jpg" alt="Technician examining an appliance component at a workbench"
                width="657" height="549" fetchpriority="high" class="w-full aspect-[6/5] object-cover">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="about-trust" />

    <section class="section-card bg-gray-100">
        <h2 class="section-title">Our Approach to Repair</h2>
        <p class="body-copy max-w-2xl mx-auto text-center mb-8">Good service means understanding the problem and helping you make an informed decision about your appliance.</p>
        <div class="grid sm:grid-cols-2 gap-5">
            <article v-for="(item, index) in approach" :key="item.title" v-bind="reveal((index % 2) * 100)" class="rounded-2xl bg-white p-6 md:p-8">
                <component :is="item.icon" class="size-11 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ item.title }}</h3>
                <p class="body-copy mt-3">{{ item.text }}</p>
            </article>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-10 md:p-12">
        <h2 class="section-title">What We Can Help With</h2>
        <p class="body-copy text-center max-w-2xl mx-auto mb-8">Explore the equipment and common problems we work on. Service depends on the appliance type, model, location, and parts availability.</p>
        <div v-bind="reveal()" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <Link v-for="service in services" :key="service.id" :href="service.url" class="service-link flex items-center justify-between gap-4 rounded-2xl border border-gray-200 p-5 text-slate-900 font-semibold hover:border-red-700 hover:bg-red-50 transition-colors">
                <span>{{ service.name }}</span><ArrowUpRight class="size-5 shrink-0 text-red-700" aria-hidden="true" />
            </Link>
        </div>
    </section>

    <section class="section-card bg-slate-900 text-white">
        <h2 class="section-title text-white">For Homes & Businesses</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <article v-bind="reveal()" class="rounded-2xl bg-slate-800 p-6 md:p-8">
                <House class="size-9 text-red-300" aria-hidden="true" />
                <h3 class="text-xl font-semibold mt-4">Residential Appliance Repair</h3>
                <p class="mt-3 leading-7 text-slate-200">From a refrigerator that will not cool to a washer that will not drain, we help homeowners understand what is wrong and whether a repair is a practical next step.</p>
            </article>
            <article v-bind="reveal(100)" class="rounded-2xl bg-slate-800 p-6 md:p-8">
                <Building2 class="size-9 text-red-300" aria-hidden="true" />
                <h3 class="text-xl font-semibold mt-4">Commercial Service Inquiries</h3>
                <p class="mt-3 leading-7 text-slate-200">For equipment in a business or shared facility, send us the appliance type, brand, model, and location. We will confirm whether the equipment is within our service scope before discussing scheduling.</p>
            </article>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-8 md:p-12">
        <div v-bind="reveal()" class="rounded-3xl bg-gray-100 p-6 md:p-8">
            <p class="eyebrow">Close to Home</p>
            <h2 class="text-2xl font-semibold text-slate-900 mt-3">Based in Moorpark</h2>
            <p class="body-copy mt-4">We serve Moorpark and nearby communities in Ventura County, including Simi Valley, as well as Santa Clarita. Tell us your city or ZIP code so we can confirm coverage for your appliance.</p>
            <Link href="/contact#service-request" class="service-link inline-flex items-center gap-2 text-red-700 font-semibold py-3 mt-2 underline underline-offset-4"><MapPin class="size-5" aria-hidden="true" />Check Service Availability</Link>
        </div>
    </section>

    <section v-bind="reveal()" class="section-card bg-slate-900 text-white text-center">
        <h2 class="section-title text-white">Tell Us What Needs Attention</h2>
        <p class="max-w-2xl mx-auto leading-8 text-slate-200">Have your appliance type, brand, symptoms, and city or ZIP code ready. We’ll review the details and contact you to discuss service availability.</p>
        <div class="flex flex-col items-center gap-3 mt-6">
            <a :href="`tel:${phone}`" class="call-button"><Phone class="size-5" aria-hidden="true" />Call {{ phone }}</a>
            <Link href="/contact#service-request" class="request-button">Request Service</Link>
        </div>
        <p class="mt-5 text-sm leading-6 text-slate-300">Submitting a request does not confirm an appointment.</p>
    </section>
</template>

<style scoped>
@reference "tailwindcss";
.eyebrow { @apply text-xs font-semibold uppercase tracking-widest text-red-700; }
.body-copy { @apply text-base leading-7 text-gray-600; }
.section-card { @apply rounded-3xl m-4 md:m-12 p-6 md:p-10 lg:p-12; }
.section-title { @apply text-2xl md:text-3xl font-semibold uppercase text-center mb-6; }
.call-button, .request-button { @apply flex items-center justify-center gap-2 w-full md:max-w-120 px-6 py-4 rounded-2xl text-base font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-red-500; }
.call-button { @apply bg-red-700 hover:bg-red-800 text-white shadow-md; }
.request-button { @apply bg-white text-red-700 border-2 border-red-700 hover:bg-red-50; }
.service-link { @apply focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-red-700; }
.about-trust :deep(dl) { @apply grid-cols-2 lg:grid-cols-4 gap-5; }
.about-trust :deep(h3) { @apply text-sm md:text-base pt-1; }
.about-trust :deep(svg) { @apply size-10; }
.about-trust :deep([data-aos]) { opacity: 1 !important; transform: none !important; }
/* Keep the motion small and reveal keyboard-focused content immediately. */
[data-aos="fade-up"] { transform: translate3d(0, 20px, 0); }
[data-aos="fade-up"].aos-animate,
[data-aos="fade-up"]:focus-within { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
    [data-aos] { opacity: 1 !important; transform: none !important; transition: none !important; }
}
</style>
