<template>
    <Head>
        <title>Electronic Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Electronic Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
    </Head>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/storage/img/components/services/electronic-repair-1.jpg')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Electronic Control & Circuit Board Repair in Moorpark
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Electronic Controls & Circuit Boards</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">Component-Level Electronics Repair</h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Not every problem requires full board replacement. TS Repair Service provides electronic
                control and circuit board diagnostics and repair for residential, commercial, and
                light-industrial equipment. Services include appliance controls,
                HVAC electronics, audio amplifiers, and equipment modules.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <a href="#schedule" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</a>
            </div>
        </div>
        <figure class="w-full max-w-[520px] mx-auto overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/storage/img/components/services/electronic-repair-2.jpg" alt="Electronic circuit schematic diagram"
                width="592" height="594" fetchpriority="high" class="w-full h-auto max-h-[520px] object-contain">
        </figure>
    </section>

    <section class="bg-gray-200 text-gray-800 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 md:px-12 pb-8 md:pb-12">
            <article v-for="(category, index) in categories" :key="category.title" class="bg-white rounded-2xl p-6 md:p-8">
                <component :is="categoryIcons[index]" class="size-12 rounded-xl bg-red-50 text-red-700 p-2 mb-5" aria-hidden="true" />
                <h3 class="text-xl font-semibold leading-7 text-slate-900 mb-4">{{ category.title }}</h3>
                <ul class="list-disc pl-5 space-y-2 text-base leading-7 text-gray-600">
                    <li v-for="item in category.items" :key="item">{{ item }}</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="bg-slate-900 text-white rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Typical Electronics Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div class="relative aspect-[4/3] lg:aspect-auto overflow-hidden rounded-2xl">
                <img src="/storage/img/components/services/electronic-repair-3.jpg" alt="Circuit board examined during electronics repair"
                    loading="lazy" class="absolute inset-0 w-full h-full object-cover">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-start pb-3 leading-7">
                    <CheckIcon class="size-7 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-slate-900 text-white rounded-3xl m-4 md:m-12">
        <h2 class="section-title">Discuss Your Electronics Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-12 text-base md:text-lg leading-8">
            <p>Contact TS Repair Service about your equipment and the problem you are experiencing.</p>
            <div class="flex flex-col items-center mt-6 gap-4">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <a href="#schedule" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</a>
            </div>
        </div>
    </section>

    <div class="m-4 md:m-12 rounded-3xl overflow-hidden electronic-schedule">
        <ScheduleFormSection />
    </div>
</template>

<script setup>
import { inject } from 'vue';
import { Head } from '@inertiajs/vue3';
import { CheckIcon } from '@heroicons/vue/16/solid';
import { PhoneIcon, WrenchScrewdriverIcon } from '@heroicons/vue/24/outline';
import { Fan, CircuitBoard, AudioLines, Cpu } from 'lucide-vue-next';
import Layout from '@/Layouts/Layout.vue';
import ScheduleFormSection from '@/Components/Custom/ScheduleFormSection.vue';

defineOptions({ layout: Layout });
const contactNumber = inject('contactNumber');
const description = 'Electronic control and circuit board repair in Moorpark. Appliance controls, HVAC electronics, audio amplifiers and equipment modules. Contact TS Repair Service.';
const categoryIcons = [Fan, CircuitBoard, AudioLines, Cpu];
const categories = [
    {
        "title": "HVAC & Climate Control Electronics",
        "items": [
            "Furnace control boards and ignition/control circuits",
            "AC / heat pump modules and relays",
            "Thermostats and smart thermostat board issues",
            "ECM / variable-speed motor controller boards"
        ]
    },
    {
        "title": "Appliance Electronic Controls",
        "items": [
            "Washer, dryer, oven, refrigerator, and dishwasher control boards",
            "User interface boards and display modules",
            "Power supply boards and inverter boards",
            "Motor control and drive boards"
        ]
    },
    {
        "title": "Audio & Amplifier Electronics",
        "items": [
            "Home audio amplifiers and receivers",
            "Professional and commercial audio equipment",
            "Power amplifiers and preamp boards",
            "Speaker protection and power supply boards"
        ]
    },
    {
        "title": "Commercial & Specialty Electronics",
        "items": [
            "Restaurant and commercial appliance boards",
            "Industrial control modules (non-PLC)",
            "Power supply units",
            "Custom or discontinued electronic boards"
        ]
    }
];
const problems = [
    "Equipment not powering on",
    "No display or dead control panel",
    "Intermittent operation or random shutdowns",
    "Burnt components or visible board damage",
    "Error codes caused by control failure",
    "Short circuits or blown fuses",
    "Power supply failure",
    "Audio distortion or no output",
    "Overheating components"
];
</script>

<style scoped>
@reference "tailwindcss";
.section-title {
    @apply leading-tight md:leading-12 uppercase text-2xl md:text-3xl text-center font-semibold px-5 py-7 md:py-10;
}
.call-button, .book-button {
    @apply flex items-center justify-center gap-2 w-full md:max-w-120 px-6 py-4 rounded-2xl font-semibold text-lg transition focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-red-700;
}
.call-button { @apply bg-red-700 hover:bg-red-800 text-white shadow-lg; }
.book-button { @apply bg-white hover:bg-red-700 text-red-700 hover:text-white border-2 border-red-700 shadow-md; }
.electronic-schedule :deep(#schedule) { @apply bg-slate-900 px-4 py-8 md:p-12; }
.electronic-schedule :deep(form) { @apply rounded-2xl px-5 py-8 sm:px-8 md:px-12; }
</style>
