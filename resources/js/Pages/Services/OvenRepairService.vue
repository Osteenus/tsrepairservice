<template>
    <Head>
        <title>Oven & Stove Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Oven & Stove Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <link rel="canonical" :href="canonicalUrl" head-key="canonical">
        <meta property="og:url" :content="canonicalUrl">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://tsrepairservice.com/images/services/oven-stove-repair-moorpark.webp">
        <meta property="og:image:alt" content="Oven and stove repair in Moorpark">
        <component :is="'script'" type="application/ld+json" head-key="oven-stove-schema">{{ structuredDataJson }}</component>
    </Head>

    <nav aria-label="Breadcrumb" class="px-6 md:px-12 py-3 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><Link href="/" class="hover:text-red-700 underline">Home</Link></li>
            <li aria-hidden="true">/</li>
            <li><Link href="/services" class="hover:text-red-700 underline">Services</Link></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">Oven &amp; Stove Repair</li>
        </ol>
    </nav>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/images/services/oven-stove-repair-moorpark.webp')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Oven and Stove Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential & Commercial Oven & Stove Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Bring Your Kitchen Back to Cooking
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                An oven that bakes unevenly or a burner that will not ignite can put meals on hold. We diagnose
                ovens, stoves, ranges, and cooktops in Moorpark and Ventura County, explain the cause,
                and discuss the repair before work begins.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/images/services/oven-stove-repair-moorpark.webp" alt="Stainless steel double-oven gas range in a home kitchen"
                class="w-full aspect-[4/3] md:aspect-square max-h-[520px] object-cover" fetchpriority="high" width="1200" height="900">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="cooking-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in cookingTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Oven and Stove Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/images/services/oven-stove-diagnostics.webp" alt="Broken bake heating element inside an electric oven" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Typical Oven & Stove Repairs & Parts</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            A cold oven, a clicking burner, or an inaccurate temperature can have several causes. We check the
            relevant heating, ignition, and control systems before recommending parts. Repair options depend
            on the model, condition, and parts availability.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Residential & Commercial Oven & Stove Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/oven-stove-residential-cooktop.webp"
                    alt="Stainless steel five-burner gas cooktop in a home kitchen"
                    width="756" height="567" loading="lazy" decoding="async"
                    class="w-full aspect-[4/3] object-cover rounded-xl mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Residential Oven & Stove Repair</h3>
                <p>From a built-in wall oven to the range used for everyday meals, we help homeowners resolve
                    heating, ignition, temperature, and door problems. Let us know whether your appliance uses
                    gas or electricity and whether it is built into cabinetry so we can discuss access.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/oven-stove-commercial-repair.webp"
                    alt="Illustration of a technician removing a commercial range access panel in a restaurant kitchen"
                    width="1200" height="900" loading="lazy" decoding="async"
                    class="w-full aspect-[4/3] object-cover rounded-xl mb-6">
                <p class="text-xs text-gray-500 -mt-3 mb-4">Illustrative image</p>
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Commercial Cooking Equipment</h3>
                <p>Contact us to check service availability for:</p>
                <ul class="list-disc pl-5 mt-3 space-y-2">
                    <li>Commercial convection ovens</li>
                    <li>Commercial gas and electric ranges with ovens</li>
                    <li>Countertop gas and electric cooking units</li>
                </ul>
                <p class="mt-4">Share the manufacturer, model, fuel type, and symptoms before scheduling.
                    This enquiry list does not confirm coverage for every unit. We will check the specific
                    equipment, installation, and parts availability before accepting the job.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Oven & Stove Brands We Service" heading-tag="h2" :brand-names="cookingBrands" class="bg-white text-black" />
    <p class="text-center px-6 pb-8 leading-7">Have a different brand or a commercial model? Share the model number so we can confirm service and parts availability.</p>

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

    <ServiceAreaSection heading-tag="h2" description="We provide oven, stove, range, and cooktop repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Oven & Stove Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`cooking-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`cooking-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`cooking-faq-answer-${index}`"
                        :aria-labelledby="`cooking-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Oven & Stove Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>Get help with an oven that will not heat, an unreliable burner, or a cooktop that interrupts meal
                preparation. Share the model, fuel type, and symptoms with TS Repair Service to request a visit.</p>
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
import { House, Building2, MessagesSquare, Search, CookingPot, PanelsTopLeft, Flame, Zap, Layers3 } from 'lucide-vue-next';
import Aos from 'aos';
import Layout from '@/Layouts/Layout.vue';
import BrandsSection from '@/Components/Custom/BrandsSection.vue';
import ServiceAreaSection from '@/Components/Custom/ServiceAreaSection.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
const contactNumber = inject('contactNumber');
const whyChooseUsItems = inject('whyChooseUsItems');
const description = 'Oven, stove, range and cooktop repair in Moorpark and Ventura County. Help with heating, ignition and temperature problems. Contact TS Repair Service.';
const canonicalUrl = 'https://tsrepairservice.com/oven-stove-repair-moorpark';
const structuredDataJson = JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'Service',
            '@id': `${canonicalUrl}#service`,
            name: 'Oven and Stove Repair',
            serviceType: 'Oven, stove, range and cooktop repair',
            url: canonicalUrl,
            description,
            image: 'https://tsrepairservice.com/images/services/oven-stove-repair-moorpark.webp',
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
                { '@type': 'ListItem', position: 3, name: 'Oven & Stove Repair', item: canonicalUrl },
            ],
        },
    ],
}).replace(/</g, '\\u003c');
const trustItems = [
    { title: 'Local Family-Owned Service', icon: House },
    { title: 'Residential & Commercial', icon: Building2 },
    { title: 'Clear Communication', icon: MessagesSquare },
    { title: 'Careful Diagnostics', icon: Search },
];
const cookingTypes = [
    { title: 'Wall Ovens', icon: PanelsTopLeft, description: 'Built-in single and double ovens with heating, door, or control faults.' },
    { title: 'Gas & Electric Ovens', icon: Flame, description: 'Diagnosis of bake, broil, ignition, and temperature-control problems.' },
    { title: 'Freestanding & Slide-In Ranges', icon: CookingPot, description: 'Service for the oven and surface-cooking sections of your range.' },
    { title: 'Gas & Electric Stoves', icon: Zap, description: 'Troubleshooting burners, surface elements, switches, and ignition.' },
    { title: 'Cooktops', icon: Layers3, description: 'Gas and electric cooking surfaces; share your model to confirm coverage.' },
    { title: 'Commercial Equipment Enquiries', icon: Building2, description: 'Model-specific confirmation for commercial cooking equipment before booking.' },
];
const problems = [
    'Oven not heating or taking too long to preheat', 'Oven overheating or burning food',
    'Uneven cooking or baking', 'Gas burners not igniting or repeatedly clicking',
    'Electric burner won’t heat', 'Ignition problems', 'Oven door won’t close properly',
    'Damaged bake or broil element', 'Temperature does not match the setting',
    'Control panel or display problems', 'Error codes or interrupted cooking cycles',
    'Range or cooktop won’t turn on',
];
const parts = [
    { name: 'Bake and broil elements', description: 'Checking electric heating elements and their circuits when an oven stays cold or heats unevenly.' },
    { name: 'Oven igniters', description: 'Diagnosing the ignition sequence when a gas oven fails to heat.' },
    { name: 'Burners and surface elements', description: 'Inspecting the affected cooking zone, connections, and heat source.' },
    { name: 'Infinite switches', description: 'Testing surface-element controls when an electric burner will not heat or regulate correctly.' },
    { name: 'Temperature sensors and thermostats', description: 'Checking temperature feedback and regulation when baking results do not match the setting.' },
    { name: 'Control boards and displays', description: 'Diagnosing electronic faults and related connections before recommending a board replacement.' },
    { name: 'Spark modules and electrodes', description: 'Tracing gas cooktop ignition faults or persistent clicking.' },
    { name: 'Gas valves', description: 'Assessing valve operation as part of professional diagnosis on supported gas appliances.' },
    { name: 'Oven door hinges', description: 'Checking alignment and worn hinges when a door will not close evenly.' },
    { name: 'Door seals', description: 'Inspecting damaged or displaced gaskets that can allow heat to escape.' },
    { name: 'Switches and wiring', description: 'Testing door switches, selectors, and electrical connections for the diagnosed fault.' },
    { name: 'Convection fans', description: 'Investigating airflow or fan faults that may affect heat distribution inside the oven.' },
];
const cookingBrands = ['Whirlpool', 'General Electric', 'KitchenAid', 'Frigidaire', 'Bosch', 'Samsung', 'LG', 'Maytag'];
const steps = [
    { title: 'Contact Us', description: 'Share the appliance model, fuel type, and what happens when you try to cook.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We inspect the affected heating or ignition system and explain repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'We check the repaired functions and explain any care or follow-up recommendations.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    { question: 'Why is my oven not heating?', answer: 'On an electric oven, a heating element, control, or power-supply fault may be involved. On a gas oven, the ignition system may be the cause. The symptoms alone do not identify the part; we check the relevant system before recommending a repair.' },
    { question: 'Why does my oven cook unevenly or show the wrong temperature?', answer: 'Possible causes include a sensor or thermostat fault, a damaged element, poor door sealing, or a convection fan problem. Rack position and cookware can also affect results. Tell us which cooking mode is affected and whether the issue started suddenly.' },
    { question: 'Do you repair gas and electric stoves and ovens?', answer: 'Yes, we diagnose gas and electric cooking appliances, including ovens, stoves, ranges, and cooktops. Share the model and installation details when booking. Commercial equipment and specialised designs require confirmation before a visit.' },
    { question: 'Can you repair built-in single and double wall ovens?', answer: 'We service single and double wall ovens. Let us know how the oven is installed and whether another appliance is built into the same cabinet so access requirements can be discussed before the appointment.' },
    { question: 'Why won’t my stove burner ignite or heat?', answer: 'Gas ignition problems can involve the electrode, spark module, or burner components. An electric zone may have an element, switch, or connection fault. Stop using the affected burner and request a diagnosis if the problem persists.' },
    { question: 'What should I do if I smell gas near my stove?', answer: 'Leave the area immediately. Do not try to light the appliance, operate electrical switches, or use a phone near the suspected leak. From a safe location, call your gas utility or emergency services. A suspected gas leak needs urgent attention, not a routine repair booking.' },
    { question: 'Is it worth repairing an oven or range?', answer: 'Compare the diagnosed repair cost, parts availability, and appliance condition with the cost of replacement and installation. An isolated element, igniter, or hinge fault may be practical to repair. Extensive damage or unavailable parts can change that assessment.' },
    { question: 'Which oven and stove brands do you service?', answer: 'Our listed appliance brands include Whirlpool, GE, KitchenAid, Frigidaire, Bosch, Samsung, LG, and Maytag. Send the model number to confirm coverage for your cooking appliance, especially commercial equipment or specialised designs.' },
    { question: 'Do you repair commercial ovens and ranges?', answer: 'Contact us with your commercial equipment’s manufacturer, model, fuel type, and installation details. We confirm service scope and parts availability for that specific unit before scheduling; we do not assume every restaurant appliance is covered.' },
    { question: 'How long does oven or stove repair take?', answer: 'It depends on the fault, access, and whether parts are available. Some repairs can be completed during the diagnostic visit; others need a return appointment. We explain the expected next steps after inspection rather than promise a fixed repair time.' },
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
.cooking-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.cooking-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.cooking-trust :deep(h3) {
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
