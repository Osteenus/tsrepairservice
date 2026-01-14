<!-- ElectronicsRepairService.vue
  Vue 3 + Tailwind CSS (Composition API only, single <script setup>)
  Marketing-style service page inspired by the structure of "General Electronics" service pages:
  - Hero + CTA
  - Service sections with bullet lists
  - Why choose us
  - Diagnostics value section
  - Policies (optional but common for electronics/bench repair)
  - FAQ accordion (click to expand)
  - Contact / Request form
-->

<template>
  <main class="bg-white text-slate-900">
    <!-- Top nav (optional) -->
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
      <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
          <!-- LOGO PLACEHOLDER -->
          <div class="grid h-10 w-10 place-items-center rounded-lg bg-slate-900 text-sm font-bold text-white">
            AR
          </div>
          <div class="leading-tight">
            <div class="text-sm font-semibold">[Your Company Name]</div>
            <div class="text-xs text-slate-500">Appliance & Electronics Repair</div>
          </div>
        </div>

        <nav class="hidden items-center gap-6 text-sm md:flex">
          <a class="text-slate-600 hover:text-slate-900" href="#services">Services</a>
          <a class="text-slate-600 hover:text-slate-900" href="#why">Why Us</a>
          <a class="text-slate-600 hover:text-slate-900" href="#policies">Policies</a>
          <a class="text-slate-600 hover:text-slate-900" href="#faq">FAQ</a>
          <a class="text-slate-600 hover:text-slate-900" href="#contact">Contact</a>
        </nav>

        <div class="flex items-center gap-2">
          <a
            class="hidden rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-50 sm:inline-flex"
            :href="`tel:${phoneTel}`"
          >
            Call {{ phonePretty }}
          </a>
          <a
            class="inline-flex rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
            href="#contact"
          >
            Request Service
          </a>
        </div>
      </div>
    </header>

    <!-- HERO -->
    <section class="border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
          <!-- Copy -->
          <div>
            <p class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
              Component-level diagnostics • Boards • Power supplies • Amplifiers • HVAC controls
            </p>

            <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">
              Electronic Control & Circuit Board Repair in <span class="underline decoration-slate-300">[Your City, CA]</span>
            </h1>

            <p class="mt-4 text-base leading-7 text-slate-600">
              Don’t replace expensive electronics if a repair makes sense. We specialize in
              <span class="font-semibold text-slate-900">component-level repair</span> for appliance and HVAC controls,
              audio equipment, and commercial boards. We’re a <span class="font-semibold text-slate-900">young, family-owned</span> company
              focused on honest estimates and reliable results.
            </p>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
              <a
                class="inline-flex items-center justify-center rounded-md bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                href="#contact"
              >
                Get an Estimate
              </a>
              <a
                class="inline-flex items-center justify-center rounded-md border border-slate-300 px-6 py-3 text-sm font-semibold hover:bg-white"
                href="#services"
              >
                What We Repair
              </a>
            </div>

            <div class="mt-7 grid gap-3 sm:grid-cols-2">
              <FeatureLine title="Clear pricing" text="You’ll get a quote before we proceed." />
              <FeatureLine title="Bench testing" text="We verify the repair under real conditions." />
              <FeatureLine title="Quality parts" text="OEM or high-quality replacements." />
              <FeatureLine title="Warranty" text="Parts & labor warranty on repairs." />
            </div>
          </div>

          <!-- HERO IMAGE PLACEHOLDER -->
          <div class="lg:justify-self-end">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
              <!-- IMAGE PLACE A: Technician at electronics bench / board repair -->
              <img :src="images.hero" alt="Electronics repair bench" class="h-80 w-full object-cover sm:h-[26rem]" />
            </div>

            <div class="mt-4 grid grid-cols-3 gap-3">
              <!-- IMAGE PLACE B -->
              <img :src="images.thumb1" alt="Board close-up" class="h-20 w-full rounded-xl border border-slate-200 object-cover" />
              <!-- IMAGE PLACE C -->
              <img :src="images.thumb2" alt="Test equipment" class="h-20 w-full rounded-xl border border-slate-200 object-cover" />
              <!-- IMAGE PLACE D -->
              <img :src="images.thumb3" alt="Repaired board" class="h-20 w-full rounded-xl border border-slate-200 object-cover" />
            </div>

            <p class="mt-2 text-xs text-slate-500">
              Replace these photos anytime. Suggested: bench, board close-up, test equipment, finished board.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="bg-white">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
          <div class="max-w-2xl">
            <h2 class="text-2xl font-bold tracking-tight">Complete Electronics Repair Services</h2>
            <p class="mt-2 text-slate-600">
              We diagnose and repair electronic controls across residential, commercial, and specialty equipment.
              If you’re not sure, send photos and the model number.
            </p>
          </div>
          <a
            class="inline-flex rounded-md bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
            href="#contact"
          >
            Contact Us
          </a>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
          <!-- Service block 1 -->
          <ServiceBlock
            title="Appliance Electronic Controls"
            subtitle="Control boards, UI boards, inverter and motor drive modules."
            :items="[
              'Washer & dryer control boards and user interface panels',
              'Refrigerator, dishwasher, oven/range control boards',
              'Inverter boards and motor control / drive boards',
              'Power supply sections and connector repairs'
            ]"
            :image="images.service1"
            imageHint="IMAGE PLACE E: appliance control board / UI board"
          />

          <!-- Service block 2 -->
          <ServiceBlock
            title="HVAC & Climate Electronics"
            subtitle="Furnace/AC boards, thermostats, and motor controllers."
            :items="[
              'Furnace control boards and ignition/control circuits',
              'AC / heat pump modules and relays',
              'Thermostats and smart thermostat board issues',
              'ECM / variable-speed motor controller boards'
            ]"
            :image="images.service2"
            imageHint="IMAGE PLACE F: furnace board / HVAC module"
          />

          <!-- Service block 3 -->
          <ServiceBlock
            title="Audio, Amplifiers & Commercial Boards"
            subtitle="Amplifiers, receivers, and specialized equipment electronics."
            :items="[
              'Amplifiers, receivers, and power supply failures',
              'No-output, distortion, protection circuit issues',
              'Commercial equipment boards and modules',
              'Hard-to-find or discontinued boards (case-by-case)'
            ]"
            :image="images.service3"
            imageHint="IMAGE PLACE G: amplifier internals / commercial board"
          />
        </div>
      </div>
    </section>

    <!-- WHY + DIAGNOSTICS -->
    <section id="why" class="bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-start">
          <div>
            <h2 class="text-2xl font-bold tracking-tight">Why Choose [Your Company Name]?</h2>
            <p class="mt-2 text-slate-600">
              We’re not a “swap parts and hope” shop. We isolate failures, repair the circuit, and verify performance.
              As a young, family-owned team, we focus on communication and long-term trust.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
              <ValueCard title="Component-level repair" text="We repair boards when practical instead of replacing the whole module." />
              <ValueCard title="Cost-effective options" text="Repair often saves money—especially for premium and commercial equipment." />
              <ValueCard title="Clear estimates" text="You’ll know the cost before we start work." />
              <ValueCard title="Warranty included" text="Parts & labor warranty on completed repairs." />
            </div>
          </div>

          <div>
            <h2 class="text-2xl font-bold tracking-tight">The Importance of Professional Diagnostics</h2>
            <p class="mt-2 text-slate-600">
              Many failures come from power supply sections, heat-stressed parts, relays, connectors, or damaged traces.
              With proper tools and testing, we can often repair the board and restore reliable operation.
            </p>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <h3 class="text-sm font-semibold">Typical problems we fix</h3>
              <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                <li class="text-sm text-slate-700">• No power / dead board</li>
                <li class="text-sm text-slate-700">• No display / frozen controls</li>
                <li class="text-sm text-slate-700">• Random shutdown / intermittent</li>
                <li class="text-sm text-slate-700">• Blown fuses / shorts</li>
                <li class="text-sm text-slate-700">• Burnt components / traces</li>
                <li class="text-sm text-slate-700">• Audio distortion / no output</li>
              </ul>

              <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                <!-- IMAGE PLACE H: scope/multimeter testing -->
                <img :src="images.diagnostics" alt="Diagnostics testing" class="h-48 w-full object-cover" />
              </div>

              <p class="mt-3 text-xs text-slate-500">
                IMAGE PLACE H: diagnostic photo (multimeter / oscilloscope / thermal camera).
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- POLICIES (optional but helpful) -->
    <section id="policies" class="bg-white">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight">Service Policies</h2>
        <p class="mt-2 max-w-3xl text-slate-600">
          Electronics repair is often bench-based and component-level. These policies keep the process clear and fair.
          (Customize this section to your business rules.)
        </p>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
          <PolicyCard
            title="Diagnostics"
            text="After inspection/testing, we provide a repair recommendation and estimate. If a board is not repairable or not cost-effective, we’ll let you know."
          />
          <PolicyCard
            title="Parts"
            text="We use OEM or high-quality components when available. Some specialty parts may require additional lead time."
          />
          <PolicyCard
            title="Warranty"
            text="Completed repairs include a parts and labor warranty. Warranty terms vary by board type and operating conditions."
          />
        </div>
      </div>
    </section>

    <!-- FAQ (Accordion) -->
    <section id="faq" class="bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
          <div>
            <h2 class="text-2xl font-bold tracking-tight">Frequently Asked Questions</h2>
            <p class="mt-2 text-slate-600">Click a question to expand the answer.</p>
          </div>

          <a
            class="inline-flex rounded-md bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
            href="#contact"
          >
            Request Service
          </a>
        </div>

        <div class="mt-8 rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div v-for="(item, idx) in faqs" :key="idx" class="border-b border-slate-200 last:border-b-0">
            <button
              type="button"
              class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left"
              @click="toggleFaq(idx)"
              :aria-expanded="openFaq === idx"
            >
              <span class="text-sm font-semibold">{{ item.q }}</span>
              <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-slate-700">
                {{ openFaq === idx ? "–" : "+" }}
              </span>
            </button>

            <div v-show="openFaq === idx" class="px-5 pb-5">
              <p class="text-sm leading-6 text-slate-600">
                {{ item.a }}
              </p>
            </div>
          </div>
        </div>

        <p class="mt-3 text-xs text-slate-500">
          Tip: You can set <span class="font-mono">openFaq = 0</span> to keep the first answer open by default.
        </p>
      </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="bg-white">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-start">
          <div>
            <h2 class="text-2xl font-bold tracking-tight">Contact Us</h2>
            <p class="mt-2 text-slate-600">
              Send photos (front/back), model number, and symptoms. The more details you share, the faster we can estimate.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
              <ContactChip label="Phone" :value="phonePretty" :href="`tel:${phoneTel}`" />
              <ContactChip label="Email" value="[your@email.com]" href="mailto:your@email.com" />
              <ContactChip label="Service Area" value="[Your City] + Nearby Areas" href="#services" />
              <ContactChip label="Hours" value="[Mon–Sat: 9–6]" href="#policies" />
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
              <!-- IMAGE PLACE I: office / storefront / technician with board -->
              <img :src="images.contact" alt="Contact image" class="h-56 w-full object-cover" />
            </div>
            <p class="mt-2 text-xs text-slate-500">IMAGE PLACE I: friendly brand photo (team/bench/storefront).</p>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Request Electronics Repair</h3>
            <p class="mt-1 text-sm text-slate-600">We’ll respond ASAP with next steps and an estimate.</p>

            <form class="mt-5 space-y-4" @submit.prevent="submit">
              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label class="text-sm font-medium">Name</label>
                  <input
                    v-model="form.name"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
                    placeholder="Your name"
                    required
                  />
                </div>
                <div>
                  <label class="text-sm font-medium">Phone</label>
                  <input
                    v-model="form.phone"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
                    placeholder="(###) ###-####"
                    required
                  />
                </div>
              </div>

              <div class="grid gap-4 sm:grid-cols-2">
                <div>
                  <label class="text-sm font-medium">Service type</label>
                  <select
                    v-model="form.type"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
                  >
                    <option>Appliance control board</option>
                    <option>HVAC control board</option>
                    <option>Amplifier / audio equipment</option>
                    <option>Commercial equipment board</option>
                    <option>Other electronics</option>
                  </select>
                </div>
                <div>
                  <label class="text-sm font-medium">Brand / model</label>
                  <input
                    v-model="form.model"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
                    placeholder="Example: Carrier / ABC123"
                  />
                </div>
              </div>

              <div>
                <label class="text-sm font-medium">Problem description</label>
                <textarea
                  v-model="form.desc"
                  rows="4"
                  class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
                  placeholder="Symptoms, error codes, when it happens, any visible damage, etc."
                  required
                ></textarea>
              </div>

              <!-- Upload placeholder UI -->
              <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                <div class="text-sm font-semibold">Photos (optional)</div>
                <p class="mt-1 text-sm text-slate-600">
                  Place your upload widget here. Recommended: board front/back, connectors, and model label.
                </p>
              </div>

              <button
                type="submit"
                class="w-full rounded-md bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800"
              >
                Send Request
              </button>

              <p class="text-xs text-slate-500">
                By submitting, you agree we may contact you about diagnostics, repair options, and estimates.
              </p>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <div class="text-sm text-slate-600">
            <span class="font-semibold text-slate-900">[Your Company Name]</span> —
            Young, family-owned electronics repair in <span class="font-semibold text-slate-900">[Your City, CA]</span>.
          </div>
          <div class="flex gap-3">
            <a class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" href="#contact">Request Service</a>
            <a class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-white" :href="`tel:${phoneTel}`">Call</a>
          </div>
        </div>
      </div>
    </footer>
  </main>
