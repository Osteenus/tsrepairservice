<script setup>
import { computed, nextTick, ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  embedded: { type: Boolean, default: false },
  selectedServiceId: { type: Number, default: null },
});
const page = usePage();
const services = computed(() => page.props.repair.services);
const phone = computed(() => page.props.repair.phone);
const formElement = ref(null);
const resultElement = ref(null);
const received = computed(() => page.props.flash?.requestReceived === true);
const form = useForm({
  name: '', phone: '', email: '', location: '', serviceId: props.selectedServiceId ?? '',
  brand: '', model: '', description: '', website: '',
});
watch(() => props.selectedServiceId, value => { form.serviceId = value ?? ''; });
const fields = [
  { key: 'name', label: 'Name', type: 'text', autocomplete: 'name', required: true, max: 255 },
  { key: 'phone', label: 'Phone Number', type: 'tel', autocomplete: 'tel', required: true, max: 50 },
  { key: 'email', label: 'Email', type: 'email', autocomplete: 'email', max: 255 },
  { key: 'location', label: 'City or ZIP Code', type: 'text', required: true, max: 120 },
  { key: 'serviceId', label: 'Appliance Type', type: 'select', required: true },
  { key: 'brand', label: 'Brand', type: 'text', placeholder: 'Example: Whirlpool, GE, Samsung', max: 120 },
  { key: 'model', label: 'Model Number', type: 'text', placeholder: 'Usually found on the appliance label', max: 120 },
  { key: 'description', label: 'Describe the Problem', type: 'textarea', required: true, max: 5000,
    placeholder: 'Describe the symptoms, error code, unusual sound, leak, or when the problem started' },
];
const submit = () => {
  if (form.processing) return;
  form.post('/contact', {
    preserveScroll: true,
    onSuccess: async () => {
      if (!received.value) return;
      form.reset();
      await nextTick();
      resultElement.value?.focus();
    },
    onError: async () => {
      await nextTick();
      const field = formElement.value?.querySelector('[aria-invalid="true"]');
      (field ?? resultElement.value)?.focus();
    },
  });
};
</script>

<template>
  <section id="schedule" :class="embedded ? '' : 'bg-slate-900 px-4 py-8 md:p-12'">
    <div id="service-request" class="scroll-mt-8 bg-white rounded-3xl p-6 sm:p-8 lg:p-10 max-w-3xl mx-auto shadow-sm border border-gray-200">
      <div ref="resultElement" tabindex="-1" aria-live="polite" aria-atomic="true" class="rounded-xl focus-visible:outline-2 focus-visible:outline-red-700 focus-visible:outline-offset-4">
        <div v-if="received" class="rounded-2xl border border-green-200 bg-green-50 p-6 text-slate-900">
          <h2 class="text-2xl font-semibold">Your Request Has Been Received</h2>
          <p class="mt-4 leading-7">We’ll review the information and contact you using the details provided. For urgent assistance, call {{ phone }}.</p>
          <a :href="`tel:${phone}`" class="request-button mt-6">Call {{ phone }}</a>
        </div>
        <p v-else-if="form.errors.submission || form.errors.website" class="mb-6 rounded-xl bg-red-50 p-4 text-red-800">{{ form.errors.submission || form.errors.website }}</p>
        <p v-else-if="Object.keys(form.errors).length" class="mb-6 text-red-800">Please check the highlighted fields. Your details have been kept.</p>
      </div>
      <form v-if="!received" ref="formElement" @submit.prevent="submit" :aria-busy="form.processing" aria-labelledby="request-heading">
        <h2 id="request-heading" class="text-2xl md:text-3xl font-semibold text-slate-900">Request Service</h2>
        <p class="mt-3 mb-6 leading-7 text-gray-600">Share a few details about the appliance and the problem. Required fields are marked with an asterisk.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div v-for="field in fields" :key="field.key" :class="{ 'sm:col-span-2': ['model', 'description'].includes(field.key) }" class="min-w-0">
            <label :for="`request-${field.key}`" class="block text-sm font-semibold text-slate-800 mb-2">{{ field.label }}<span v-if="field.required" class="text-red-700" aria-hidden="true"> *</span></label>
            <select v-if="field.type === 'select'" :id="`request-${field.key}`" v-model="form[field.key]" :name="field.key" required aria-required="true"
              :aria-invalid="!!form.errors[field.key]" :aria-describedby="form.errors[field.key] ? `error-${field.key}` : undefined" class="request-field">
              <option disabled value="">Choose an appliance...</option>
              <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }}</option>
            </select>
            <textarea v-else-if="field.type === 'textarea'" :id="`request-${field.key}`" v-model="form[field.key]" :name="field.key" required aria-required="true" rows="5" :maxlength="field.max"
              :placeholder="field.placeholder" :aria-invalid="!!form.errors[field.key]" :aria-describedby="form.errors[field.key] ? `error-${field.key}` : undefined" class="request-field resize-y" />
            <input v-else :id="`request-${field.key}`" v-model="form[field.key]" :name="field.key" :type="field.type" :autocomplete="field.autocomplete"
              :required="field.required" :aria-required="!!field.required" :maxlength="field.max" :placeholder="field.placeholder"
              :aria-invalid="!!form.errors[field.key]" :aria-describedby="form.errors[field.key] ? `error-${field.key}` : undefined" class="request-field">
            <p v-if="form.errors[field.key]" :id="`error-${field.key}`" class="text-sm text-red-800 mt-2">{{ form.errors[field.key] }}</p>
          </div>
        </div>
        <div class="request-trap" aria-hidden="true" inert>
          <label for="request-website">Leave this field empty</label>
          <input id="request-website" v-model="form.website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <p class="mt-6 text-sm leading-6 text-gray-600">This is a service request, not a confirmed appointment. Availability depends on your appliance, location, and our schedule.</p>
        <button type="submit" :disabled="form.processing" class="request-button mt-5 disabled:opacity-60 disabled:cursor-wait">{{ form.processing ? 'Sending…' : 'Request Service' }}</button>
      </form>
    </div>
  </section>
</template>

<style scoped>
@reference "tailwindcss";
.request-field { @apply w-full min-w-0 rounded-xl border border-gray-400 bg-white px-3 py-3 text-base text-slate-900 placeholder:text-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700; }
.request-field[aria-invalid="true"] { @apply border-red-700 bg-red-50; }
.request-button { @apply flex w-full items-center justify-center rounded-2xl bg-red-700 px-5 py-4 text-base font-semibold text-white hover:bg-red-800 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-red-700 transition-colors; }
.request-trap { position: absolute; width: 1px; height: 1px; overflow: hidden; clip-path: inset(50%); }
</style>
