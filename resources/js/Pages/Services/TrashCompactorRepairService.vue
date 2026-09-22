<template>
    <Head>
        <title>Trash Compactor Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Trash Compactor Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <link rel="canonical" :href="canonicalUrl" head-key="canonical">
        <meta property="og:url" :content="canonicalUrl">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://tsrepairservice.com/images/services/trash-compactor-repair-moorpark.webp">
        <meta property="og:image:alt" content="Trash Compactor repair in Moorpark">
        <component :is="'script'" type="application/ld+json" head-key="trash-compactor-schema">{{ structuredDataJson }}</component>
    </Head>

    <nav aria-label="Breadcrumb" class="px-6 md:px-12 py-3 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><Link href="/" class="hover:text-red-700 underline">Home</Link></li>
            <li aria-hidden="true">/</li>
            <li><Link href="/services" class="hover:text-red-700 underline">Services</Link></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">Trash Compactor Repair</li>
        </ol>
    </nav>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/images/services/trash-compactor-drive.webp')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Trash Compactor Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential Trash Compactor Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Get Your Kitchen Compactor Working Again
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Drawer stuck, ram not returning, or a compactor that will not start? We diagnose household
                trash compactors in Moorpark and Ventura County and explain the repair options.
                Share your model and what happens during the cycle so we can plan the next step.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="w-full max-w-[400px] mx-auto overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/images/services/trash-compactor-repair-moorpark.webp" alt="Stainless steel under-counter trash compactor in a home kitchen"
                class="w-full h-auto max-h-[520px] object-contain" fetchpriority="high" width="400" height="400">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="trash-compactor-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in compactorTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Trash Compactor Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/images/services/trash-compactor-diagnostics.webp" alt="Trash compactor drive chain and gears exposed for diagnosis" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Typical Trash Compactor Repairs & Parts</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            A stuck drawer or stalled cycle can involve the drive mechanism, controls, or drawer alignment.
            We identify the cause before recommending parts. Age, condition, installation access, and
            parts availability all affect whether a repair is practical.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Repairing Your Kitchen Trash Compactor</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <House class="size-14 rounded-xl bg-red-50 p-3 text-red-700 mb-5" aria-hidden="true" />
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Built Into Your Kitchen</h3>
                <p>We focus on household built-in and under-counter compactors. Tell us if your unit has
                    a custom cabinet panel or if flooring or cabinetry limits removal. Access requirements
                    can affect the work needed to reach the drive and motor assemblies.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <Search class="size-14 rounded-xl bg-red-50 p-3 text-red-700 mb-5" aria-hidden="true" />
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Repair or Replace?</h3>
                <p>An isolated switch, drive-gear, or drawer-guide fault may be practical to repair.
                    We discuss the diagnosis and parts availability so you can compare repair with
                    replacement, including the cost of fitting a new unit into the existing space.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Trash Compactor Brands We Service" heading-tag="h2" :brand-names="compactorBrands" class="bg-white text-black" />
    <p class="text-center px-6 pb-8 leading-7">Have a different brand or an older model? Share the model number so we can confirm service and parts availability.</p>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Why Choose TS Repair Service</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 md:gap-8 md:px-12 pb-8 md:pb-24">
            <div v-for="item in whyChooseUsItems" :key="item.id" data-aos="zoom-in" class="flex flex-col m-4 p-6 bg-white text-slate-900 rounded-2xl text-xl">
                <div class="mx-auto border-3 border-slate-900 rounded-full p-2">
                    <svg v-html="item.iconSvgPath" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12" aria-hidden="true"></svg>
                </div>
                <div class="text-center pt-6">
                    <h3 class="font-semibold pb-2">{{ item.title }}</h3>
                    <p>{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white mx-4 my-12 md:m-12">
        <h2 class="section-title">How It Works</h2>
        <ol class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mt-4">
            <li v-for="(step, index) in steps" :key="step.title" data-aos="fade-up" class="flex flex-col items-center rounded-2xl border border-gray-200 bg-white px-6 py-8 text-center shadow-sm">
                <div class="relative flex size-18 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-700">
                    <component :is="step.icon" class="size-9" aria-hidden="true" />
                    <span aria-hidden="true" class="absolute -right-2 -top-2 flex size-7 items-center justify-center rounded-full bg-red-700 text-sm font-semibold text-white ring-4 ring-white">{{ index + 1 }}</span>
                </div>
                <h3 class="mt-6 text-xl font-semibold text-slate-900">{{ step.title }}</h3>
                <p class="mt-3 max-w-sm text-base leading-7 text-gray-600">{{ step.description }}</p>
            </li>
        </ol>
    </section>

    <ServiceAreaSection heading-tag="h2" description="We provide trash compactor repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Trash Compactor Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`trash-compactor-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`trash-compactor-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`trash-compactor-faq-answer-${index}`"
                        :aria-labelledby="`trash-compactor-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Trash Compactor Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>Get help with a stuck drawer, an interrupted cycle, or a compactor that will not start.
                Share the brand, model, and symptoms to discuss service for your kitchen.</p>
            <p class="pt-4">Call <a :href="`tel:${contactNumber}`" class="underline">{{ contactNumber }}</a>
                or send a service request with your appliance’s brand, model, and symptoms.</p>
            <div class="flex flex-col items-center mt-8 gap-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-7" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-7" aria-hidden="true" />Book Service</Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { inject, onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { CheckIcon, ChevronDownIcon } from '@heroicons/vue/16/solid';
import { PhoneIcon, CalendarDaysIcon, WrenchScrewdriverIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import { House, MessagesSquare, Search, PanelsTopLeft, Rows3 } from 'lucide-vue-next';
import Aos from 'aos';
import Layout from '@/Layouts/Layout.vue';
import BrandsSection from '@/Components/Custom/BrandsSection.vue';
import ServiceAreaSection from '@/Components/Custom/ServiceAreaSection.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
const contactNumber = inject('contactNumber');
const whyChooseUsItems = inject('whyChooseUsItems');
const description = 'Trash compactor repair in Moorpark and Ventura County. Help with stuck drawers, drive faults, noise and interrupted cycles. Contact TS Repair Service.';
const canonicalUrl = 'https://tsrepairservice.com/trash-compactor-repair-moorpark';
const structuredDataJson = JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'Service',
            '@id': `${canonicalUrl}#service`,
            name: 'Trash Compactor Repair',
            serviceType: 'Household trash compactor repair',
            url: canonicalUrl,
            description,
            image: 'https://tsrepairservice.com/images/services/trash-compactor-repair-moorpark.webp',
            provider: { '@type': 'Organization', name: 'TS Repair Service', url: 'https://tsrepairservice.com', telephone: contactNumber },
            areaServed: [
                { '@type': 'City', name: 'Moorpark' },
                { '@type': 'AdministrativeArea', name: 'Ventura County' },
            ],
        },
        {
            '@type': 'BreadcrumbList',
            itemListElement: [
                { '@type': 'ListItem', position: 1, name: 'Home', item: 'https://tsrepairservice.com/' },
                { '@type': 'ListItem', position: 2, name: 'Services', item: 'https://tsrepairservice.com/services' },
                { '@type': 'ListItem', position: 3, name: 'Trash Compactor Repair', item: canonicalUrl },
            ],
        },
    ],
}).replace(/</g, '\\u003c');
const trustItems = [
    { title: 'Local Family-Owned Service', icon: House },
    { title: 'Household Appliance Repair', icon: PanelsTopLeft },
    { title: 'Clear Communication', icon: MessagesSquare },
    { title: 'Careful Diagnostics', icon: Search },
];
const compactorTypes = [
    { title: 'Built-In Compactors', icon: PanelsTopLeft, description: 'Household units installed beneath kitchen counters, with drive, drawer, or control faults.' },
    { title: 'Panel-Ready Compactors', icon: House, description: 'Integrated models with custom drawer fronts; access and alignment discussed before a visit.' },
    { title: 'Older Household Models', icon: Rows3, description: 'Model-specific diagnosis with replacement-parts availability checked before proceeding.' },
];
const problems = [
    "Compactor will not start",
    "Ram does not move or return fully",
    "Drawer will not open or close",
    "Cycle stops before it finishes",
    "Motor hums without compacting",
    "Grinding, clicking, or unusual noise",
    "Little or no compaction during a cycle",
    "Start switch or key control does not respond",
    "Drawer catches or moves unevenly",
    "Motor shuts off unexpectedly"
];
const parts = [
    {
        "name": "Drive gears and sprockets",
        "description": "Inspecting worn or damaged drive components when the ram does not move correctly."
    },
    {
        "name": "Drive chains",
        "description": "Checking the chain and related parts for wear, damage, or loss of drive."
    },
    {
        "name": "Power screws and drive nuts",
        "description": "Assessing the lifting and compacting mechanism on models that use a screw drive."
    },
    {
        "name": "Motor assemblies",
        "description": "Diagnosing motor faults and the cause of a stalled or interrupted cycle."
    },
    {
        "name": "Directional and limit switches",
        "description": "Checking controls that manage ram travel and return on supported models."
    },
    {
        "name": "Drawer safety switches",
        "description": "Checking drawer detection without bypassing an interlock."
    },
    {
        "name": "Start and key switches",
        "description": "Investigating a unit that does not respond to the start control."
    },
    {
        "name": "Drawer rollers and guides",
        "description": "Assessing wear or alignment problems that make the drawer difficult to move."
    },
    {
        "name": "Ram guides and supports",
        "description": "Inspecting mechanical supports when travel is uneven or the ram binds."
    },
    {
        "name": "Wiring and connections",
        "description": "Tracing electrical faults associated with the reported symptoms."
    }
];
const compactorBrands = ['KitchenAid', 'Whirlpool', 'General Electric', 'Jenn-Air', 'Kenmore'];
const steps = [
    { title: 'Contact Us', description: 'Share the model and what happens when you start the cycle or move the drawer.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We check the affected systems and explain the repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'We check the repaired functions and explain any care or follow-up recommendations.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    {
        "question": "Is it worth repairing a trash compactor?",
        "answer": "Compare the diagnosed repair cost, appliance condition, and parts availability with replacement and installation costs. A switch, gear, or drawer-guide fault may be practical to repair. Extensive damage or unavailable parts may make replacement more sensible."
    },
    {
        "question": "Why won\u2019t my trash compactor start?",
        "answer": "The start control, drawer detection, power supply, or motor circuit may be involved. Note whether the unit is silent, hums, or starts and then stops. Those details help diagnosis, but do not identify a failed part on their own."
    },
    {
        "question": "Why is the drawer stuck closed?",
        "answer": "The ram may not have returned fully, the drawer may be obstructed, or its guides may be misaligned. Do not force the drawer or reach into the compacting mechanism. Stop using the unit and arrange diagnosis if normal operation described in the manual does not restore access."
    },
    {
        "question": "Why does the motor run without compacting the trash?",
        "answer": "A drive gear, chain, or other mechanical component may not be transferring movement to the ram. The amount and type of waste also affect visible compaction. Describe whether the ram moves and whether you hear unusual sounds."
    },
    {
        "question": "Why is my compactor making a grinding or clicking noise?",
        "answer": "Worn drive components, an obstruction, or a misaligned moving part can cause unusual noise. Stop using it if a new grinding or repeated clicking sound develops, rather than repeatedly restarting the cycle."
    },
    {
        "question": "Can you repair built-in and panel-ready compactors?",
        "answer": "We focus on household built-in and under-counter compactors, including panel-ready installations. Send the model number and describe any custom panel or access restrictions so we can confirm the required work."
    },
    {
        "question": "What trash compactor brands do you service?",
        "answer": "Our listed appliance brands include KitchenAid, Whirlpool, GE, Jenn-Air, and Kenmore. Confirm your compactor model when booking, particularly for older units where parts availability may be limited."
    },
    {
        "question": "Should I keep using a compactor that stops mid-cycle?",
        "answer": "Stop using a compactor that repeatedly stalls, grinds, or fails to return normally. Do not bypass a safety switch or place your hands inside the mechanism. Follow the owner\u2019s manual for user checks and arrange service if the fault continues."
    },
    {
        "question": "Can you find parts for an older trash compactor?",
        "answer": "Availability depends on the exact model and part. Send the model number so we can check options before recommending a repair. If a required component is unavailable, we will discuss what that means for repair versus replacement."
    },
    {
        "question": "How long does trash compactor repair take?",
        "answer": "Timing depends on diagnosis, access, and parts availability. Some faults can be addressed during the visit; others require a return appointment. We explain the next steps after inspection rather than promise a fixed completion time."
    }
];
onMounted(() => Aos.init());
</script>

<style scoped>
@reference "tailwindcss";
.section-title {
    @apply leading-tight md:leading-12 uppercase text-2xl md:text-3xl text-center font-semibold px-5 py-7 md:py-10;
}
.call-button, .book-button {
    @apply flex items-center justify-center gap-2 w-full md:max-w-120 px-6 py-4 rounded-2xl font-semibold text-lg transition focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-red-700;
}
.call-button {
    @apply bg-red-700 hover:bg-red-800 text-white shadow-lg;
}
.book-button {
    @apply bg-white hover:bg-red-700 text-red-700 hover:text-white border-2 border-red-700 shadow-md;
}
:deep(.title) {
    @apply text-black uppercase;
}
/* Keep the shared trust strip compact on this service page only. */
.trash-compactor-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.trash-compactor-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.trash-compactor-trust :deep(h3) {
    @apply text-sm md:text-lg leading-6 pt-1;
}
.collapse-wrapper {
    display: grid;
    grid-template-rows: 1fr;
    transition: grid-template-rows 0.3s ease-out;
    overflow: hidden;
}
.collapse-content {
    min-height: 0;
}
.faq-collapse-leave-to,
.faq-collapse-enter-from {
    grid-template-rows: 0fr;
}
@media (prefers-reduced-motion: reduce) {
    .collapse-wrapper {
        transition: none;
    }
}
</style>