</template>

<script setup>
import { reactive, ref } from "vue";

/** Customize */
const phonePretty = "(123) 456-7890";
const phoneTel = "+11234567890";

/** Images: replace with your own */
const images = {
  hero: "/assets/photos/electronics-hero.jpg",     // A
  thumb1: "/assets/photos/thumb-board.jpg",       // B
  thumb2: "/assets/photos/thumb-scope.jpg",       // C
  thumb3: "/assets/photos/thumb-finished.jpg",    // D
  service1: "/assets/photos/service-appliance.jpg", // E
  service2: "/assets/photos/service-hvac.jpg",      // F
  service3: "/assets/photos/service-audio.jpg",     // G
  diagnostics: "/assets/photos/diagnostics.jpg",    // H
  contact: "/assets/photos/contact.jpg",            // I
};

/** Simple request form (wire to your backend later) */
const form = reactive({
  name: "",
  phone: "",
  type: "Appliance control board",
  model: "",
  desc: "",
});

function submit() {
  alert("Submitted (demo). Connect this form to your backend/email service.");
  form.name = "";
  form.phone = "";
  form.type = "Appliance control board";
  form.model = "";
  form.desc = "";
}

/** FAQ Accordion */
const faqs = [
  {
    q: "Is it better to repair or replace an electronic control board?",
    a: "Often, repair is more affordable than replacement—especially for expensive, commercial, or discontinued boards. We’ll recommend the most cost-effective option after diagnostics.",
  },
  {
    q: "Do you repair appliance and HVAC control boards?",
    a: "Yes. We repair appliance control boards (washer, dryer, oven, fridge, dishwasher) and HVAC electronics (furnace boards, AC/heat pump controls, thermostats, ECM controllers).",
  },
  {
    q: "Can you fix boards damaged by power surges?",
    a: "In many cases, yes. Surge damage commonly affects the power supply section (fuses, diodes, regulators, capacitors), which is often repairable.",
  },
  {
    q: "Do you offer bench repair without installation?",
    a: "Yes. You can bring the board to us for bench diagnostics and repair. If you need installation, we can discuss options depending on the equipment type.",
  },
  {
    q: "Do repairs include a warranty?",
    a: "Yes. Repairs include a parts and labor warranty. Terms depend on the board type and operating conditions; we’ll confirm details with your estimate.",
  },
  {
    q: "What should I send for the fastest estimate?",
    a: "Clear photos of the board (front/back), model/part number, connector photos, and a short description of symptoms or error codes.",
  },
];

