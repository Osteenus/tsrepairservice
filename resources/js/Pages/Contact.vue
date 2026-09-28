<script setup>
import Breadcrumbs from '@/Components/Custom/Breadcrumbs.vue';
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { House, Building2, MessagesSquare, SearchCheck, Phone, MapPin, ClipboardList } from 'lucide-vue-next';
import Layout from '@/Layouts/Layout.vue';
import ScheduleFormSection from '@/Components/Custom/ScheduleFormSection.vue';
import TrustStripSection from '@/Components/Custom/TrustStripSection.vue';

defineOptions({ layout: Layout });
defineProps({ selectedServiceId: { type: Number, default: null } });
const page = usePage();
const phone = computed(() => page.props.repair.phone);
const title = 'Request Appliance Repair | Moorpark CA | TS Repair Service';
const description = 'Request appliance repair in Moorpark and nearby service areas. Share your appliance type, location, model information, and symptoms with TS Repair Service.';
const canonical = 'https://tsrepairservice.com/contact';
const trustItems = [
  { title: 'Local Family-Owned Service', icon: House },
  { title: 'Residential & Commercial', icon: Building2 },
  { title: 'Clear Communication', icon: MessagesSquare },
  { title: 'Careful Diagnostics', icon: SearchCheck },
];
const steps = [
  { title: 'Send Your Request', text: 'Share the appliance, location, and symptoms.' },
  { title: 'We Review the Details', text: 'We confirm whether the appliance and location are within our service coverage.' },
  { title: 'Discuss Scheduling', text: 'We contact you to discuss availability and the next step.' },
];
</script>

<template>
    <Breadcrumbs :items="[{ label: 'Request Service' }]" />
  <Head>
    <title>{{ title }}</title>
    <meta name="description" :content="description" head-key="description">
    <link rel="canonical" :href="canonical" head-key="canonical">
    <meta property="og:title" :content="title" head-key="og:title">
    <meta property="og:description" :content="description" head-key="og:description">
    <meta property="og:url" :content="canonical" head-key="og:url">
    <meta property="og:type" content="website" head-key="og:type">
  </Head>
  <section class="bg-slate-900 text-white px-6 py-10 md:px-12 md:py-14">
    <div class="max-w-6xl mx-auto">
      <p class="text-sm font-semibold tracking-widest uppercase text-red-300">TS Repair Service · Moorpark, CA</p>
      <h1 class="text-3xl md:text-5xl font-semibold leading-tight mt-3">Request Appliance Repair</h1>
      <p class="max-w-3xl text-base md:text-lg leading-8 text-slate-200 mt-5">Tell us what appliance needs attention, what it is doing, and where you are located. We’ll review your request and contact you to discuss service availability.</p>
      <div class="flex flex-col sm:flex-row gap-3 mt-7">
        <a :href="`tel:${phone}`" class="contact-call"><Phone class="size-5" aria-hidden="true" />Call {{ phone }}</a>
        <a href="#service-request" class="contact-request">Request Service</a>
      </div>
    </div>
  </section>

  <TrustStripSection :items="trustItems" class="contact-trust" />

  <section class="bg-gray-100 px-4 py-8 md:p-12">
    <div class="grid lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.35fr)] gap-6 lg:gap-10 max-w-6xl mx-auto items-start">
      <aside class="order-2 lg:order-1 rounded-3xl bg-white border border-gray-200 p-6 md:p-8">
        <h2 class="text-2xl font-semibold text-slate-900">Let’s Talk About Your Repair</h2>
        <a :href="`tel:${phone}`" class="flex items-center gap-3 mt-6 py-2 text-xl font-semibold text-red-700 hover:underline focus-visible:outline-2 focus-visible:outline-red-700"><Phone class="size-5 shrink-0" aria-hidden="true" />{{ phone }}</a>
        <p class="flex items-start gap-3 mt-4 leading-7 text-gray-600"><MapPin class="size-5 mt-1 shrink-0 text-red-700" aria-hidden="true" />Moorpark, Ventura County, and surrounding service areas</p>
        <p class="flex items-start gap-3 mt-4 leading-7 text-gray-600"><Building2 class="size-5 mt-1 shrink-0 text-red-700" aria-hidden="true" />Residential &amp; Commercial Appliance Repair</p>
        <div class="border-t border-gray-200 mt-7 pt-7">
          <h3 class="flex items-center gap-2 text-lg font-semibold text-slate-900"><ClipboardList class="size-5 text-red-700" aria-hidden="true" />Have This Information Ready</h3>
          <ul class="list-disc pl-5 space-y-2 mt-4 text-gray-600 leading-7">
            <li>Appliance type</li><li>Brand</li><li>Model number, if available</li><li>Symptoms or error code</li><li>City or ZIP code</li>
          </ul>
        </div>
      </aside>
      <ScheduleFormSection embedded :selected-service-id="selectedServiceId" class="order-1 lg:order-2 min-w-0" />
    </div>
  </section>

  <section class="px-4 py-10 md:p-12 max-w-7xl mx-auto">
    <h2 class="text-2xl md:text-3xl uppercase font-semibold text-center text-slate-900">What Happens Next</h2>
    <div class="grid md:grid-cols-3 gap-5 mt-8">
      <article v-for="(step, index) in steps" :key="step.title" class="bg-gray-100 rounded-2xl p-6 md:p-8">
        <span class="flex size-10 items-center justify-center rounded-full bg-red-700 text-white font-semibold" aria-hidden="true">{{ index + 1 }}</span>
        <h3 class="text-xl font-semibold text-slate-900 mt-4">{{ step.title }}</h3>
        <p class="text-gray-600 leading-7 mt-3">{{ step.text }}</p>
      </article>
    </div>
  </section>
</template>

<style scoped>
@reference "tailwindcss";
.contact-call, .contact-request { @apply inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl font-semibold text-base focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-red-300 transition-colors; }
.contact-call { @apply bg-red-700 hover:bg-red-800 text-white; }
.contact-request { @apply bg-white text-red-700 hover:bg-red-50; }
.contact-trust :deep(dl) { @apply grid-cols-2 lg:grid-cols-4 gap-5; }
.contact-trust :deep(h3) { @apply text-sm md:text-base pt-1; }
.contact-trust :deep(svg) { @apply size-8; }
.contact-trust :deep([data-aos]) { opacity: 1 !important; transform: none !important; }
</style>
