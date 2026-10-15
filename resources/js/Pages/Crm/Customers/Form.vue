<script setup>
import { useForm } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Field from '@/Components/Crm/Field.vue';
import Button from '@/Components/Crm/Button.vue';

const props = defineProps({
    customer: Object,
});

const form = useForm({
    name: props.customer?.name ?? '',
    phone: props.customer?.phone ?? '',
    email: props.customer?.email ?? '',
    address_line1: props.customer?.address_line1 ?? '',
    city: props.customer?.city ?? '',
    state: props.customer?.state ?? 'CA',
    zip: props.customer?.zip ?? '',
    notes: props.customer?.notes ?? '',
});

const submit = () => props.customer
    ? form.put(route('crm.customers.update', props.customer.id))
    : form.post(route('crm.customers.store'));
</script>

<template>
    <CrmLayout
        :title="customer ? 'Edit customer' : 'New customer'"
        :back="customer ? route('crm.customers.show', customer.id) : route('crm.customers.index')"
    >
        <form class="grid gap-4 rounded-xl bg-white p-4 shadow-sm" @submit.prevent="submit">
            <Field v-model="form.name" label="Name" format="words" :error="form.errors.name" required autocomplete="off" />
            <Field v-model="form.phone" label="Phone" format="phone" placeholder="(805) 555-0100" :error="form.errors.phone" autocomplete="off" />
            <Field v-model="form.email" label="Email" type="email" format="email" :error="form.errors.email" autocomplete="off" />
            <Field v-model="form.address_line1" label="Street address" format="words" :error="form.errors.address_line1" autocomplete="off" />
            <Field v-model="form.city" label="City" format="words" :error="form.errors.city" autocomplete="off" />
            <div class="grid grid-cols-[5rem_1fr] gap-3">
                <Field v-model="form.state" label="State" format="state" :error="form.errors.state" />
                <Field v-model="form.zip" label="ZIP" format="zip" :error="form.errors.zip" />
            </div>
            <Field v-model="form.notes" as="textarea" label="Notes" :error="form.errors.notes" />
            <Button type="submit" :disabled="form.processing">Save customer</Button>
        </form>
    </CrmLayout>
</template>
