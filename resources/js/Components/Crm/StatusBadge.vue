<script setup>
defineProps({
    status: { type: String, required: true },
    label: { type: String, required: true },
});

const colors = {
    new: 'bg-blue-100 text-blue-800',
    estimate_sent: 'bg-amber-100 text-amber-800',
    approved: 'bg-violet-100 text-violet-800',
    in_progress: 'bg-orange-100 text-orange-800',
    completed: 'bg-teal-100 text-teal-800',
    paid: 'bg-green-100 text-green-800',
    cancelled: 'bg-gray-200 text-gray-600',
};
</script>

<template>
    <span class="inline-block whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-bold" :class="colors[status]">{{ label }}</span>
</template>
