<script setup>
import { computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Field from '@/Components/Crm/Field.vue';
import Button from '@/Components/Crm/Button.vue';
import { lineAmount, money, totals } from '@/crm/money.js';

const props = defineProps({
    document: Object,
    kinds: Object,
});

const isInvoice = props.document.type === 'invoice';

const form = useForm({
    items: props.document.items.map(item => ({ ...item, unit: item.unit.toFixed(2) })),
    discount: props.document.discount ? props.document.discount.toFixed(2) : '',
    notes: props.document.notes ?? '',
});

const live = computed(() => totals(form.items, form.discount));

const add = kind => form.items.push({
    kind,
    description: kind === 'labor' ? 'Labor: ' : '',
    part_number: '',
    qty: 1,
    unit: '',
    is_warranty: false,
    is_used_or_exchanged: false,
});

const remove = index => form.items.splice(index, 1);

const save = then => form
    .transform(data => ({ ...data, then }))
    .put(route('crm.documents.update', props.document.id), { preserveScroll: true });
const deleteDraft = () => {
    if (confirm('Delete this draft?')) router.post(route('crm.documents.void', props.document.id));
};
</script>

<template>
    <CrmLayout :title="document.title" :back="route('crm.jobs.show', document.job.id)">
        <section class="mb-3 rounded-xl bg-white p-4 shadow-sm">
            <p class="font-semibold">{{ document.job.customer_name }}</p>
            <p class="text-sm text-gray-600">{{ document.job.appliance || 'Appliance not set' }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ document.job.reported_problem }}</p>
            <p v-if="document.revision_of" class="mt-2 rounded-lg bg-amber-50 p-2 text-sm text-amber-900">
                Revision of {{ document.revision_of.number }} ({{ money(document.revision_of.total_cents) }}). The customer must consent to the new total before the extra work.
            </p>
        </section>

        <form class="grid gap-3" @submit.prevent="save('show')">
            <div v-for="(item, index) in form.items" :key="index" class="rounded-xl bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center gap-2">
                    <div class="grid flex-1 grid-cols-3 gap-1 rounded-lg bg-gray-100 p-1 text-center text-sm font-semibold">
                        <button
                            v-for="(label, kind) in kinds"
                            :key="kind"
                            type="button"
                            class="rounded-md py-2"
                            :class="item.kind === kind ? 'bg-white shadow-sm' : 'text-gray-500'"
                            @click="item.kind = kind"
                        >{{ label }}</button>
                    </div>
                    <button type="button" class="flex size-11 items-center justify-center rounded-lg text-gray-500" aria-label="Remove line" @click="remove(index)">
                        <Trash2 class="size-5" />
                    </button>
                </div>
                <div class="grid gap-3">
                    <Field v-model="item.description" label="Description" :error="form.errors[`items.${index}.description`]" required />
                    <Field v-if="item.kind === 'part'" v-model="item.part_number" label="Part number" autocapitalize="characters" :error="form.errors[`items.${index}.part_number`]" />
                    <div class="grid grid-cols-2 gap-3">
                        <Field v-model="item.qty" label="Qty" type="number" inputmode="decimal" step="0.01" min="0.01" :error="form.errors[`items.${index}.qty`]" />
                        <Field v-model="item.unit" label="Unit price, $" type="number" inputmode="decimal" step="0.01" min="0" :disabled="item.is_warranty" :error="form.errors[`items.${index}.unit`]" />
                    </div>
                    <label v-if="isInvoice" class="flex min-h-11 items-center gap-3">
                        <input v-model="item.is_warranty" type="checkbox" class="size-6 rounded text-[#c8000a]">
                        <span>Warranty, no charge</span>
                    </label>
                    <label v-if="item.kind === 'part'" class="flex min-h-11 items-center gap-3">
                        <input v-model="item.is_used_or_exchanged" type="checkbox" class="size-6 rounded text-[#c8000a]">
                        <span>Used or exchanged (rebuilt) part</span>
                    </label>
                    <p class="text-right font-semibold">{{ money(lineAmount(item)) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <Button type="button" variant="secondary" @click="add('labor')">+ Labor</Button>
                <Button type="button" variant="secondary" @click="add('part')">+ Part</Button>
            </div>

            <div class="grid gap-3 rounded-xl bg-white p-4 shadow-sm">
                <Field v-model="form.discount" label="Discount, $" type="number" inputmode="decimal" step="0.01" min="0" :error="form.errors.discount" />
                <Field v-model="form.notes" as="textarea" label="Notes printed on the document" :error="form.errors.notes" />
            </div>

            <p v-if="form.errors.items" class="text-sm text-red-600">{{ form.errors.items }}</p>

            <div class="sticky bottom-16 z-10 -mx-3 border-t border-gray-200 bg-white/95 px-3 pb-3 pt-2 backdrop-blur">
                <div class="mb-2 flex justify-between text-sm text-gray-600">
                    <span>Labor {{ money(live.labor) }} · Parts {{ money(live.parts) }}<template v-if="live.discount"> · −{{ money(live.discount) }}</template></span>
                    <span class="text-lg font-extrabold text-[#c8000a]">{{ money(live.total) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <Button type="submit" variant="dark" :disabled="form.processing">Save</Button>
                    <Button v-if="!isInvoice" type="button" :disabled="form.processing || !form.items.length" @click="save('present')">Present</Button>
                </div>
            </div>
        </form>

        <button type="button" class="mt-4 h-11 w-full text-sm font-semibold text-gray-500 underline" @click="deleteDraft">Delete draft</button>
    </CrmLayout>
</template>
