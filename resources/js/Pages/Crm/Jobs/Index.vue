<script setup>
import { Link } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import StatusBadge from '@/Components/Crm/StatusBadge.vue';

defineProps({
    jobs: Object,
    filter: String,
});

const filters = { today: 'Today', open: 'Open', all: 'All' };
</script>

<template>
    <CrmLayout title="Jobs">
        <div class="mb-3 grid grid-cols-3 gap-1 rounded-lg bg-gray-200 p-1 text-center font-semibold">
            <Link v-for="(label, key) in filters" :key="key" :href="route('crm.jobs.index', { filter: key })" class="rounded-md py-2.5" :class="filter === key && 'bg-white shadow-sm'">{{ label }}</Link>
        </div>

        <ul class="grid gap-2">
            <li v-for="job in jobs.data" :key="job.id">
                <Link :href="route('crm.jobs.show', job.id)" class="block rounded-xl bg-white p-4 shadow-sm active:bg-gray-50">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-semibold">{{ job.customer_name }}</p>
                        <StatusBadge :status="job.status" :label="job.status_label" />
                    </div>
                    <p class="text-sm text-gray-600">#{{ job.id }} · {{ job.appliance || 'Appliance not set' }}</p>
                    <p v-if="job.scheduled" class="mt-1 text-sm font-semibold text-[#c8000a]">{{ job.scheduled }}</p>
                    <p v-if="job.service_address" class="text-sm text-gray-500">{{ job.service_address }}</p>
                </Link>
            </li>
            <li v-if="!jobs.data.length" class="rounded-xl bg-white px-4 py-6 text-center text-gray-500 shadow-sm">No jobs.</li>
        </ul>

        <div v-if="jobs.prev_page_url || jobs.next_page_url" class="mt-3 flex justify-between">
            <Link v-if="jobs.prev_page_url" :href="jobs.prev_page_url" class="rounded-lg bg-white px-4 py-3 font-semibold shadow-sm">‹ Previous</Link>
            <span v-else />
            <Link v-if="jobs.next_page_url" :href="jobs.next_page_url" class="rounded-lg bg-white px-4 py-3 font-semibold shadow-sm">Next ›</Link>
        </div>
    </CrmLayout>
</template>
