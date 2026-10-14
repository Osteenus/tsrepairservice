<script setup>
import { Link, router } from '@inertiajs/vue3';
import { FileDown } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Button from '@/Components/Crm/Button.vue';
import DocumentLines from '@/Components/Crm/DocumentLines.vue';

const props = defineProps({
    document: Object,
});

const isEstimate = props.document.type === 'estimate';
const post = (name, message = null) => {
    if (message && !confirm(message)) return;
    router.post(route(name, props.document.id));
};
</script>

<template>
    <CrmLayout :title="document.title" :back="route('crm.jobs.show', document.job.id)">
        <section class="rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="font-semibold">{{ document.job.customer_name }}</p>
                    <p class="text-sm text-gray-600">{{ document.job.appliance }}</p>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-bold">{{ document.status_label }}</span>
            </div>
            <p v-if="document.issued_on" class="mt-2 text-sm text-gray-600">
                Issued {{ document.issued_on }}<template v-if="document.valid_until"> · valid until {{ document.valid_until }}</template>
            </p>
            <p v-if="document.expired && document.status === 'issued'" class="mt-2 rounded-lg bg-red-50 p-2 text-sm font-semibold text-red-700">
                This estimate has expired. Create a new estimate before the customer signs.
            </p>
            <p v-if="document.approved_at" class="mt-1 text-sm font-semibold text-green-700">Approved {{ document.approved_at }}</p>
        </section>

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <DocumentLines :document="document" />
            <p v-if="document.notes" class="mt-3 whitespace-pre-line text-sm text-gray-600">{{ document.notes }}</p>
        </section>

        <section v-if="document.signatures.length || document.consent" class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <div v-for="signature in document.signatures" :key="signature.id" class="mb-2">
                <img :src="signature.url" alt="Customer signature" class="h-20 w-auto">
                <p class="text-sm text-gray-600">{{ signature.signed_name }} · {{ signature.signed_at }}</p>
            </div>
            <div v-if="document.consent" class="text-sm text-gray-700">
                <p class="font-semibold">{{ document.consent.method === 'oral' ? 'Oral consent' : 'Written consent' }}: {{ document.consent.authorized_by }}<template v-if="document.consent.phone">, {{ document.consent.phone }}</template></p>
                <p>{{ document.consent.consented_at }} — {{ document.consent.description }}</p>
            </div>
        </section>

        <div class="mt-4 grid gap-2">
            <template v-if="document.status === 'draft'">
                <Link :href="route('crm.documents.edit', document.id)" class="flex h-12 items-center justify-center rounded-lg bg-[#151515] font-bold text-white">Edit</Link>
            </template>
            <template v-if="isEstimate && ['draft', 'issued'].includes(document.status) && !document.expired">
                <Link :href="route('crm.estimates.present', document.id)" class="flex h-12 items-center justify-center rounded-lg bg-[#c8000a] font-bold text-white">Present to customer &amp; sign</Link>
            </template>
            <Button v-if="isEstimate && document.status === 'draft'" variant="secondary" @click="post('crm.estimates.issue')">Issue without signature (send later)</Button>
            <Button v-if="isEstimate && document.status === 'approved'" variant="secondary" @click="post('crm.estimates.revise')">Revised estimate (needs consent)</Button>
            <a :href="route('crm.documents.pdf', document.id)" target="_blank" class="flex h-12 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white font-bold">
                <FileDown class="size-5" /> PDF
            </a>
            <button v-if="['issued', 'approved'].includes(document.status)" type="button" class="h-11 text-sm font-semibold text-gray-500 underline" @click="post('crm.documents.void', `Void ${document.number}? It stays on file marked VOID.`)">Void</button>
        </div>
    </CrmLayout>
</template>
