<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { MapPin, MessageSquare, Pencil, Phone, PhoneForwarded } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import ApplianceFields from '@/Components/Crm/ApplianceFields.vue';
import Button from '@/Components/Crm/Button.vue';
import CallRow from '@/Components/Crm/CallRow.vue';
import StatusBadge from '@/Components/Crm/StatusBadge.vue';

const props = defineProps({
    customer: Object,
    appliances: Array,
    jobs: Array,
    applianceTypes: Array,
    applianceBrands: Array,
    calls: Array,
    phoneBlocked: Boolean,
});

const address = [props.customer.address_line1, props.customer.city, [props.customer.state, props.customer.zip].filter(Boolean).join(' ')]
    .filter(Boolean).join(', ');
const tel = (props.customer.phone || '').replace(/[^\d+]/g, '');
const callFromBusinessLine = () => {
    if (confirm('Twilio will ring your phone first. Answer and press 1 to connect.')) router.post(route('crm.customers.call', props.customer.id), {}, { preserveScroll: true });
};
const blockPhone = () => {
    if (confirm(`Block ${props.customer.phone}? Calls from this number will be rejected.`)) router.post(route('crm.blocked-numbers.store'), { phone: props.customer.phone, reason: props.customer.name }, { preserveScroll: true });
};

const editing = ref(null);
const blank = { type: '', brand: '', model: '', serial: '', notes: '' };
const form = useForm({ ...blank });

const startAdd = () => {
    form.defaults({ ...blank });
    form.reset();
    form.clearErrors();
    editing.value = 'new';
};

const startEdit = appliance => {
    form.defaults({ type: appliance.type, brand: appliance.brand ?? '', model: appliance.model ?? '', serial: appliance.serial ?? '', notes: appliance.notes ?? '' });
    form.reset();
    form.clearErrors();
    editing.value = appliance.id;
};

const save = () => {
    const options = { preserveScroll: true, onSuccess: () => (editing.value = null) };
    editing.value === 'new'
        ? form.post(route('crm.appliances.store', props.customer.id), options)
        : form.put(route('crm.appliances.update', editing.value), options);
};
</script>

<template>
    <CrmLayout :title="customer.name" :back="route('crm.customers.index')">
        <template #actions>
            <Link :href="route('crm.customers.edit', customer.id)" class="flex size-11 items-center justify-center rounded-lg hover:bg-white/10" aria-label="Edit customer">
                <Pencil class="size-5" />
            </Link>
        </template>

        <section class="rounded-xl bg-white p-4 shadow-sm">
            <p v-if="customer.phone" class="text-lg font-semibold">{{ customer.phone }}</p>
            <p v-if="customer.email" class="text-gray-600">{{ customer.email }}</p>
            <p v-if="address" class="mt-1 text-gray-600">{{ address }}</p>
            <div class="mt-3 grid grid-cols-3 gap-2">
                <a v-if="tel" :href="`tel:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#c8000a] font-bold text-white"><Phone class="size-5" />Call</a>
                <a v-if="tel" :href="`sms:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#151515] font-bold text-white"><MessageSquare class="size-5" />Text</a>
                <a v-if="address" :href="`https://maps.google.com/?q=${encodeURIComponent(address)}`" target="_blank" rel="noopener" class="flex h-12 items-center justify-center gap-1 rounded-lg border border-gray-300 font-bold"><MapPin class="size-5" />Map</a>
            </div>
            <button v-if="tel" type="button" class="mt-2 flex h-12 w-full items-center justify-center gap-1 rounded-lg border border-[#c8000a] font-bold text-[#c8000a]" @click="callFromBusinessLine"><PhoneForwarded class="size-5" />Call from business line</button>
            <p v-if="phoneBlocked" class="mt-2 text-center text-sm font-semibold text-gray-500">Calls from this number are blocked (Phone → Calls to unblock).</p>
            <button v-else-if="tel" type="button" class="mt-2 h-11 w-full rounded-lg text-sm font-semibold text-gray-500 active:bg-gray-100" @click="blockPhone">Block this number</button>
            <p v-if="customer.notes" class="mt-3 whitespace-pre-line text-sm text-gray-700">{{ customer.notes }}</p>
        </section>

        <Link :href="route('crm.jobs.create', { customer: customer.id })" class="mt-3 flex h-12 items-center justify-center rounded-lg bg-[#151515] font-bold text-white">
            + New job
        </Link>

        <section class="mt-4">
            <div class="mb-2 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#c8000a]">Appliances</h2>
                <button v-if="editing === null" type="button" class="h-11 px-2 font-semibold text-[#c8000a]" @click="startAdd">+ Add</button>
            </div>

            <ul class="grid gap-2">
                <li v-for="appliance in appliances" :key="appliance.id" class="rounded-xl bg-white p-4 shadow-sm">
                    <button v-if="editing !== appliance.id" type="button" class="block w-full text-left" @click="startEdit(appliance)">
                        <p class="font-semibold">{{ [appliance.brand, appliance.type].filter(Boolean).join(' ') }}</p>
                        <p class="text-sm text-gray-600">
                            <span v-if="appliance.model">Model {{ appliance.model }}</span>
                            <span v-if="appliance.serial"> · S/N {{ appliance.serial }}</span>
                        </p>
                        <p v-if="appliance.notes" class="mt-1 text-sm text-gray-500">{{ appliance.notes }}</p>
                    </button>
                    <form v-else class="grid gap-3" @submit.prevent="save">
                        <ApplianceFields :form="form" :types="applianceTypes" :brands="applianceBrands" />
                        <div class="grid grid-cols-2 gap-2">
                            <Button type="button" variant="secondary" @click="editing = null">Cancel</Button>
                            <Button type="submit" :disabled="form.processing">Save</Button>
                        </div>
                    </form>
                </li>
                <li v-if="editing === 'new'" class="rounded-xl bg-white p-4 shadow-sm">
                    <form class="grid gap-3" @submit.prevent="save">
                        <ApplianceFields :form="form" :types="applianceTypes" :brands="applianceBrands" />
                        <div class="grid grid-cols-2 gap-2">
                            <Button type="button" variant="secondary" @click="editing = null">Cancel</Button>
                            <Button type="submit" :disabled="form.processing">Add</Button>
                        </div>
                    </form>
                </li>
                <li v-if="!appliances.length && editing !== 'new'" class="rounded-xl bg-white p-4 text-center text-gray-500 shadow-sm">No appliances yet.</li>
            </ul>
        </section>

        <section v-if="calls.length" class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Calls</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <CallRow v-for="call in calls" :key="call.id" :call="call" :show-customer="false" />
            </ul>
        </section>

        <section v-if="jobs.length" class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Job history</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <li v-for="job in jobs" :key="job.id">
                    <Link :href="route('crm.jobs.show', job.id)" class="flex items-center justify-between gap-2 px-4 py-3 active:bg-gray-50">
                        <span>
                            <span class="block font-semibold">#{{ job.id }} · {{ job.appliance || 'Appliance not set' }}</span>
                            <span class="block text-sm text-gray-500">{{ job.date }}</span>
                        </span>
                        <StatusBadge :status="job.status" :label="job.status_label" />
                    </Link>
                </li>
            </ul>
        </section>
    </CrmLayout>
</template>
