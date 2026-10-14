<script setup>
import { Link, router } from '@inertiajs/vue3';
import { MapPin, Pencil, Phone } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import StatusBadge from '@/Components/Crm/StatusBadge.vue';

const props = defineProps({
    job: Object,
    statuses: Object,
    manualStatuses: Array,
});

const tel = (props.job.customer.phone || '').replace(/[^\d+]/g, '');
const setStatus = status => router.patch(route('crm.jobs.status', props.job.id), { status }, { preserveScroll: true });
</script>

<template>
    <CrmLayout :title="`Job #${job.id}`" :back="route('crm.jobs.index')">
        <template #actions>
            <Link :href="route('crm.jobs.edit', job.id)" class="flex size-11 items-center justify-center rounded-lg hover:bg-white/10" aria-label="Edit job">
                <Pencil class="size-5" />
            </Link>
        </template>

        <section class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <Link :href="route('crm.customers.show', job.customer.id)" class="text-lg font-semibold underline decoration-gray-300">{{ job.customer.name }}</Link>
                <StatusBadge :status="job.status" :label="job.status_label" />
            </div>
            <p v-if="job.scheduled" class="mt-1 font-semibold text-[#c8000a]">{{ job.scheduled }}</p>
            <p v-if="job.service_address" class="text-gray-600">{{ job.service_address }}</p>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <a v-if="tel" :href="`tel:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#c8000a] font-bold text-white"><Phone class="size-5" />Call</a>
                <a v-if="job.service_address" :href="`https://maps.google.com/?q=${encodeURIComponent(job.service_address)}`" target="_blank" rel="noopener" class="flex h-12 items-center justify-center gap-1 rounded-lg border border-gray-300 font-bold"><MapPin class="size-5" />Map</a>
            </div>
        </section>

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">Appliance</h2>
            <template v-if="job.appliance">
                <p class="font-semibold">{{ [job.appliance.brand, job.appliance.type].filter(Boolean).join(' ') }}</p>
                <p class="text-sm text-gray-600">
                    Model {{ job.appliance.model || '—' }} · S/N {{ job.appliance.serial || '—' }}
                </p>
            </template>
            <p v-else class="text-gray-500">Not set</p>

            <h2 class="mt-3 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Reported problem</h2>
            <p class="whitespace-pre-line">{{ job.reported_problem }}</p>

            <template v-if="job.findings">
                <h2 class="mt-3 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Findings</h2>
                <p class="whitespace-pre-line">{{ job.findings }}</p>
            </template>

            <template v-if="job.notes">
                <h2 class="mt-3 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Notes</h2>
                <p class="whitespace-pre-line text-gray-700">{{ job.notes }}</p>
            </template>

            <Link v-if="job.repair_request_id" :href="route('crm.requests.show', job.repair_request_id)" class="mt-3 inline-block text-sm text-gray-500 underline">
                From website request #{{ job.repair_request_id }}
            </Link>
        </section>

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <h2 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Status</h2>
            <div class="grid grid-cols-2 gap-2">
                <button
                    v-for="status in manualStatuses"
                    :key="status"
                    type="button"
                    class="h-12 rounded-lg border font-semibold"
                    :class="job.status === status ? 'border-[#151515] bg-[#151515] text-white' : 'border-gray-300 bg-white'"
                    :disabled="job.status === status"
                    @click="setStatus(status)"
                >
                    {{ statuses[status] }}
                </button>
            </div>
        </section>
    </CrmLayout>
</template>
