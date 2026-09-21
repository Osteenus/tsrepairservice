<template>
    <Head>
        <title>Microwave Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Microwave Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <link rel="canonical" :href="canonicalUrl" head-key="canonical">
        <meta property="og:url" :content="canonicalUrl">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://tsrepairservice.com/images/services/microwave-repair-moorpark.webp">
        <meta property="og:image:alt" content="Microwave repair in Moorpark">
        <component :is="'script'" type="application/ld+json" head-key="microwave-schema">{{ structuredDataJson }}</component>
    </Head>

    <nav aria-label="Breadcrumb" class="px-6 md:px-12 py-3 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><Link href="/" class="hover:text-red-700 underline">Home</Link></li>
            <li aria-hidden="true">/</li>
            <li><Link href="/services" class="hover:text-red-700 underline">Services</Link></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">Microwave Repair</li>
        </ol>
    </nav>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/images/services/microwave-repair-moorpark.webp')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Microwave Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential & Commercial Microwave Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Bring Everyday Meals Back on Track
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Microwave running without heating, stopping unexpectedly, or ignoring the controls? We help
                households in Moorpark and Ventura County understand the fault and their repair options.
                Share the model and symptoms so we can discuss service and installation access.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="w-full max-w-[400px] mx-auto overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/images/services/microwave-repair-moorpark.webp" alt="Stainless steel over-the-range microwave in a home kitchen"
                class="w-full h-auto max-h-[520px] object-contain" fetchpriority="high" width="400" height="400">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="microwave-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in microwaveTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Microwave Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/images/services/microwave-diagnostics.webp" alt="Internal microwave components exposed during professional diagnosis" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Typical Microwave Repairs & Parts</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            A microwave that will not heat does not automatically need a new magnetron. We assess the
            relevant controls, door-interlock system, and heating components before recommending parts.
            Repair options depend on the model, condition, installation, and parts availability.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Residential & Commercial Microwave Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <figure class="mb-5">
                    <img src="/images/services/microwave-residential-repair.webp"
                        alt="Countertop microwave in a residential kitchen"
                        width="1024" height="765" loading="lazy" decoding="async"
                        class="w-full aspect-[4/3] object-cover rounded-xl">
                </figure>
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Residential Microwave Repair</h3>
                <p>We diagnose countertop, over-the-range, built-in, and drawer-style microwaves used
                    in home kitchens. Tell us how the unit is installed and whether it is part of a
                    combination oven so we can discuss access and model coverage before a visit.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <figure class="mb-5">
                    <img src="/images/services/microwave-commercial-repair.webp"
                        alt="Commercial countertop microwave in a restaurant kitchen"
                        width="1024" height="765" loading="lazy" decoding="async"
                        class="w-full aspect-[4/3] object-cover rounded-xl">
                </figure>
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Commercial Microwave Enquiries</h3>
                <p>For a countertop microwave in an office, café, or business kitchen, send the brand,
                    model, power rating, and symptoms. We confirm equipment coverage and parts availability
                    before scheduling.</p>
                <p class="mt-4">Heavy-duty restaurant microwaves and combination cooking systems require
                    individual confirmation. Not every commercial model is within our service scope.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Microwave Brands We Service" heading-tag="h2" :brand-names="microwaveBrands" class="bg-white text-black" />
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

    <ServiceAreaSection heading-tag="h2" description="We provide microwave repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Microwave Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`microwave-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`microwave-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`microwave-faq-answer-${index}`"
                        :aria-labelledby="`microwave-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Microwave Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>Get help with a microwave that will not heat, an unresponsive control panel, or a door that
                will not latch. Share the brand, model, and installation details to request service.</p>
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
import { House, Building2, MessagesSquare, Search, PanelsTopLeft, Move, Rows3, Minimize2 } from 'lucide-vue-next';
import Aos from 'aos';
import Layout from '@/Layouts/Layout.vue';
import BrandsSection from '@/Components/Custom/BrandsSection.vue';
import ServiceAreaSection from '@/Components/Custom/ServiceAreaSection.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
const contactNumber = inject('contactNumber');
const whyChooseUsItems = inject('whyChooseUsItems');
const description = 'Microwave repair in Moorpark and Ventura County. Help with heating, controls, turntables and door faults. Contact TS Repair Service to discuss your model.';
const canonicalUrl = 'https://tsrepairservice.com/microwave-repair-moorpark';
const structuredDataJson = JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'Service',
            '@id': `${canonicalUrl}#service`,
            name: 'Microwave Repair',
            serviceType: 'Microwave repair',
            url: canonicalUrl,
            description,
            image: 'https://tsrepairservice.com/images/services/microwave-repair-moorpark.webp',
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
                { '@type': 'ListItem', position: 3, name: 'Microwave Repair', item: canonicalUrl },
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
const microwaveTypes = [
    { title: 'Countertop Microwaves', icon: Move, description: 'Freestanding units with heating, control, door, or turntable faults.' },
    { title: 'Over-the-Range Microwaves', icon: PanelsTopLeft, description: 'Cooking functions plus model-specific fan, light, and mounting access issues.' },
    { title: 'Built-In Microwaves', icon: House, description: 'Integrated units and trim-kit installations, with removal requirements discussed in advance.' },
    { title: 'Microwave Drawers', icon: Rows3, description: 'Drawer operation, controls, and heating faults; send the model to confirm coverage.' },
    { title: 'Convection & Combination Models', icon: Minimize2, description: 'Tell us which cooking mode is affected so we can confirm service for your appliance.' },
    { title: 'Commercial Model Enquiries', icon: Building2, description: 'Business-use equipment is assessed by model and installation before booking.' },
];
const problems = [
    "Runs but does not heat food",
    "Will not turn on",
    "Sparks or makes popping sounds",
    "Stops before the timer finishes",
    "Turntable does not rotate",
    "Buttons or touch controls do not respond",
    "Display is blank or shows an error code",
    "Door does not close or latch correctly",
    "Unusual humming or buzzing",
    "Interior light does not work",
    "Over-the-range exhaust fan problems",
    "Heating is inconsistent"
];
const parts = [
    {
        "name": "Door switches and interlocks",
        "description": "Diagnosing door-detection faults without bypassing safety mechanisms."
    },
    {
        "name": "Latches and door mechanisms",
        "description": "Assessing alignment and damaged components that prevent correct closure."
    },
    {
        "name": "Turntable motors and couplers",
        "description": "Checking the drive mechanism when the tray does not rotate on models with a turntable."
    },
    {
        "name": "Control panels and keypads",
        "description": "Investigating unresponsive buttons or intermittent operation."
    },
    {
        "name": "Control boards and connections",
        "description": "Tracing electronic faults before recommending a replacement board."
    },
    {
        "name": "Thermal cutouts and cooling fans",
        "description": "Investigating overheating-related shutdowns and cooling problems."
    },
    {
        "name": "Magnetrons and heating-system components",
        "description": "Professional assessment of the heating circuit on supported models."
    },
    {
        "name": "Inverter assemblies",
        "description": "Model-specific diagnosis when the power-conversion system may be involved."
    },
    {
        "name": "Fuses and power-supply components",
        "description": "Finding the underlying cause of a loss of power rather than simply replacing a fuse."
    },
    {
        "name": "Waveguide covers and cavity condition",
        "description": "Inspecting damage or contamination associated with arcing and determining whether repair is appropriate."
    },
    {
        "name": "Interior lights",
        "description": "Checking lamps and related connections according to the appliance design."
    },
    {
        "name": "Vent fans and cooktop lights",
        "description": "Troubleshooting these additional functions on over-the-range models."
    }
];
const microwaveBrands = ['Whirlpool', 'General Electric', 'KitchenAid', 'Samsung', 'LG', 'Frigidaire', 'Bosch', 'Maytag'];
const steps = [
    { title: 'Contact Us', description: 'Share the model, any error code, and whether the unit runs, heats, and responds to controls.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We check the affected systems and explain the repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'We check the repaired functions and explain any care or follow-up recommendations.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    {
        "question": "Is it worth repairing a microwave?",
        "answer": "Compare the diagnosed repair cost with replacement and installation costs. A built-in or over-the-range model may involve cabinetry or mounting work when replaced. A lower-cost countertop unit may be less economical to repair. Parts availability and the condition of the door and cavity also matter."
    },
    {
        "question": "Why does my microwave run but not heat?",
        "answer": "Possible causes include a door-interlock fault, a control fault, or a problem in the heating or power-conversion system. Sound and lights alone cannot identify the failed part. Professional diagnosis is needed before deciding what to replace."
    },
    {
        "question": "What should I do if my microwave sparks?",
        "answer": "Stop the cycle and do not continue using a unit that repeatedly sparks. Arcing can be associated with unsuitable items, contamination, or damaged internal surfaces or components. Follow the model’s user manual and arrange an inspection if the cause is unclear or damage is visible."
    },
    {
        "question": "Do you repair over-the-range and built-in microwaves?",
        "answer": "Yes, these are among the types we service. Let us know the model, mounting arrangement, and whether it is part of a combination appliance. Access, removal, and parts availability must be considered before scheduling."
    },
    {
        "question": "Do you repair microwave drawers and convection models?",
        "answer": "Contact us with the model number and the affected function. Drawer mechanisms and combination cooking systems vary, so we confirm coverage and parts availability for the specific unit."
    },
    {
        "question": "Why has the turntable stopped spinning?",
        "answer": "On a model with a turntable, the tray, support, coupler, or drive motor may be involved. Some microwaves use a different design and have no rotating tray. Tell us whether the tray is correctly seated and whether the problem started suddenly."
    },
    {
        "question": "Can I use a microwave with a damaged door or latch?",
        "answer": "Do not use a microwave if the door will not close properly or if the door, hinges, latch, or sealing surfaces are damaged. Arrange an assessment before using it again, and never bypass a door switch."
    },
    {
        "question": "Can I repair a microwave myself?",
        "answer": "Limit home care to the cleaning and user checks described in your model’s manual. Do not remove the outer cover: internal components can retain dangerous voltage even after unplugging. Internal testing and repairs should be left to a qualified technician."
    },
    {
        "question": "What microwave brands do you service?",
        "answer": "Our listed appliance brands include Whirlpool, GE, KitchenAid, Samsung, LG, Frigidaire, Bosch, and Maytag. Share the model number so we can confirm service and parts availability, especially for combination or commercial equipment."
    },
    {
        "question": "How long does microwave repair take?",
        "answer": "Timing depends on the fault, installation access, and parts availability. Some issues can be addressed during the visit; others require a return appointment. We explain the next steps after diagnosis rather than promise a fixed repair time."
    },
    {
        "question": "Do you repair commercial microwaves?",
        "answer": "Send the brand, model, power rating, and installation details. We assess business-use and heavy-duty countertop units individually before accepting a job. Specialised high-speed and combination cooking equipment is not automatically covered."
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
.microwave-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.microwave-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.microwave-trust :deep(h3) {
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
