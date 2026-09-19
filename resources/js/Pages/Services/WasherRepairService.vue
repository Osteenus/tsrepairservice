<template>
    <Head>
        <title>Washer Repair Moorpark CA | TS Repair Service</title>
        <meta name="description" :content="description">
        <meta property="og:title" content="Washer Repair Moorpark CA | TS Repair Service">
        <meta property="og:description" :content="description">
        <meta property="og:image" content="/storage/img/components/services/washer-repair-1.jpg">
    </Head>

    <div class="bg-cover bg-center bg-stone-600 bg-blend-multiply" style="background-image: url('/storage/img/components/services/washer-repair-1.jpg')">
        <h1 class="text-3xl md:text-5xl text-white text-center font-semibold px-6 py-8 md:py-16 leading-tight md:leading-16">
            Washer Repair in Moorpark and Ventura County
        </h1>
    </div>

    <section class="bg-white grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-center px-6 py-8 md:px-12 md:py-12 lg:px-16">
        <div class="flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-widest text-red-700">Residential & Commercial Washer Repair</p>
            <h2 class="leading-tight text-3xl lg:text-5xl text-slate-900 font-semibold mt-3 mb-5">
                Get Your Laundry Routine Back on Track
            </h2>
            <p class="leading-7 text-base text-gray-600 max-w-xl">
                Washer not draining, spinning, or finishing a cycle? We help homes and businesses in Moorpark
                and Ventura County find the cause and understand their repair options.
            </p>
            <div class="flex flex-col items-start gap-3 mt-6">
                <a :href="`tel:${contactNumber}`" class="call-button"><PhoneIcon class="size-6" aria-hidden="true" />Call Us Now</a>
                <Link href="/contact" class="book-button"><WrenchScrewdriverIcon class="size-6" aria-hidden="true" />Book Service</Link>
            </div>
            <p class="mt-4 text-sm leading-6 text-gray-500">Have your brand, model number, and symptoms ready when you contact us.</p>
        </div>
        <figure class="overflow-hidden rounded-3xl bg-gray-100 border border-gray-200">
            <img src="/storage/img/components/washer-dryer.png" alt="Front-load washer and dryer in a laundry room"
                class="w-full aspect-[4/3] md:aspect-square max-h-[520px] object-cover" fetchpriority="high" width="400" height="400">
        </figure>
    </section>

    <TrustStripSection :items="trustItems" class="washer-trust" />

    <section class="bg-gray-200 text-gray-800 flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">What We Repair</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 md:px-8 pb-6 md:pb-10">
            <div v-for="item in washerTypes" :key="item.title" data-aos="fade-up" class="flex items-start gap-4 rounded-2xl bg-white p-5">
                <component :is="item.icon" class="size-11 shrink-0 rounded-xl bg-red-50 p-2 text-red-700" aria-hidden="true" />
                <div>
                    <h3 class="text-lg font-semibold leading-6">{{ item.title }}</h3>
                    <p class="text-sm leading-6 pt-2 text-gray-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 text-white flex flex-col md:m-12 rounded-3xl m-4 shadow-lg">
        <h2 class="section-title">Common Washer Problems</h2>
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] lg:max-w-5xl lg:mx-auto items-center lg:items-stretch gap-8 lg:gap-12 px-6 md:px-12 pb-8 md:pb-16">
            <div data-aos="fade-up" class="relative aspect-[4/2] lg:aspect-auto overflow-hidden rounded-2xl">
                <img class="absolute inset-0 h-full w-full object-cover" src="/storage/img/components/services/washer-repair-3.jpeg" alt="Washer service and troubleshooting" loading="lazy">
            </div>
            <ul class="text-base md:text-lg">
                <li v-for="problem in problems" :key="problem" class="flex items-center pb-3">
                    <CheckIcon class="size-8 shrink-0 mr-2" aria-hidden="true" />{{ problem }}
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-white m-4 md:m-12 text-gray-800">
        <h2 class="section-title">Typical Washer Repairs & Parts</h2>
        <p class="max-w-3xl mx-auto text-center text-base md:text-lg leading-7 pb-8">
            The same symptom can have several causes. We check the machine before recommending a part replacement;
            the repair depends on its design, condition, and parts availability.
        </p>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-10 pb-8">
            <div v-for="part in parts" :key="part.name" class="border-b border-gray-200 py-5 px-2">
                <dt class="text-lg font-semibold">{{ part.name }}</dt>
                <dd class="pt-2 text-base leading-7 text-gray-600">{{ part.description }}</dd>
            </div>
        </dl>
    </section>

    <section class="bg-gray-200 text-gray-700 rounded-3xl m-4 md:m-12 shadow-lg">
        <h2 class="section-title">Residential & Commercial Washer Repair</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-6 md:px-12 pb-8 md:pb-16">
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/washer-residential.png"
                    alt="Illustration of a white residential front-load washing machine"
                    width="1254" height="1254" loading="lazy" decoding="async"
                    class="w-full h-64 lg:h-80 object-contain mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Residential Washer Repair</h3>
                <p>From a household top-loader to a front-load machine in a laundry closet, we help resolve filling,
                    washing, draining, and spinning problems. Let us know if your washer is stacked or in a tight space
                    so we can discuss access before the visit.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 md:p-8 text-base md:text-lg leading-7">
                <img src="/images/services/washer-commercial.png"
                    alt="Illustration of a stainless steel commercial front-load washing machine"
                    width="1254" height="1254" loading="lazy" decoding="async"
                    class="w-full h-64 lg:h-80 object-contain mb-6">
                <h3 class="text-xl font-semibold pb-3 text-slate-900">Commercial Washer Repair</h3>
                <p>A laundry breakdown can disrupt your business as well as your wash schedule. Contact us with your
                    commercial washer’s brand, model, installation details, and symptoms to confirm service for your
                    equipment. Coverage and parts availability depend on the specific machine.</p>
            </div>
        </div>
    </section>

    <BrandsSection title="Washer Brands We Service" heading-tag="h2" :brand-names="washerBrands" class="bg-white text-black" />
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

    <ServiceAreaSection heading-tag="h2" description="Contact us for washer repair in Moorpark and Ventura County. Our published service areas include:" />

    <section class="bg-white flex flex-col m-4 md:m-12 text-red-700 rounded-3xl">
        <h2 class="section-title text-black">Washer Repair FAQ</h2>
        <div class="grid grid-cols-1 md:px-20 text-base md:text-lg pb-8">
            <div v-for="(item, index) in faqs" :key="item.question"
                class="border-b-2 border-dotted transition-colors duration-300 ease-in-out hover:bg-red-50">
                <h3>
                    <button type="button" :id="`washer-faq-question-${index}`"
                        :aria-expanded="Boolean(openFaqs[index])" :aria-controls="`washer-faq-answer-${index}`"
                        @click="openFaqs[index] = !openFaqs[index]"
                        class="flex items-start gap-4 w-full text-left cursor-pointer px-6 py-4 font-semibold text-lg text-gray-700 focus-visible:outline-red-700">
                        <ChevronDownIcon class="size-6 shrink-0 text-red-700 transition-transform duration-400 motion-reduce:transition-none"
                            :class="{ 'rotate-180': openFaqs[index] }" aria-hidden="true" />
                        {{ item.question }}
                    </button>
                </h3>
                <Transition name="faq-collapse">
                    <div v-show="openFaqs[index]" :id="`washer-faq-answer-${index}`"
                        :aria-labelledby="`washer-faq-question-${index}`" class="collapse-wrapper">
                        <div class="collapse-content">
                            <p class="text-gray-700 px-6 pb-4 leading-8">{{ item.answer }}</p>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 flex flex-col m-4 md:m-12 text-white rounded-3xl">
        <h2 class="section-title">Schedule Your Washer Repair</h2>
        <div class="max-w-3xl mx-auto px-6 md:px-8 pb-8 md:pb-16 text-base md:text-xl leading-8">
            <p>Standing water, a stopped cycle, or soaked laundry should not leave you guessing. Tell TS Repair Service
                what your washer is doing and request a visit in Moorpark or your local service area.</p>
            <p class="pt-4">Call <a :href="`tel:${contactNumber}`" class="underline">{{ contactNumber }}</a>
                or send a service request with your washer’s brand, model, and symptoms.</p>
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
import { House, Building2, MessagesSquare, Search, WashingMachine, Layers3, Minimize2 } from 'lucide-vue-next';
import Aos from 'aos';
import Layout from '@/Layouts/Layout.vue';
import BrandsSection from '@/Components/Custom/BrandsSection.vue';
import ServiceAreaSection from '@/Components/Custom/ServiceAreaSection.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
const contactNumber = inject('contactNumber');
const whyChooseUsItems = inject('whyChooseUsItems');
const description = 'Residential and commercial washer repair in Moorpark and Ventura County. Help with leaks, drainage, spin problems and error codes. Call TS Repair Service.';
const trustItems = [
    { title: 'Local Family-Owned Service', icon: House },
    { title: 'Residential & Commercial', icon: Building2 },
    { title: 'Clear Communication', icon: MessagesSquare },
    { title: 'Careful Diagnostics', icon: Search },
];
const washerTypes = [
    { title: 'Top-Load Washers', icon: WashingMachine, description: 'Help with agitation, lid locking, draining, and spin-cycle problems.' },
    { title: 'Front-Load Washers', icon: WashingMachine, description: 'Diagnosis of door-seal leaks, locking faults, and drum or drainage issues.' },
    { title: 'Stackable Washer Units', icon: Layers3, description: 'Washer service for stacked laundry setups, with access discussed before the visit.' },
    { title: 'Compact Washers', icon: Minimize2, description: 'Troubleshooting for smaller laundry machines where space and access matter.' },
    { title: 'Commercial Washers', icon: Building2, description: 'Contact us with your equipment details to confirm service for your commercial model.' },
];
const problems = [
    'Washer won’t start', 'Washer won’t drain', 'Washer is leaking', 'Washer won’t spin',
    'Excessive shaking or vibration', 'Washer makes unusual noises', 'Door or lid won’t lock',
    'Washer doesn’t fill with water', 'Error codes or interrupted cycles', 'Clothes remain wet after the cycle',
];
const parts = [
    { name: 'Drain pump', description: 'Checking for blockages or pump faults when water stays in the drum.' },
    { name: 'Water inlet valve', description: 'Investigating slow filling, no water, or water entering when it should not.' },
    { name: 'Door lock or lid switch', description: 'Diagnosing locking and safety-switch faults that can prevent a cycle from starting or spinning.' },
    { name: 'Suspension rods and shock absorbers', description: 'Checking worn suspension components when the tub shakes excessively.' },
    { name: 'Drive belt', description: 'Inspecting belt wear or damage on models that use a belt-driven system.' },
    { name: 'Motor', description: 'Testing the drive system when the drum fails to turn or the cycle stops under load.' },
    { name: 'Control board', description: 'Checking controls and related wiring before recommending an electronic board replacement.' },
    { name: 'Pressure switch or water-level sensor', description: 'Investigating incorrect water levels and filling or draining interruptions.' },
    { name: 'Hoses and seals', description: 'Tracing leaks at hose connections, the door seal, and other water-carrying components.' },
    { name: 'Bearings, where appropriate', description: 'Assessing drum noise and play; repair feasibility depends on tub design, parts availability, and overall condition.' },
];
const washerBrands = ['LG', 'Whirlpool', 'Samsung', 'General Electric', 'Maytag', 'Bosch'];
const steps = [
    { title: 'Contact Us', description: 'Call or send a request with your washer’s model and the symptoms you noticed.', icon: PhoneIcon },
    { title: 'Schedule a Visit', description: 'Confirm your location, equipment details, access, and an available appointment.', icon: CalendarDaysIcon },
    { title: 'Diagnosis & Repair', description: 'We inspect the washer and explain the issue and repair options before work begins.', icon: WrenchScrewdriverIcon },
    { title: 'Check Operation', description: 'After the repair, we check the relevant washer functions and explain any follow-up care.', icon: CheckCircleIcon },
];
const openFaqs = ref({});
const faqs = [
    { question: 'Is it worth repairing a washing machine?', answer: 'It depends on the washer’s age, condition, repair cost, and parts availability. An isolated pump or latch fault may be more practical to repair than extensive tub damage. A diagnosis helps you compare a repair with replacement before deciding.' },
    { question: 'Why won’t my washer drain?', answer: 'Possible causes include a blocked drain path, a kinked hose, or a faulty drain pump. Some control or locking faults can also interrupt the cycle. Avoid repeatedly restarting a washer full of water; note any error code and request a diagnosis.' },
    { question: 'Why is my washer shaking during the spin cycle?', answer: 'An uneven load or an unlevel machine can cause shaking. Persistent vibration may also involve worn suspension rods, shock absorbers, or other drum-support components. Stop the cycle if the machine moves across the floor; repeated severe shaking needs inspection.' },
    { question: 'Why is my washing machine leaking?', answer: 'Leaks may come from loose or damaged hoses, a worn door seal, the drain pump, or excess suds. Where and when the water appears helps narrow down the cause. Stop using the washer until the leak is identified to limit further damage.' },
    { question: 'Do you repair front-load and top-load washers?', answer: 'Yes. We service front-load and top-load washers, as well as compact and stackable washer units. Share the model number and installation details when booking, especially if the washer is stacked or difficult to access.' },
    { question: 'What washer brands do you service?', answer: 'Our washer service includes LG, Whirlpool, Samsung, GE, Maytag, and Bosch. For another brand or a commercial machine, contact us with the model number to confirm coverage and parts availability.' },
    { question: 'Should I stop using a noisy or leaking washer?', answer: 'Yes, stop the cycle if you notice a leak, grinding, banging, or severe vibration. Turn off the water supply if it is safe to reach. Do not touch plugs or electrical equipment in a wet area. Arrange an inspection before running another load.' },
    { question: 'How long does washer repair usually take?', answer: 'Timing depends on the fault, access to the machine, and whether the correct parts are available. Some repairs can be completed during the diagnostic visit; others need a return appointment. We can explain the expected next steps after inspecting your washer.' },
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
.washer-trust :deep(dl) {
    @apply grid-cols-2 gap-6 lg:grid-cols-4;
}
.washer-trust :deep(svg) {
    @apply size-9 md:size-12;
}
.washer-trust :deep(h3) {
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
