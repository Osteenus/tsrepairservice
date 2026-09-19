<template>
    <Head>
        <title>Dryer Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Dryer Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <meta property="og:image" content="/storage/img/components/services/dryer-repair-1.jpg">
    </Head>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/storage/img/components/services/dryer-repair-1.jpg')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Dryer Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential & Commercial Dryer Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Restore Heat, Airflow & Reliable Drying
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Clothes still damp after a full cycle? A dryer that runs cold, stops tumbling, or needs repeated cycles
                deserves a closer look. We diagnose electric and gas dryers for homes and businesses in Moorpark
                and Ventura County, and explain your repair options before work begins.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/images/services/dryer-repair-4.JPG" alt="Silver front-load dryer in a home laundry room"
                class="w-full aspect-[4/3] md:aspect-square max-h-[520px] object-cover" fetchpriority="high" width="756" height="1008">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="dryer-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in dryerTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Dryer Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/storage/img/components/services/dryer-repair-3.jpeg" alt="Dryer service and troubleshooting" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Dryer Diagnostics & Typical Repairs</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            Long drying times can involve heat, airflow, or moisture sensing. We check how these systems work together
            before recommending parts. The repair depends on the dryer’s design, condition, and parts availability.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Residential & Commercial Dryer Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/dryer-residential.png"
                    alt="Illustration of a white residential tumble dryer"
                    width="1254" height="1254" loading="lazy" decoding="async"
                    class="w-full h-64 lg:h-80 object-contain mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Residential Dryer Repair</h3>
                <p>Keep laundry moving without running the same load again and again. We troubleshoot household
                    electric and gas dryers, including units in stacked installations. Let us know about tight
                    closets, stacked equipment, and the symptoms you notice so we can plan for access.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/dryer-commercial.png"
                    alt="Illustration of a stainless steel commercial tumble dryer"
                    width="1254" height="1254" loading="lazy" decoding="async"
                    class="w-full h-64 lg:h-80 object-contain mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Commercial Dryer Repair</h3>
                <p>Repeated drying cycles and unexpected shutdowns can interrupt a business’s laundry schedule.
                    Share your dryer’s brand, model, fuel type, and installation details to confirm service for
                    your equipment. Commercial model coverage and replacement parts are checked individually.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Dryer Brands We Service" heading-tag="h2" :brand-names="dryerBrands" class="bg-white text-black" />
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

    <ServiceAreaSection heading-tag="h2" description="Contact us for dryer repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Dryer Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`dryer-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`dryer-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`dryer-faq-answer-${index}`"
                        :aria-labelledby="`dryer-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Dryer Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>A dryer that runs without heat or leaves every load damp adds time to your day. Tell TS Repair Service
                when the problem happens and whether your dryer uses gas or electricity, and request a service visit.</p>
            <p class="pt-4">Call <a :href="`tel:${contactNumber}`" class="underline">{{ contactNumber }}</a>
                or send a service request with your dryer’s brand, model, and symptoms.</p>
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
import { House, Building2, MessagesSquare, Search, Layers3, Minimize2, Zap, Flame } from 'lucide-vue-next';
import Aos from 'aos';
import Layout from '@/Layouts/Layout.vue';
import BrandsSection from '@/Components/Custom/BrandsSection.vue';
import ServiceAreaSection from '@/Components/Custom/ServiceAreaSection.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
const contactNumber = inject('contactNumber');
const whyChooseUsItems = inject('whyChooseUsItems');
const description = 'Electric and gas dryer repair in Moorpark and Ventura County. Help with no heat, long drying cycles and drum problems. Contact TS Repair Service.';
const trustItems = [
    { title: 'Local Family-Owned Service', icon: House },
    { title: 'Residential & Commercial', icon: Building2 },
    { title: 'Clear Communication', icon: MessagesSquare },
    { title: 'Careful Diagnostics', icon: Search },
];
const dryerTypes = [
    { title: 'Electric Dryers', icon: Zap, description: 'Diagnosis of electric heating, temperature-control, and drum-drive faults.' },
    { title: 'Gas Dryers', icon: Flame, description: 'Troubleshooting ignition and heating issues on gas-powered models.' },
    { title: 'Stacked Dryer Units', icon: Layers3, description: 'Dryer service for stacked laundry setups, with access discussed before the visit.' },
    { title: 'Compact Dryers', icon: Minimize2, description: 'Share your model and venting type to confirm service for a compact installation.' },
    { title: 'Commercial Dryers', icon: Building2, description: 'Model-specific service enquiries for dryers used in business laundry operations.' },
];
const problems = [
    'Dryer runs but does not heat', 'Clothes take more than one cycle to dry',
    'Dryer won’t start', 'Drum won’t turn', 'Squealing, thumping, or grinding noises',
    'Dryer stops before the cycle ends', 'Excessive heat or a burning smell',
    'Gas dryer fails to ignite', 'Error codes or unresponsive controls',
    'Automatic cycle ends with clothes still damp',
];
const parts = [
    { name: 'Heating element', description: 'Checking the electric heating circuit when the drum turns but clothes stay cold or damp.' },
    { name: 'Thermal fuse and high-limit thermostat', description: 'Testing protective components and investigating the cause of overheating before replacement.' },
    { name: 'Cycling thermostat and thermistor', description: 'Checking temperature regulation when heat is inconsistent or cycles stop unexpectedly.' },
    { name: 'Igniter and gas valve coils', description: 'Diagnosing the ignition sequence and heating faults on compatible gas dryer models.' },
    { name: 'Drive belt and idler pulley', description: 'Inspecting belt tension and drive components when the drum stops or squeals.' },
    { name: 'Drum rollers, glides, and bearings', description: 'Checking drum supports for wear that can cause rumbling, scraping, or uneven rotation.' },
    { name: 'Motor and blower wheel', description: 'Investigating drive and internal airflow problems, including unusual blower noises.' },
    { name: 'Door switch and start controls', description: 'Testing the door-safety and starting circuits when the dryer will not run.' },
    { name: 'Moisture sensors and control board', description: 'Diagnosing early cycle endings or control errors before recommending electronic parts.' },
    { name: 'Drum seals and internal air paths', description: 'Checking seals and accessible airflow paths. Ask separately about any work needed on the building’s exhaust duct.' },
];
const dryerBrands = ['LG', 'Whirlpool', 'Samsung', 'General Electric', 'Maytag', 'Bosch'];
const steps = [
    { title: 'Contact Us', description: 'Tell us the model, fuel type, and whether the problem involves heat, noise, or drum movement.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We inspect the dryer and explain the issue and repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'We check the repaired functions and discuss any remaining airflow or maintenance concerns.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    { question: 'Why is my dryer running but not heating?', answer: 'An electric dryer may have a heating-element, power-supply, or temperature-control problem. A gas dryer may have an ignition fault. A protective device can also interrupt heating. Diagnosis should identify the cause rather than simply replace the first suspected part.' },
    { question: 'Why does my dryer take so long to dry clothes?', answer: 'Restricted airflow, an overloaded drum, a heating fault, or laundry that is unusually wet can extend drying time. Clean the lint filter as directed by your owner’s manual. If the problem persists, the appliance and its exhaust path may both need attention.' },
    { question: 'Do you repair both gas and electric dryers?', answer: 'Yes, we service both gas and electric dryers. Tell us the brand, model, and fuel type when requesting service so we can confirm coverage and prepare for your installation.' },
    { question: 'Why is my dryer making squealing or thumping noises?', answer: 'A worn pulley, drum roller, glide, or bearing can create noise. An object caught inside or an uneven load can sound similar. Stop the dryer if the noise is severe or persistent and arrange an inspection rather than repeatedly running it.' },
    { question: 'Should I keep using a dryer that smells hot or is overheating?', answer: 'No. Stop using it and arrange an inspection before another cycle. A recurring burning smell or excessive heat needs attention even if the dryer still runs. Do not bypass a thermal fuse or other safety device to keep it operating.' },
    { question: 'Is it worth repairing a dryer?', answer: 'Consider its age, overall condition, the diagnosed fault, and parts availability. A belt or switch repair may make sense for an otherwise sound machine; extensive damage may change that decision. Compare the repair estimate with replacement and installation costs.' },
    { question: 'What dryer brands do you service?', answer: 'Our dryer service includes LG, Samsung, Whirlpool, GE, Maytag, and Bosch. Contact us with the model number for other brands, commercial equipment, or specialised designs so we can confirm service availability.' },
    { question: 'How long does dryer repair take?', answer: 'Timing depends on the diagnosis, access, and parts availability. Some faults can be addressed at the first visit; others require a replacement part and a return appointment. We explain the next steps after inspecting the dryer.' },
    { question: 'Does dryer repair include cleaning the exhaust vent?', answer: 'Appliance repair and cleaning a building’s exhaust duct are different tasks. Restricted venting can affect drying, so describe your setup when booking and ask which airflow checks or cleaning work can be included. Do not assume a full duct-cleaning service is part of the repair.' },
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
.dryer-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.dryer-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.dryer-trust :deep(h3) {
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
