<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    current: { type: String, required: true },
});

const tabs = [
    { name: 'calls', label: 'Calls', route: 'crm.calls.index' },
    { name: 'line', label: 'Line settings', route: 'crm.phone.show' },
];
</script>

<template>
    <div class="mb-3 grid grid-cols-2 gap-1 rounded-lg bg-gray-200 p-1 font-semibold">
        <Link v-for="tab in tabs" :key="tab.name" :href="route(tab.route)" class="flex h-11 items-center justify-center rounded-md"
            :class="tab.name === current ? 'bg-white shadow-sm' : 'text-gray-600'">{{ tab.label }}</Link>
    </div>
</template>
