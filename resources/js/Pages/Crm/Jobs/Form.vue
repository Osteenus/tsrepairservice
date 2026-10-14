<script setup>
import { useForm } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Field from '@/Components/Crm/Field.vue';
import Button from '@/Components/Crm/Button.vue';

const props = defineProps({
    job: Object,
    customer: Object,
    appliances: Array,
    selectedAppliance: Number,
});

const form = useForm({
    customer_id: props.customer.id,
    appliance_id: props.selectedAppliance ?? props.appliances[0]?.id ?? null,
    reported_problem: props.job?.reported_problem ?? '',
    findings: props.job?.findings ?? '',
    scheduled_at: props.job?.scheduled_at ?? '',
    service_address: props.job?.service_address ?? props.customer.address ?? '',
    notes: props.job?.notes ?? '',
});

const submit = () => props.job
    ? form.put(route('crm.jobs.update', props.job.id))
    : form.post(route('crm.jobs.store'));
</script>

<template>
    <CrmLayout
        :title="job ? `Edit job #${job.id}` : 'New job'"
        :back="job ? route('crm.jobs.show', job.id) : route('crm.customers.show', customer.id)"
    >
        <form class="grid gap-4 rounded-xl bg-white p-4 shadow-sm" @submit.prevent="submit">
            <p class="font-semibold">{{ customer.name }}</p>
            <Field v-model="form.appliance_id" as="select" label="Appliance" :error="form.errors.appliance_id">
                <option :value="null">— Not set —</option>
                <option v-for="appliance in appliances" :key="appliance.id" :value="appliance.id">{{ appliance.label }}</option>
            </Field>
            <p v-if="!appliances.length" class="-mt-2 text-sm text-gray-500">Add appliances on the customer page.</p>
            <Field v-model="form.reported_problem" as="textarea" label="Customer's description of the problem" :error="form.errors.reported_problem" required />
            <Field v-model="form.findings" as="textarea" label="Findings / diagnosis" :error="form.errors.findings" />
            <Field v-model="form.scheduled_at" type="datetime-local" label="Scheduled visit" :error="form.errors.scheduled_at" />
            <Field v-model="form.service_address" label="Service address" hint="Defaults to the customer's address." :error="form.errors.service_address" />
            <Field v-model="form.notes" as="textarea" label="Notes" :error="form.errors.notes" />
            <Button type="submit" :disabled="form.processing">Save job</Button>
        </form>
    </CrmLayout>
</template>
