<script setup>
import { Link } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';

defineProps({
    requests: Object,
    archived: Boolean,
});
</script>

<template>
    <CrmLayout title="Website requests">
        <div class="mb-3 grid grid-cols-2 gap-1 rounded-lg bg-gray-200 p-1 text-center font-semibold">
            <Link :href="route('crm.requests.index')" class="rounded-md py-2.5" :class="!archived && 'bg-white shadow-sm'">New</Link>
            <Link :href="route('crm.requests.index', { archived: 1 })" class="rounded-md py-2.5" :class="archived && 'bg-white shadow-sm'">Archived</Link>
        </div>

        <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
            <li v-for="request in requests.data" :key="request.id">
                <Link :href="route('crm.requests.show', request.id)" class="block px-4 py-3 active:bg-gray-50">
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="font-semibold">{{ request.name }}</p>
                        <p class="shrink-0 text-xs text-gray-500">{{ request.received }}</p>
                    </div>
                    <p class="text-sm text-gray-600"><span v-if="request.by_phone" class="mr-1 rounded bg-gray-200 px-1.5 py-0.5 text-xs font-semibold text-gray-700">Phone</span>{{ [request.appliance, request.location].filter(Boolean).join(' · ') }}</p>
                </Link>
            </li>
            <li v-if="!requests.data.length" class="px-4 py-6 text-center text-gray-500">No requests.</li>
        </ul>

        <div v-if="requests.prev_page_url || requests.next_page_url" class="mt-3 flex justify-between">
            <Link v-if="requests.prev_page_url" :href="requests.prev_page_url" class="rounded-lg bg-white px-4 py-3 font-semibold shadow-sm">‹ Previous</Link>
            <span v-else />
            <Link v-if="requests.next_page_url" :href="requests.next_page_url" class="rounded-lg bg-white px-4 py-3 font-semibold shadow-sm">Next ›</Link>
        </div>
    </CrmLayout>
</template>
