<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from '@/Components/Crm/Button.vue';
import DocumentLines from '@/Components/Crm/DocumentLines.vue';
import Field from '@/Components/Crm/Field.vue';
import SignaturePad from '@/Components/Crm/SignaturePad.vue';
import { money } from '@/crm/money.js';

const props = defineProps({
    document: Object,
});

const isInvoice = props.document.type === 'invoice';
const isRevision = !!props.document.revision_of;
const pad = n => String(n).padStart(2, '0');
const now = new Date();

const form = useForm({
    signature: null,
    work_accepted: false,
    signed_name: props.document.job.customer_name,
    consent: {
        method: 'written',
        authorized_by: props.document.job.customer_name,
        phone: props.document.job.customer_phone ?? '',
        consented_at: `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`,
        description: '',
    },
});

const oral = computed(() => isRevision && form.consent.method === 'oral');
const canSubmit = computed(() => oral.value || form.signature);
const submit = () => {
    const message = isInvoice
        ? 'Save the customer\'s signature on this invoice?'
        : (oral.value ? 'Record oral consent and approve this revised estimate?' : 'Save the signature and approve this estimate?');
    if (!confirm(message)) return;

    form
        .transform(data => (isInvoice ? { signature: data.signature, signed_name: data.signed_name, work_accepted: data.work_accepted } : data))
        .post(route(isInvoice ? 'crm.invoices.sign' : 'crm.estimates.approve', props.document.id));
};
</script>

<template>
    <Head :title="`Sign ${document.title}`">
        <meta name="robots" content="noindex, nofollow">
    </Head>

    <div class="min-h-screen bg-gray-100 pb-8 text-gray-900">
        <header class="border-b-4 border-[#c8000a] bg-[#151515] px-4 py-3 text-white">
            <div class="mx-auto flex max-w-3xl items-center justify-between">
                <img src="/storage/img/logo.png" alt="Tech Solutions Repair Service" class="h-9 w-auto rounded bg-white px-1">
                <Link :href="route('crm.documents.show', document.id)" class="px-2 py-2 text-sm underline">Close</Link>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-3 py-4">
            <h1 class="text-2xl font-extrabold">{{ isInvoice ? `Invoice ${document.number}` : (isRevision ? 'Revised estimate' : 'Written estimate') }}</h1>
            <p class="text-gray-600">{{ document.job.customer_name }} · {{ document.job.appliance }}</p>

            <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
                <DocumentLines :document="document" />
            </section>

            <section v-if="isInvoice && document.work_performed" class="mt-3 rounded-xl bg-white p-4 text-sm text-gray-700 shadow-sm">
                <p><b>Work performed:</b> {{ document.work_performed }}</p>
            </section>

            <section v-if="!isInvoice" class="mt-3 rounded-xl bg-white p-4 text-sm text-gray-700 shadow-sm">
                <p>{{ document.diagnostic_terms }}</p>
                <p class="mt-2">Sales tax and other applicable taxes are not included in this estimate.</p>
                <p v-if="isRevision" class="mt-2 font-semibold">Previous authorized total: {{ money(document.revision_of.total_cents) }}. New total: {{ money(document.totals.total) }}.</p>
            </section>

            <form class="mt-3 grid gap-4 rounded-xl bg-white p-4 shadow-sm" @submit.prevent="submit">
                <template v-if="isRevision">
                    <div class="grid grid-cols-2 gap-1 rounded-lg bg-gray-100 p-1 text-center font-semibold">
                        <button type="button" class="rounded-md py-2.5" :class="!oral && 'bg-white shadow-sm'" @click="form.consent.method = 'written'">Customer signs</button>
                        <button type="button" class="rounded-md py-2.5" :class="oral && 'bg-white shadow-sm'" @click="form.consent.method = 'oral'">Oral (phone) consent</button>
                    </div>
                    <Field v-model="form.consent.description" as="textarea" label="Added or changed work and parts" :error="form.errors['consent.description']" required />
                </template>

                <template v-if="isInvoice">
                    <label class="flex items-start gap-3">
                        <input v-model="form.work_accepted" type="checkbox" class="mt-0.5 size-6 shrink-0 rounded text-[#c8000a]">
                        <span class="text-sm font-semibold">Repairs satisfactorily completed.</span>
                    </label>
                    <SignaturePad v-model="form.signature" />
                    <p v-if="form.errors.signature" class="-mt-3 text-sm text-red-600">{{ form.errors.signature }}</p>
                    <Field v-model="form.signed_name" label="Printed name" format="words" :error="form.errors.signed_name" required />
                </template>

                <template v-else-if="!oral">
                    <p class="text-sm font-semibold">
                        I received this written estimate and authorize the repairs described above. The service dealer may not charge for work done or parts supplied above this estimate total without my prior consent.
                    </p>
                    <SignaturePad v-model="form.signature" />
                    <p v-if="form.errors.signature" class="-mt-3 text-sm text-red-600">{{ form.errors.signature }}</p>
                    <Field v-model="form.signed_name" label="Printed name" format="words" :error="form.errors.signed_name" required />
                </template>

                <template v-else>
                    <Field v-model="form.consent.authorized_by" label="Who authorized" format="words" :error="form.errors['consent.authorized_by']" required />
                    <Field v-model="form.consent.phone" label="Phone number" format="phone" :error="form.errors['consent.phone']" required />
                    <Field v-model="form.consent.consented_at" label="Date and time of consent" type="datetime-local" :error="form.errors['consent.consented_at']" required />
                </template>

                <p v-if="form.errors.document" class="text-sm text-red-600">{{ form.errors.document }}</p>
                <Button type="submit" :disabled="form.processing || !canSubmit">
                    {{ isInvoice ? 'Sign invoice' : (oral ? 'Record consent' : 'Approve & sign') }} · {{ money(document.totals.total) }}
                </Button>
            </form>
        </main>
    </div>
</template>
