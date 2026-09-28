<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ items: { type: Array, required: true } });
</script>

<template>
    <nav aria-label="Breadcrumb" class="px-6 md:px-12 py-3 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
            <li><Link href="/" class="inline-block py-2 underline hover:text-red-700 focus-visible:outline-2 focus-visible:outline-red-700">Home</Link></li>
            <template v-for="(item, index) in items" :key="item.label">
                <li aria-hidden="true">/</li>
                <li v-if="index === items.length - 1" aria-current="page" class="py-2">{{ item.label }}</li>
                <li v-else><Link :href="item.href" class="inline-block py-2 underline hover:text-red-700 focus-visible:outline-2 focus-visible:outline-red-700">{{ item.label }}</Link></li>
            </template>
        </ol>
    </nav>
</template>
