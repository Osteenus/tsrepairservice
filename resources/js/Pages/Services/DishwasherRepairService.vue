<template>
    <Head>
        <title>Dishwasher Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Dishwasher Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <link rel="canonical" :href="canonicalUrl" head-key="canonical">
        <meta property="og:url" :content="canonicalUrl">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://tsrepairservice.com/images/services/dishwasher-repair-moorpark.webp">
        <meta property="og:image:alt" content="Dishwasher repair in Moorpark">
        <component :is="'script'" type="application/ld+json" head-key="dishwasher-schema">{{ structuredDataJson }}</component>
    </Head>

    <nav aria-label="Breadcrumb" class="px-6 md:px-12 py-3 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><Link href="/" class="hover:text-red-700 underline">Home</Link></li>
            <li aria-hidden="true">/</li>
            <li><Link href="/services" class="hover:text-red-700 underline">Services</Link></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">Dishwasher Repair</li>
        </ol>
    </nav>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/images/services/dishwasher-racks.webp')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Dishwasher Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential & Commercial Dishwasher Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Get Back to Clean Dishes
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Standing water, cloudy glasses, or a cycle that never finishes? We help households in Moorpark
                and Ventura County identify dishwasher faults and understand their repair options.
                Tell us what happens during the cycle so we can plan the next step.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="w-full max-w-[465px] mx-auto overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/images/services/dishwasher-repair-moorpark.webp" alt="Stainless steel KitchenAid dishwasher installed beneath a kitchen counter"
                class="w-full h-auto max-h-[520px] object-contain" fetchpriority="high" width="756" height="850">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="dishwasher-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in dishwasherTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Dishwasher Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/images/services/dishwasher-diagnostics.webp" alt="Dishwasher pump, hoses and motor exposed for diagnosis" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Typical Dishwasher Repairs & Parts</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            Similar symptoms can come from different faults. We check the fill, wash, drain, and heating
            systems as appropriate before recommending a part. Access, appliance condition, and parts
            availability all affect the repair options.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Residential & Commercial Dishwasher Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <House class="size-14 rounded-xl bg-red-50 p-3 text-red-700 mb-5" aria-hidden="true" />
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Residential Dishwasher Repair</h3>
                <p>We help with everyday cleaning, draining, drying, and leak problems in home kitchens.
                    Built-in, portable, and drawer-style machines have different access requirements.
                    Let us know if the dishwasher is behind a cabinet panel or difficult to pull out.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <Building2 class="size-14 rounded-xl bg-red-50 p-3 text-red-700 mb-5" aria-hidden="true" />
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Commercial Dishwasher Enquiries</h3>
                <p>For a dishwasher in an office, rental property, or business kitchen, send the brand,
                    model, installation details, and symptoms. We will confirm whether the unit is within
                    our service scope before scheduling.</p>
                <p class="mt-4">Specialised restaurant warewashers and glasswashers need individual confirmation;
                    residential and commercial machines do not use the same systems or parts.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Dishwasher Brands We Service" heading-tag="h2" :brand-names="dishwasherBrands" class="bg-white text-black" />
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

    <ServiceAreaSection heading-tag="h2" description="We provide dishwasher repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Dishwasher Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`dishwasher-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`dishwasher-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`dishwasher-faq-answer-${index}`"
                        :aria-labelledby="`dishwasher-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Dishwasher Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>Get help with standing water, poor wash results, or a dishwasher that will not finish its cycle.
                Share the brand, model, and symptoms with TS Repair Service to request a visit.</p>
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
const description = 'Dishwasher repair in Moorpark and Ventura County. Help with leaks, drainage, poor cleaning and drying. Contact TS Repair Service to schedule a visit.';
const canonicalUrl = 'https://tsrepairservice.com/dishwasher-repair-moorpark';
const structuredDataJson = JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'Service',
            '@id': `${canonicalUrl}#service`,
            name: 'Dishwasher Repair',
            serviceType: 'Dishwasher repair',
            url: canonicalUrl,
            description,
            image: 'https://tsrepairservice.com/images/services/dishwasher-repair-moorpark.webp',
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
                { '@type': 'ListItem', position: 3, name: 'Dishwasher Repair', item: canonicalUrl },
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
const dishwasherTypes = [
    { title: 'Built-In Dishwashers', icon: PanelsTopLeft, description: 'Under-counter machines with wash, drain, drying, or control problems.' },
    { title: 'Portable Dishwashers', icon: Move, description: 'Freestanding units, including fill connections, drainage, and cycle faults.' },
    { title: 'Drawer Dishwashers', icon: Rows3, description: 'Single and double drawers; share your model and the affected drawer when booking.' },
    { title: 'Compact Dishwashers', icon: Minimize2, description: 'Space-saving models with access and parts availability confirmed for the unit.' },
    { title: 'Panel-Ready Dishwashers', icon: House, description: 'Integrated machines behind cabinet panels, with installation access discussed in advance.' },
    { title: 'Business Kitchen Enquiries', icon: Building2, description: 'Tell us about your equipment so we can confirm coverage before scheduling.' },
];
const problems = [
    "Dishwasher won’t start",
    "Standing water or slow draining",
    "Dishes come out dirty",
    "Water leaking from the door or underneath",
    "Dishwasher won’t fill with water",
    "Dishes stay wet after the cycle",
    "Grinding, buzzing, or unusual noises",
    "Door won’t latch or close properly",
    "Cycle stops or runs longer than usual",
    "Error codes or unresponsive controls",
    "Residue, spots, or cloudy glasses",
    "Unpleasant odours after washing"
];
const parts = [
    {
        "name": "Drain pumps",
        "description": "Checking the pump and drain path when water remains after a cycle."
    },
    {
        "name": "Circulation pumps and wash motors",
        "description": "Investigating weak spray, poor cleaning, or unusual sounds during washing."
    },
    {
        "name": "Water inlet valves",
        "description": "Testing water entry when the machine underfills or will not fill."
    },
    {
        "name": "Door latches and switches",
        "description": "Checking whether the control can detect a properly closed door."
    },
    {
        "name": "Door seals and gaskets",
        "description": "Inspecting sealing surfaces when water escapes around the door."
    },
    {
        "name": "Drain and inlet hoses",
        "description": "Checking connections, restrictions, and damaged hoses that may cause leaks or drainage problems."
    },
    {
        "name": "Spray arms and wash-system parts",
        "description": "Inspecting spray distribution when food remains on dishes or one rack washes poorly."
    },
    {
        "name": "Heating elements and temperature sensors",
        "description": "Diagnosing heating and drying faults according to the model\u2019s design."
    },
    {
        "name": "Float and water-level components",
        "description": "Investigating fill detection and overfill protection faults."
    },
    {
        "name": "Detergent dispensers",
        "description": "Checking release mechanisms when detergent remains in the dispenser."
    },
    {
        "name": "Control boards and wiring",
        "description": "Testing controls and related circuits before recommending an electronic part."
    },
    {
        "name": "Rack tracks and door mechanisms",
        "description": "Assessing damaged guides, hinges, and springs that affect loading or door movement."
    }
];
const dishwasherBrands = ['Bosch', 'KitchenAid', 'General Electric', 'Whirlpool', 'Samsung', 'LG', 'Frigidaire', 'Maytag', 'Fisher & Paykel'];
const steps = [
    { title: 'Contact Us', description: 'Share the model, any error code, and what happens during the cycle.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We check the affected systems and explain the repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'We check the repaired functions and explain any care or follow-up recommendations.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    {
        "question": "Is it worth repairing a dishwasher?",
        "answer": "Consider the diagnosed fault, overall condition, parts availability, and replacement cost including installation. An isolated pump, latch, or valve fault may be practical to repair. Extensive damage or unavailable parts may make replacement more sensible."
    },
    {
        "question": "Why won’t my dishwasher drain?",
        "answer": "A restricted filter or drain hose, a drain connection issue, or a pump fault can leave water behind. A little water below the filter can be normal on some models; water covering the tub floor after a completed cycle deserves investigation."
    },
    {
        "question": "Why are my dishes still dirty after washing?",
        "answer": "Loading, detergent, restricted spray arms, low water fill, and circulation or heating faults can all affect cleaning. Note whether both racks are affected and whether the detergent dispenser opens. We use those details to guide diagnosis."
    },
    {
        "question": "Why is my dishwasher leaking?",
        "answer": "Leaks may come from door seals, hose connections, pumps, or fill-system faults. Excess foam can also cause water to escape. Stop the cycle and avoid running the machine again until the source has been checked."
    },
    {
        "question": "Why isn’t my dishwasher drying dishes?",
        "answer": "Drying results depend on the model’s drying method, cycle selection, rinse aid, and load materials. Plastic items often retain more moisture. A sudden change may point to a heating, sensor, fan, or vent fault, depending on the model."
    },
    {
        "question": "Do you repair built-in, portable, and drawer dishwashers?",
        "answer": "Yes, these are among the dishwasher types we service. Send the model number to confirm parts and access requirements. Compact, panel-ready, and specialised models may need additional details before scheduling."
    },
    {
        "question": "What dishwasher brands do you service?",
        "answer": "Our listed brands include Bosch, KitchenAid, GE, Whirlpool, Samsung, LG, Frigidaire, Maytag, and Fisher & Paykel. Confirm the model when you contact us; brand alone does not determine parts availability or coverage for every unit."
    },
    {
        "question": "Should I keep using a leaking or noisy dishwasher?",
        "answer": "Stop using a leaking machine or one making a new grinding or scraping noise. Repeated cycles can worsen a leak or mechanical fault. If water is near electrical connections, keep clear and do not reach behind the appliance."
    },
    {
        "question": "What does a dishwasher error code mean?",
        "answer": "Codes differ by manufacturer and model. Record the exact code and when it appears, along with any changes in filling, washing, or draining. A code identifies a detected condition; it does not always mean the named part needs replacement."
    },
    {
        "question": "How long does dishwasher repair take?",
        "answer": "Timing depends on the diagnosis, access, and whether the required part is available. Some faults can be addressed during the visit; others need a return appointment. We explain the next steps after inspection."
    },
    {
        "question": "Do you repair commercial dishwashers?",
        "answer": "Contact us with the manufacturer, model, and installation details. We confirm each business installation before accepting a job. Specialised restaurant warewashers, glasswashers, and sanitising systems are not automatically included in our service scope."
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
.dishwasher-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.dishwasher-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.dishwasher-trust :deep(h3) {
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
