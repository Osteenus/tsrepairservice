<script setup>
import { Link } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';

defineProps({
    customerCount: Number,
    newRequestCount: Number,
});
</script>

<template>
    <CrmLayout title="Dashboard">
        <div class="grid gap-3">
            <Link :href="route('crm.requests.index')" class="rounded-xl bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">New website requests</p>
                <p class="text-3xl font-extrabold" :class="newRequestCount && 'text-[#c8000a]'">{{ newRequestCount }}</p>
            </Link>
            <Link :href="route('crm.customers.index')" class="rounded-xl bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Customers</p>
                <p class="text-3xl font-extrabold">{{ customerCount }}</p>
            </Link>
            <Link :href="route('crm.customers.create')" class="flex h-14 items-center justify-center rounded-xl bg-[#c8000a] text-lg font-bold text-white">
                + New customer
            </Link>
        </div>
    </CrmLayout>
</template>
