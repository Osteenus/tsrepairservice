<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { PhoneIncoming, PhoneMissed, PhoneOutgoing } from 'lucide-vue-next';

const props = defineProps({
    call: { type: Object, required: true },
    showCustomer: { type: Boolean, default: true },
});

const labels = {
    ringing: 'In progress',
    initiated: 'Calling you',
    'not-connected': 'Not connected',
    'no-answer': 'No answer',
    busy: 'Busy',
    failed: 'Failed',
    canceled: 'Hung up',
};

const missed = computed(() => ['not-connected', 'no-answer', 'busy', 'failed', 'canceled'].includes(props.call.status));
const icon = computed(() => (missed.value ? PhoneMissed : props.call.direction === 'inbound' ? PhoneIncoming : PhoneOutgoing));
const duration = computed(() => {
    const value = props.call.duration_seconds;
    if (!value) return null;
    return value >= 60 ? `${Math.floor(value / 60)}m ${value % 60}s` : `${value}s`;
});
const details = computed(() => [props.call.time, duration.value, labels[props.call.status]].filter(Boolean).join(' · '));
const tel = computed(() => (props.call.number || '').replace(/[^\d+]/g, ''));

const block = () => {
    if (confirm(`Block ${props.call.display_number}? Calls from this number will be rejected.`)) {
        router.post(route('crm.blocked-numbers.store'), { phone: props.call.number }, { preserveScroll: true });
    }
};
</script>

<template>
    <li class="flex items-center gap-3 px-4 py-3">
        <component :is="icon" class="size-5 shrink-0" :class="missed ? 'text-[#c8000a]' : 'text-gray-500'"
            :aria-label="call.direction === 'inbound' ? 'Incoming' : 'Outgoing'" />
        <div class="min-w-0 flex-1">
            <Link v-if="showCustomer && call.customer" :href="route('crm.customers.show', call.customer.id)" class="block truncate font-semibold text-[#c8000a]">
                {{ call.customer.name }}
            </Link>
            <a v-if="tel" :href="`tel:${tel}`" class="block truncate" :class="showCustomer && call.customer ? 'text-sm text-gray-700' : 'font-semibold'">{{ call.display_number }}</a>
            <span v-else class="block font-semibold">{{ call.display_number }}</span>
            <span class="block text-sm text-gray-500">{{ details }}</span>
        </div>
        <span v-if="call.blocked" class="shrink-0 text-sm font-semibold text-gray-500">Blocked</span>
        <button v-else-if="call.number" type="button" class="h-11 shrink-0 rounded-lg border border-gray-300 px-3 text-sm font-bold active:bg-gray-100" @click="block">
            Block
        </button>
    </li>
</template>
