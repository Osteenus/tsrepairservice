<script setup>
import { Link } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import StatusBadge from '@/Components/Crm/StatusBadge.vue';
import { money } from '@/crm/money.js';

defineProps({
    today: Array,
    upcoming: Array,
    requests: Array,
    newRequestCount: Number,
    unpaid: Array,
    unpaidTotal: Number,
    todayLabel: String,
});
</script>

<template>
    <CrmLayout title="Dashboard">
        <div class="grid grid-cols-2 gap-3">
            <Link :href="route('crm.requests.index')" class="rounded-xl bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">New requests</p>
                <p class="text-3xl font-extrabold" :class="newRequestCount && 'text-[#c8000a]'">{{ newRequestCount }}</p>
            </Link>
            <div class="rounded-xl bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Unpaid</p>
                <p class="text-3xl font-extrabold" :class="unpaidTotal && 'text-[#c8000a]'">{{ money(unpaidTotal) }}</p>
            </div>
        </div>

        <section class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Today · {{ todayLabel }}</h2>
            <ul class="grid gap-2">
                <li v-for="job in today" :key="job.id">
                    <Link :href="route('crm.jobs.show', job.id)" class="block rounded-xl bg-white p-4 shadow-sm active:bg-gray-50">
                        <div class="flex items-start justify-between gap-2">
                            <p><span class="font-extrabold">{{ job.time }}</span> · <span class="font-semibold">{{ job.customer_name }}</span></p>
                            <StatusBadge :status="job.status" :label="job.status_label" />
                        </div>
                        <p class="text-sm text-gray-600">{{ job.appliance || 'Appliance not set' }}</p>
                        <p v-if="job.service_address" class="text-sm text-gray-500">{{ job.service_address }}</p>
                    </Link>
                </li>
                <li v-if="!today.length" class="rounded-xl bg-white p-4 text-center text-gray-500 shadow-sm">No visits scheduled today.</li>
            </ul>
        </section>

        <section v-if="upcoming.length" class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Next 7 days</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <li v-for="job in upcoming" :key="job.id">
                    <Link :href="route('crm.jobs.show', job.id)" class="block px-4 py-3 active:bg-gray-50">
                        <p class="text-sm font-semibold text-[#c8000a]">{{ job.time }}</p>
                        <p class="font-semibold">{{ job.customer_name }}</p>
                        <p class="text-sm text-gray-600">{{ job.appliance || 'Appliance not set' }}</p>
                    </Link>
                </li>
            </ul>
        </section>

        <section v-if="requests.length" class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Website requests</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <li v-for="request in requests" :key="request.id">
                    <Link :href="route('crm.requests.show', request.id)" class="block px-4 py-3 active:bg-gray-50">
                        <div class="flex items-baseline justify-between gap-2">
                            <p class="font-semibold">{{ request.name }}</p>
                            <p class="shrink-0 text-xs text-gray-500">{{ request.received }}</p>
                        </div>
                        <p class="text-sm text-gray-600">{{ request.appliance }} · {{ request.location }}</p>
                    </Link>
                </li>
            </ul>
        </section>

        <section v-if="unpaid.length" class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Unpaid invoices</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <li v-for="invoice in unpaid" :key="invoice.id">
                    <Link :href="route('crm.documents.show', invoice.id)" class="flex items-center justify-between gap-2 px-4 py-3 active:bg-gray-50">
                        <span>
                            <span class="block font-semibold">{{ invoice.customer_name }}</span>
                            <span class="block text-sm text-gray-500">{{ invoice.number }} · {{ invoice.issued_on }}</span>
                        </span>
                        <span class="font-bold text-[#c8000a]">{{ money(invoice.balance) }}</span>
                    </Link>
                </li>
            </ul>
        </section>
    </CrmLayout>
</template>
