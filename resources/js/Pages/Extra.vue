<template>
  <main class="bg-white text-slate-900">
    <!-- Hero -->
    <section class="relative overflow-hidden bg-slate-950 text-white">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900/70"></div>
      <div class="relative mx-auto max-w-7xl px-6 py-20 lg:px-8 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
          <div>
            <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-1 text-sm font-medium tracking-wide text-slate-200">
              Residential & Commercial Refrigeration Repair
            </span>

            <h1 class="mt-6 max-w-4xl text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
              Refrigerator Repair in Moorpark, CA
            </h1>

            <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-200">
              Fast and dependable refrigerator repair when you need it most.
              We provide same-day service for both residential and commercial refrigeration systems.
              Our technician quickly diagnoses the issue and explains the repair clearly before starting any work.
              We focus on reliable, long-term solutions — not temporary fixes.
              Serving homeowners and businesses across Moorpark and Ventura County.
            </p>

            <div class="mt-8 flex flex-col gap-4 sm:flex-row">
              <a
                href="tel:+18050000000"
                class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-4 text-base font-semibold text-slate-950 shadow-lg transition hover:-translate-y-0.5"
              >
                Call Now
              </a>
              <a
                href="#booking"
                class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-base font-semibold text-white transition hover:bg-white/15"
              >
                Book Service
              </a>
            </div>

            <div class="mt-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
              <div
                v-for="item in heroHighlights"
                :key="item"
                class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100"
              >
                {{ item }}
              </div>
            </div>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/10 p-6 shadow-2xl backdrop-blur">
            <div class="rounded-2xl bg-white p-6 text-slate-900">
              <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">
                Fast Refrigerator Service
              </p>
              <h2 class="mt-3 text-2xl font-bold tracking-tight">
                Need your fridge fixed today?
              </h2>
              <p class="mt-3 text-sm leading-6 text-slate-600">
                Call for fast scheduling, clear diagnostics, and reliable repair for residential and commercial refrigeration equipment.
              </p>

              <div class="mt-6 space-y-3">
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">Same-day appointments when available</div>
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">Service throughout Moorpark & Ventura County</div>
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">Residential homes, restaurants, cafes, and businesses</div>
              </div>

              <a
                href="tel:+18050000000"
                class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-6 py-4 text-base font-semibold text-white transition hover:bg-slate-800"
              >
                Call TS Repair Service
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Trust Bar -->
    <section class="border-b border-slate-200 bg-slate-50">
      <div class="mx-auto grid max-w-7xl gap-4 px-6 py-6 text-sm font-medium text-slate-700 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div v-for="item in trustBar" :key="item" class="rounded-2xl bg-white px-4 py-4 shadow-sm ring-1 ring-slate-200">
          {{ item }}
        </div>
      </div>
    </section>

    <!-- Residential / Commercial -->
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
      <div class="mx-auto max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Who We Serve</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Residential and Commercial Refrigerator Repair</h2>
        <p class="mt-4 text-lg leading-8 text-slate-600">
          Whether you need a household refrigerator fixed or a commercial unit repaired quickly, we provide responsive service and dependable solutions.
        </p>
      </div>

      <div class="mt-12 grid gap-8 lg:grid-cols-2">
        <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-xl">🏠</div>
            <h3 class="text-2xl font-bold tracking-tight">Residential Refrigerator Repair</h3>
          </div>
          <p class="mt-5 leading-7 text-slate-600">
            We repair everyday home refrigerators quickly and professionally to help keep your kitchen running and your food protected.
          </p>
          <ul class="mt-6 space-y-3 text-slate-700">
            <li v-for="item in residentialTypes" :key="item" class="flex gap-3">
              <span class="mt-1 text-blue-700">✓</span>
              <span>{{ item }}</span>
            </li>
          </ul>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-slate-950 p-8 text-white shadow-sm">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-xl">🏢</div>
            <h3 class="text-2xl font-bold tracking-tight">Commercial Refrigerator Repair</h3>
          </div>
          <p class="mt-5 leading-7 text-slate-300">
            We service commercial refrigeration equipment for businesses that need fast response times, accurate diagnostics, and minimal downtime.
          </p>
          <ul class="mt-6 space-y-3 text-slate-200">
            <li v-for="item in commercialTypes" :key="item" class="flex gap-3">
              <span class="mt-1 text-cyan-300">✓</span>
              <span>{{ item }}</span>
            </li>
          </ul>
        </article>
      </div>
    </section>

    <!-- Common Problems -->
    <section class="bg-slate-50">
      <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Common Issues</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">We Fix Refrigerator Problems Fast</h2>
            <p class="mt-4 text-lg leading-8 text-slate-600">
              From cooling problems to compressor issues, we diagnose the source of the failure and recommend the right repair.
            </p>
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div
              v-for="problem in commonProblems"
              :key="problem"
              class="rounded-3xl border border-slate-200 bg-white px-5 py-5 shadow-sm"
            >
              <div class="flex items-start gap-3">
                <span class="mt-0.5 text-blue-700">•</span>
                <p class="font-medium text-slate-800">{{ problem }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Commercial Priority -->
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
      <div class="rounded-[2rem] bg-gradient-to-r from-slate-950 to-blue-900 px-8 py-10 text-white lg:px-12 lg:py-12">
        <div class="grid gap-8 lg:grid-cols-[1fr_0.8fr] lg:items-center">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">Commercial Priority Service</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Fast Commercial Refrigeration Repair for Local Businesses</h2>
            <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-200">
              If your business depends on refrigeration, fast service matters. We help restaurants, cafes, grocery-related businesses, and property managers reduce downtime and protect products, inventory, and daily operations.
            </p>
          </div>

          <div class="grid gap-3">
            <div v-for="item in commercialBenefits" :key="item" class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 text-slate-100">
              {{ item }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Diagnostics -->
    <section class="bg-white">
      <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:items-start">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Expert Diagnostics</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Professional Refrigerator Diagnostics and Repair</h2>
            <p class="mt-4 text-lg leading-8 text-slate-600">
              We identify the actual cause of the issue before recommending any repair. That means clearer answers, more reliable results, and fewer repeat problems.
            </p>
          </div>

          <div class="grid gap-4 sm:grid-cols-2">
            <div
              v-for="item in diagnosticItems"
              :key="item.title"
              class="rounded-3xl border border-slate-200 bg-slate-50 p-6"
            >
              <h3 class="text-lg font-semibold">{{ item.title }}</h3>
              <p class="mt-2 text-sm leading-6 text-slate-600">{{ item.text }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Service Area -->
    <section class="bg-slate-50">
      <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Service Area</p>
          <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Refrigerator Repair in Moorpark and Ventura County</h2>
          <p class="mt-4 text-lg leading-8 text-slate-600">
            We provide service throughout the local area with fast scheduling and dependable support for homes and businesses.
          </p>
        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
          <div
            v-for="city in serviceAreas"
            :key="city"
            class="rounded-3xl border border-slate-200 bg-white px-5 py-5 text-center font-semibold text-slate-800 shadow-sm"
          >
            {{ city }}
          </div>
        </div>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Why TS Repair Service</p>
          <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">A Better Choice for Refrigerator Repair</h2>
          <p class="mt-4 text-lg leading-8 text-slate-600">
            We combine responsive scheduling, clear communication, and reliable workmanship to make the repair process easier from start to finish.
          </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div
            v-for="item in whyChooseUs"
            :key="item.title"
            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
          >
            <h3 class="text-lg font-semibold text-slate-900">{{ item.title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ item.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="bg-slate-950 text-white">
      <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">FAQ</p>
          <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Frequently Asked Questions</h2>
        </div>

        <div class="mt-10 grid gap-4 lg:grid-cols-2">
          <article
            v-for="item in faqs"
            :key="item.question"
            class="rounded-3xl border border-white/10 bg-white/5 p-6"
          >
            <h3 class="text-lg font-semibold">{{ item.question }}</h3>
            <p class="mt-3 text-sm leading-7 text-slate-300">{{ item.answer }}</p>
          </article>
        </div>
      </div>
    </section>

    <!-- Final CTA -->
    <section id="booking" class="bg-white">
      <div class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-slate-50 px-8 py-10 text-center shadow-sm lg:px-12">
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Book Service</p>
          <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Need Refrigerator Repair Today?</h2>
          <p class="mt-4 text-lg leading-8 text-slate-600">
            Call now for fast scheduling and dependable service for residential and commercial refrigeration systems in Moorpark and nearby areas.
          </p>
          <div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
            <a
              href="tel:+18050000000"
              class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-6 py-4 text-base font-semibold text-white transition hover:bg-slate-800"
            >
              Call Now
            </a>
            <a
              href="/contact"
              class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-base font-semibold text-slate-900 transition hover:bg-slate-100"
            >
              Request Service
            </a>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
const heroHighlights = [
  'Same-Day Service Available',
  'Residential & Commercial Repairs',
  'Warranty on Repairs',
  'Local Moorpark Service',
]

const trustBar = [
  'Same-Day Service Available',
  'Residential & Commercial Repairs',
  'Warranty on Repairs',
  'Transparent Pricing',
]

const residentialTypes = [
  'Top freezer and bottom freezer refrigerators',
  'Side-by-side refrigerators',
  'French door refrigerators',
  'Built-in refrigerators',
]

const commercialTypes = [
  'Restaurant refrigerators',
  'Reach-in and walk-in coolers',
  'Prep tables and commercial refrigeration units',
  'Beverage and display refrigerators',
]

const commonProblems = [
  'Refrigerator not cooling',
  'Commercial fridge temperature issues',
  'Freezer icing up',
  'Water leaking from the unit',
  'Ice maker not working',
  'Loud or unusual noises',
  'Compressor issues',
  'Fridge running constantly',
]

const commercialBenefits = [
  'Fast response for refrigeration-dependent businesses',
  'Clear diagnostics and efficient repair recommendations',
  'Reduced downtime for restaurants, cafes, and commercial properties',
  'Reliable long-term repair solutions',
]

const diagnosticItems = [
  {
    title: 'Compressor Systems',
    text: 'We inspect compressor performance and related cooling issues to identify the source of the failure.',
  },
  {
    title: 'Fans and Airflow',
    text: 'Evaporator and condenser fan problems can affect cooling and efficiency, and we diagnose them carefully.',
  },
  {
    title: 'Thermostats and Sensors',
    text: 'Faulty controls, sensors, and temperature regulation components can cause unstable performance.',
  },
  {
    title: 'Defrost and Control Systems',
    text: 'We troubleshoot defrost components and control boards to restore reliable operation.',
  },
]

const serviceAreas = ['Moorpark', 'Simi Valley', 'Thousand Oaks', 'Camarillo', 'Oxnard']

const whyChooseUs = [
  {
    title: 'Local and Responsive',
    text: 'We serve Moorpark and nearby cities with fast scheduling and practical service you can count on.',
  },
  {
    title: 'Clear Communication',
    text: 'We explain the problem, the repair approach, and pricing before the work begins.',
  },
  {
    title: 'Residential and Commercial Experience',
    text: 'We work on both household refrigerators and commercial refrigeration equipment.',
  },
  {
    title: 'Reliable Repairs',
    text: 'Our focus is on long-term solutions that help reduce repeat failures and downtime.',
  },
]

const faqs = [
  {
    question: 'Do you repair commercial refrigerators?',
    answer:
      'Yes. We service both residential and commercial refrigeration systems, including many business-related units.',
  },
  {
    question: 'Do you offer same-day refrigerator repair?',
    answer:
      'Yes, same-day appointments may be available depending on scheduling and service area.',
  },
  {
    question: 'Should I repair or replace my refrigerator?',
    answer:
      'In many cases, repair is the better option, especially when the unit is newer or the problem is isolated to a repairable component.',
  },
  {
    question: 'Do you serve businesses outside of Moorpark?',
    answer:
      'Yes. We also serve nearby Ventura County cities such as Simi Valley, Thousand Oaks, Camarillo, and Oxnard.',
  },
]
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
