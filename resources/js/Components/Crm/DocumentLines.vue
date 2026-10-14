<script setup>
import { money } from '@/crm/money.js';

defineProps({
    document: Object,
});
</script>

<template>
    <div>
        <ul class="divide-y divide-gray-200">
            <li v-for="(item, index) in document.items" :key="index" class="flex justify-between gap-3 py-2">
                <div class="min-w-0">
                    <p class="font-medium">{{ item.description }}</p>
                    <p class="text-xs text-gray-500">
                        <span v-if="item.qty !== 1">{{ item.qty }} × {{ money(Math.round(item.unit * 100)) }}</span>
                        <span v-if="item.part_number"> Part # {{ item.part_number }}</span>
                        <span v-if="item.is_warranty"> · Warranty, no charge</span>
                        <span v-if="item.is_used_or_exchanged"> · Used / exchanged part</span>
                    </p>
                </div>
                <p class="shrink-0 font-semibold">{{ money(item.amount_cents) }}</p>
            </li>
        </ul>
        <dl class="mt-2 grid grid-cols-[1fr_auto] gap-x-4 gap-y-1 border-t border-gray-300 pt-2 text-sm">
            <dt>Service call &amp; labor</dt><dd class="text-right">{{ money(document.totals.labor) }}</dd>
            <dt>Parts</dt><dd class="text-right">{{ money(document.totals.parts) }}</dd>
            <template v-if="document.totals.tax"><dt>Sales tax on parts</dt><dd class="text-right">{{ money(document.totals.tax) }}</dd></template>
            <template v-if="document.totals.discount"><dt>Discount</dt><dd class="text-right">−{{ money(document.totals.discount) }}</dd></template>
            <dt class="text-lg font-extrabold">Total</dt><dd class="text-right text-lg font-extrabold text-[#c8000a]">{{ money(document.totals.total) }}</dd>
        </dl>
    </div>
</template>
