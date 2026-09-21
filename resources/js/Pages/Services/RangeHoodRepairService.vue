<template>
    <Head>
        <title>Range Hood Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Range Hood Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <link rel="canonical" :href="canonicalUrl" head-key="canonical">
        <meta property="og:url" :content="canonicalUrl">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://tsrepairservice.com/images/services/range-hood-repair-moorpark.webp">
        <meta property="og:image:alt" content="Range Hood repair in Moorpark">
        <component :is="'script'" type="application/ld+json" head-key="range-hood-schema">{{ structuredDataJson }}</component>
    </Head>

    <nav aria-label="Breadcrumb" class="px-6 md:px-12 py-3 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-2">
            <li><Link href="/" class="hover:text-red-700 underline">Home</Link></li>
            <li aria-hidden="true">/</li>
            <li><Link href="/services" class="hover:text-red-700 underline">Services</Link></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">Range Hood Repair</li>
        </ol>
    </nav>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/images/services/range-hood-ventilation.webp')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Range Hood Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential & Commercial Range Hood Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Get Your Kitchen Ventilation Working Again
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Fan not starting, weak extraction, or a hood that rattles while you cook? We diagnose
                range hood faults in Moorpark and Ventura County and explain the available repair options.
                Tell us the model, symptoms, and how the hood is installed before scheduling.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="w-full max-w-[400px] mx-auto overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/images/services/range-hood-repair-moorpark.webp" alt="Stainless steel wall-mounted range hood in a home kitchen"
                class="w-full h-auto max-h-[520px] object-contain" fetchpriority="high" width="400" height="400">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="range-hood-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in rangeHoodTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Range Hood Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/images/services/range-hood-diagnostics.webp" alt="Range hood blower and wiring exposed on a workbench for service" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Typical Range Hood Repairs & Parts</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            Weak extraction does not always mean the motor has failed. We assess the blower, controls,
            filters, and accessible airflow path as appropriate before recommending parts. The model,
            installation, and parts availability determine which repairs are practical.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Residential & Commercial Range Hood Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/range-hood-residential-repair.webp"
                    alt="Stainless steel under-cabinet range hood above a cooktop in a home kitchen"
                    width="1024" height="765" loading="lazy" decoding="async"
                    class="w-full aspect-[4/3] object-cover rounded-xl mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Residential Range Hood Repair</h3>
                <p>We help with fan, light, control, and noise problems in home kitchens. Under-cabinet,
                    chimney, island, and concealed insert installations have different access requirements.
                    Let us know the mounting height and whether the hood vents outside or recirculates air.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/range-hood-commercial-repair.webp"
                    alt="Large stainless steel exhaust hood above a restaurant cooking line"
                    width="1024" height="765" loading="lazy" decoding="async"
                    class="w-full aspect-[4/3] object-cover rounded-xl mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Commercial Ventilation Enquiries</h3>
                <p>For a hood in an office, rental property, or business kitchen, send the manufacturer,
                    model, installation details, and symptoms. We confirm whether the equipment and
                    required work are within our service scope before accepting a job.</p>
                <p class="mt-4">Restaurant exhaust systems, roof-mounted fans, duct cleaning, and fire-suppression
                    equipment require separate confirmation; they are not included in a standard appliance repair visit.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Range Hood Brands We Service" heading-tag="h2" :brand-names="rangeHoodBrands" class="bg-white text-black" />
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

    <ServiceAreaSection heading-tag="h2" description="We provide range hood repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Range Hood Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`range-hood-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`range-hood-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`range-hood-faq-answer-${index}`"
                        :aria-labelledby="`range-hood-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Range Hood Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>Get help with a fan that will not start, unreliable lights, or a noisy range hood.
                Share the brand, model, and installation details to discuss service for your kitchen.</p>
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
const description = 'Range hood repair in Moorpark and Ventura County. Help with fans, weak airflow, lights, controls and noise. Contact TS Repair Service to discuss your model.';
const canonicalUrl = 'https://tsrepairservice.com/range-hood-repair-moorpark';
const structuredDataJson = JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type': 'Service',
            '@id': `${canonicalUrl}#service`,
            name: 'Range Hood Repair',
            serviceType: 'Range hood repair',
            url: canonicalUrl,
            description,
            image: 'https://tsrepairservice.com/images/services/range-hood-repair-moorpark.webp',
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
                { '@type': 'ListItem', position: 3, name: 'Range Hood Repair', item: canonicalUrl },
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
const rangeHoodTypes = [
    { title: 'Under-Cabinet Hoods', icon: PanelsTopLeft, description: 'Compact hoods mounted beneath cabinets, with fan, light, and control faults.' },
    { title: 'Wall-Mounted Hoods', icon: House, description: 'Chimney-style units with accessible blower, switch, or lighting problems.' },
    { title: 'Island Hoods', icon: Move, description: 'Ceiling-mounted kitchen hoods, with height and access discussed before the visit.' },
    { title: 'Inserts & Built-In Hoods', icon: Rows3, description: 'Ventilation units concealed in cabinetry or custom canopies.' },
    { title: 'Ducted & Recirculating Models', icon: Minimize2, description: 'Diagnosis appropriate to outside-venting or filtered recirculation designs.' },
    { title: 'Business Kitchen Enquiries', icon: Building2, description: 'Equipment and job scope confirmed individually before booking.' },
];
const problems = [
    "Range hood will not turn on",
    "Fan hums but does not start",
    "Weak airflow or poor extraction",
    "Fan runs slowly or only at one speed",
    "Rattling or vibration during operation",
    "Grinding, squealing, or unusual noise",
    "Lights will not turn on or flicker",
    "Buttons or touch controls do not respond",
    "Hood shuts off unexpectedly",
    "Error codes on supported digital models",
    "Cooking odours linger despite running the fan",
    "Airflow changes or a damper rattles"
];
const parts = [
    {
        "name": "Blower motors",
        "description": "Checking the motor and its related controls when the fan does not run or runs slowly."
    },
    {
        "name": "Fan wheels and impellers",
        "description": "Inspecting the rotating assembly for damage, imbalance, or obstruction."
    },
    {
        "name": "Motor capacitors",
        "description": "Model-specific testing where a capacitor is part of the motor-start or run circuit."
    },
    {
        "name": "Fan-speed switches",
        "description": "Diagnosing missing speeds or inconsistent response to the selected setting."
    },
    {
        "name": "Control boards and touch panels",
        "description": "Assessing electronic controls and connections before recommending a board."
    },
    {
        "name": "Light switches and lamp holders",
        "description": "Tracing lighting faults beyond a failed replaceable bulb."
    },
    {
        "name": "LED modules and drivers",
        "description": "Checking model-specific lighting assemblies and their power supply."
    },
    {
        "name": "Wiring and connectors",
        "description": "Investigating loose or damaged connections associated with the reported fault."
    },
    {
        "name": "Thermal protectors",
        "description": "Assessing shutdowns and the underlying cause of overheating on supported models."
    },
    {
        "name": "Dampers and accessible airflow components",
        "description": "Checking accessible moving parts that may restrict airflow or rattle."
    },
    {
        "name": "Filter mounts and retaining clips",
        "description": "Inspecting damaged supports or loose filters that can cause vibration."
    },
    {
        "name": "Mounting hardware and vibration supports",
        "description": "Assessing loose accessible fixings and supports that transmit noise."
    }
];
const rangeHoodBrands = ['Whirlpool', 'General Electric', 'KitchenAid', 'Bosch', 'Frigidaire', 'Samsung', 'LG'];
const steps = [
    { title: 'Contact Us', description: 'Share the model, fan and lighting symptoms, mounting height, and venting arrangement.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We check the affected systems and explain the repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'We check the repaired functions and explain any care or follow-up recommendations.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    {
        "question": "Why is my range hood not pulling air properly?",
        "answer": "Restricted filters, a blower problem, or an issue with the vent path can reduce extraction. Installation layout also affects performance. Tell us whether airflow changed suddenly or has always been weak so we can discuss the appropriate checks."
    },
    {
        "question": "Why does my range hood make a loud noise?",
        "answer": "A loose filter, mounting, fan wheel, or worn motor may produce rattling, vibration, or grinding. Note whether the noise occurs at every speed. Stop using the fan if it makes a new scraping or grinding sound and arrange an assessment."
    },
    {
        "question": "Why does the fan hum but not spin?",
        "answer": "The motor, its controls, or a model-specific capacitor may be involved. A restricted rotating assembly can also prevent movement. Stop using the fan and request diagnosis rather than repeatedly trying to start it."
    },
    {
        "question": "Can you repair range hood lights?",
        "answer": "We diagnose lighting faults involving switches, lamp holders, wiring, and supported LED modules or drivers. Replacement options depend on the model. Use only the bulb type specified in the owner’s manual for any user-replaceable lamp."
    },
    {
        "question": "Do you repair ducted and ductless range hoods?",
        "answer": "Yes, we assess both outside-venting and recirculating models. They move and treat air differently, so tell us which arrangement you have. Work on inaccessible ducts or external ventilation equipment requires separate confirmation."
    },
    {
        "question": "Can you repair island hoods and built-in inserts?",
        "answer": "These are among the hood types we service. Share the model, mounting height, and photos of the installation so we can discuss safe access and whether removal is needed before scheduling."
    },
    {
        "question": "Should I clean or replace the filters first?",
        "answer": "Follow your model’s manual: some grease filters can be cleaned, while charcoal filters used in recirculating hoods may require replacement. Correct filter maintenance can help airflow, but it will not resolve a failed motor or control. Do not operate the hood without its required filters."
    },
    {
        "question": "Is it worth repairing a range hood?",
        "answer": "Compare the diagnosed repair cost and parts availability with the cost of replacement, removal, and installation. An isolated switch, lighting, or motor fault may be practical to repair. Extensive damage or unavailable parts can change that assessment."
    },
    {
        "question": "What range hood brands do you service?",
        "answer": "Our listed appliance brands include Whirlpool, GE, KitchenAid, Bosch, Frigidaire, Samsung, and LG. Send your hood’s model number to confirm coverage and parts availability. A listed brand does not guarantee support for every ventilation system."
    },
    {
        "question": "Do you repair commercial kitchen exhaust systems?",
        "answer": "Contact us with the equipment details and requested work. We confirm each business installation individually. Restaurant exhaust, roof fans, duct cleaning, and fire-suppression work are not automatically covered by an appliance repair appointment."
    },
    {
        "question": "How long does range hood repair take?",
        "answer": "Timing depends on the diagnosis, mounting access, and parts availability. Some faults can be addressed during the visit; others require a return appointment. We explain the expected next steps after inspection."
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
.range-hood-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.range-hood-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.range-hood-trust :deep(h3) {
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
