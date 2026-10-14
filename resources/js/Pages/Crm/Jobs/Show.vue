<script setup>
import { Link, router } from '@inertiajs/vue3';
import { MapPin, Pencil, Phone } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import StatusBadge from '@/Components/Crm/StatusBadge.vue';
import Button from '@/Components/Crm/Button.vue';
import JobPhotos from '@/Components/Crm/JobPhotos.vue';
import { money } from '@/crm/money.js';

const props = defineProps({
    job: Object,
    statuses: Object,
    manualStatuses: Array,
    documents: Array,
    photos: Array,
    photoKinds: Object,
});

const tel = (props.job.customer.phone || '').replace(/[^\d+]/g, '');
const setStatus = status => router.patch(route('crm.jobs.status', props.job.id), { status }, { preserveScroll: true });
const createEstimate = () => router.post(route('crm.estimates.store', props.job.id));
const createInvoice = () => router.post(route('crm.invoices.store', props.job.id));
const hasEstimate = props.documents.some(d => d.type === 'estimate' && ['issued', 'approved'].includes(d.status));
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
            <h2 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Estimates &amp; invoices</h2>
            <ul v-if="documents.length" class="mb-3 divide-y divide-gray-200">
                <li v-for="document in documents" :key="document.id">
                    <Link :href="document.href" class="flex items-center justify-between gap-2 py-3">
                        <span>
                            <span class="block font-semibold">{{ document.title }}</span>
                            <span class="block text-sm text-gray-500">{{ document.status_label }}</span>
                        </span>
                        <span class="font-semibold" :class="document.status === 'void' && 'text-gray-400 line-through'">{{ money(document.total) }}</span>
                    </Link>
                </li>
            </ul>
            <p v-if="$page.props.errors.document" class="mb-2 text-sm text-red-600">{{ $page.props.errors.document }}</p>
            <div class="grid gap-2" :class="hasEstimate && 'grid-cols-2'">
                <Button :variant="hasEstimate ? 'secondary' : 'primary'" @click="createEstimate">Create estimate</Button>
                <Button v-if="hasEstimate" @click="createInvoice">Create invoice</Button>
            </div>
        </section>

        <JobPhotos :job-id="job.id" :photos="photos" :kinds="photoKinds" />

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <h2 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Status</h2>
            <p v-if="$page.props.errors.status" class="mb-2 text-sm text-red-600">{{ $page.props.errors.status }}</p>
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