const openFaq = ref(-1); // set to 0 if you want the first item open by default

function toggleFaq(idx) {
  openFaq.value = openFaq.value === idx ? -1 : idx;
}

/**
 * Local UI components (Composition API only)
 * Defined as simple objects with props + template strings.
 */
const FeatureLine = {
  props: { title: String, text: String },
  template: `
    <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="mt-0.5 grid h-6 w-6 place-items-center rounded-full bg-slate-900 text-xs font-bold text-white">✓</div>
      <div>
        <div class="text-sm font-semibold">{{ title }}</div>
        <div class="text-sm text-slate-600">{{ text }}</div>
      </div>
    </div>
  `,
};

const ValueCard = {
  props: { title: String, text: String },
  template: `
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">{{ title }}</div>
      <div class="mt-2 text-sm leading-6 text-slate-600">{{ text }}</div>
    </div>
  `,
};

const PolicyCard = {
  props: { title: String, text: String },
  template: `
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
      <div class="text-sm font-semibold">{{ title }}</div>
      <div class="mt-2 text-sm leading-6 text-slate-600">{{ text }}</div>
    </div>
  `,
};

const ContactChip = {
  props: { label: String, value: String, href: String },
  template: `
    <a :href="href" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:bg-slate-50">
      <div class="text-xs font-semibold text-slate-500">{{ label }}</div>
      <div class="mt-1 text-sm font-semibold text-slate-900">{{ value }}</div>
    </a>
  `,
};

const ServiceBlock = {
  props: { title: String, subtitle: String, items: Array, image: String, imageHint: String },
  template: `
    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <img :src="image" :alt="title" class="h-44 w-full object-cover bg-slate-100" />
      <div class="p-6">
        <h3 class="text-base font-semibold">{{ title }}</h3>
        <p class="mt-1 text-sm text-slate-600">{{ subtitle }}</p>
        <ul class="mt-4 space-y-2">
          <li v-for="(it, i) in items" :key="i" class="text-sm text-slate-700">
            <span class="font-semibold text-slate-900">•</span><span class="ml-2">{{ it }}</span>
          </li>
        </ul>
        <p class="mt-4 text-xs text-slate-400">{{ imageHint }}</p>
      </div>
    </article>
  `,
};
</script>
