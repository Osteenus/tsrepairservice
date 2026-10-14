<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import { FileDown, Mail, X } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Button from '@/Components/Crm/Button.vue';
import DocumentLines from '@/Components/Crm/DocumentLines.vue';
import Field from '@/Components/Crm/Field.vue';
import { money } from '@/crm/money.js';

const props = defineProps({
    document: Object,
});

const isEstimate = props.document.type === 'estimate';
const post = (name, message = null) => {
    if (message && !confirm(message)) return;
    router.post(route(name, props.document.id), {}, { preserveScroll: true });
};

const methods = { cash: 'Cash', check: 'Check', zelle: 'Zelle', card: 'Card', other: 'Other' };
const today = new Date();
const payment = useForm({
    amount: props.document.balance > 0 ? (props.document.balance / 100).toFixed(2) : '',
    method: 'cash',
    paid_on: `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`,
    note: '',
});
const addPayment = () => payment.post(route('crm.payments.store', props.document.id), {
    preserveScroll: true,
    onSuccess: () => payment.reset('note'),
});
const removePayment = id => {
    if (confirm('Remove this payment?')) router.delete(route('crm.payments.destroy', id), { preserveScroll: true });
};
const email = () => {
    if (confirm(`Email ${props.document.number} to ${props.document.job.customer_email}?`)) post('crm.documents.email');
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

        <p v-if="$page.props.errors.document" class="mt-3 rounded-lg bg-red-50 p-3 text-sm font-semibold text-red-700">{{ $page.props.errors.document }}</p>
        <p v-if="$page.props.errors.amount" class="mt-3 rounded-lg bg-red-50 p-3 text-sm font-semibold text-red-700">{{ $page.props.errors.amount }}</p>

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <DocumentLines :document="document" />
            <p v-if="document.work_performed" class="mt-3 whitespace-pre-line text-sm"><b>Work performed:</b> {{ document.work_performed }}</p>
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

        <section v-if="!isEstimate && document.status === 'draft' && (document.missing.length || document.limit_cents !== null)" class="mt-3 rounded-xl bg-white p-4 text-sm shadow-sm">
            <p v-if="document.limit_cents !== null">Customer authorized: <b>{{ money(document.limit_cents) }}</b> before tax.</p>
            <p v-if="document.missing.length" class="mt-1 font-semibold text-red-700">Before issuing, add: {{ document.missing.join(', ') }}.</p>
        </section>

        <section v-if="!isEstimate && document.status !== 'draft'" class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-baseline justify-between">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">Payments</h2>
                <p class="font-bold" :class="document.balance > 0 ? 'text-[#c8000a]' : 'text-green-700'">
                    {{ document.balance > 0 ? `Balance ${money(document.balance)}` : 'Paid in full' }}
                </p>
            </div>
            <ul v-if="document.payments.length" class="mt-2 divide-y divide-gray-200">
                <li v-for="item in document.payments" :key="item.id" class="flex items-center justify-between gap-2 py-2">
                    <span>
                        <span class="block font-semibold">{{ money(item.amount) }} · {{ item.method }}</span>
                        <span class="block text-sm text-gray-500">{{ item.paid_on }}<template v-if="item.note"> · {{ item.note }}</template></span>
                    </span>
                    <button type="button" class="flex size-11 items-center justify-center text-gray-400" aria-label="Remove payment" @click="removePayment(item.id)"><X class="size-5" /></button>
                </li>
            </ul>
            <form v-if="document.balance > 0 && document.status !== 'void'" class="mt-3 grid gap-3 border-t border-gray-200 pt-3" @submit.prevent="addPayment">
                <div class="grid grid-cols-2 gap-3">
                    <Field v-model="payment.amount" label="Amount, $" type="number" inputmode="decimal" step="0.01" min="0.01" :error="payment.errors.amount" />
                    <Field v-model="payment.paid_on" label="Date" type="date" :error="payment.errors.paid_on" />
                </div>
                <div class="grid grid-cols-5 gap-1 rounded-lg bg-gray-100 p-1 text-center text-sm font-semibold">
                    <button v-for="(label, key) in methods" :key="key" type="button" class="rounded-md py-2.5" :class="payment.method === key ? 'bg-white shadow-sm' : 'text-gray-500'" @click="payment.method = key">{{ label }}</button>
                </div>
                <Field v-model="payment.note" label="Note (check no., etc.)" :error="payment.errors.note" />
                <Button type="submit" variant="dark" :disabled="payment.processing">Record payment</Button>
            </form>
        </section>

        <div class="mt-4 grid gap-2">
            <template v-if="document.status === 'draft'">
                <Link :href="route('crm.documents.edit', document.id)" class="flex h-12 items-center justify-center rounded-lg bg-[#151515] font-bold text-white">Edit</Link>
            </template>
            <template v-if="isEstimate && ['draft', 'issued'].includes(document.status) && !document.expired">
                <Link :href="route('crm.estimates.present', document.id)" class="flex h-12 items-center justify-center rounded-lg bg-[#c8000a] font-bold text-white">Present to customer &amp; sign</Link>
            </template>
            <Button v-if="!isEstimate && document.status === 'draft'" :disabled="document.missing.length > 0" @click="post('crm.invoices.issue')">Issue invoice</Button>
            <Button v-if="isEstimate && document.status === 'draft'" variant="secondary" @click="post('crm.estimates.issue')">Issue without signature (send later)</Button>
            <Button v-if="isEstimate && document.status === 'approved'" variant="secondary" @click="post('crm.estimates.revise')">Revised estimate (needs consent)</Button>
            <a :href="route('crm.documents.pdf', document.id)" target="_blank" class="flex h-12 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white font-bold">
                <FileDown class="size-5" /> PDF
            </a>
            <button v-if="['issued', 'approved'].includes(document.status) && document.job.customer_email" type="button" class="flex h-12 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white font-bold" @click="email">
                <Mail class="size-5" /> Email to customer
            </button>
            <p v-if="document.emailed_at" class="text-center text-sm text-gray-500">Email sent {{ document.emailed_at }}</p>
            <p v-if="['issued', 'approved'].includes(document.status) && !document.job.customer_email" class="text-center text-sm text-gray-500">Add the customer's email to send this by email.</p>
            <button v-if="['issued', 'approved'].includes(document.status)" type="button" class="h-11 text-sm font-semibold text-gray-500 underline" @click="post('crm.documents.void', `Void ${document.number}? It stays on file marked VOID.`)">Void</button>
        </div>
    </CrmLayout>
</template>
