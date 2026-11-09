<script setup>
import { computed, onUnmounted, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { FileText, PhoneForwarded, PhoneIncoming, PhoneMissed, PhoneOutgoing, Play, X } from 'lucide-vue-next';

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

const open = ref(false);
const autoplay = ref(false);
const show = (play) => {
    autoplay.value = play;
    open.value = true;
};
const pending = computed(() => props.call.transcript_status === 'pending');
const canTranscribe = computed(() => props.call.recorded && [null, 'failed'].includes(props.call.transcript_status));

const transcribe = () => {
    const minutes = Math.max(1, Math.ceil((props.call.recording_duration_seconds || 0) / 60));
    if (confirm(`Transcribe this ${minutes} min recording? Twilio charges for each transcript.`)) {
        show(false);
        router.post(route('crm.calls.transcribe', props.call.id), {}, { preserveScroll: true });
    }
};

// While Twilio prepares the text, check for it every 15 seconds.
let poll;
watch([open, pending], ([isOpen, isPending]) => {
    clearInterval(poll);
    if (isOpen && isPending) poll = setInterval(() => router.reload({ only: ['calls'] }), 15000);
}, { immediate: true });
onUnmounted(() => clearInterval(poll));

const callBack = () => {
    if (confirm(`Call ${props.call.customer?.name || props.call.display_number} from the business line? Twilio will ring your phone first. Answer and press 1 to connect.`)) {
        router.post(route('crm.call'), { phone: props.call.number, name: props.call.customer?.name }, { preserveScroll: true });
    }
};

const block = () => {
    if (confirm(`Block ${props.call.display_number}? Calls from this number will be rejected.`)) {
        router.post(route('crm.blocked-numbers.store'), { phone: props.call.number }, { preserveScroll: true });
    }
};
</script>

<template>
    <li class="px-4 py-3">
        <div class="flex items-center gap-3">
            <component :is="icon" class="size-5 shrink-0" :class="missed ? 'text-[#c8000a]' : 'text-gray-500'"
                :aria-label="call.direction === 'inbound' ? 'Incoming' : 'Outgoing'" />
            <div class="min-w-0 flex-1">
                <Link v-if="showCustomer && call.customer" :href="route('crm.customers.show', call.customer.id)" class="block truncate font-semibold text-[#c8000a]">
                    {{ call.customer.name }}
                </Link>
                <a v-if="tel" :href="`tel:${tel}`" class="block truncate" :class="showCustomer && call.customer ? 'text-sm text-gray-700' : 'font-semibold'">{{ call.display_number }}</a>
                <span v-else class="block font-semibold">{{ call.display_number }}</span>
                <span v-if="call.internal" class="mt-0.5 inline-block rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-bold text-sky-800">Test</span>
                <span class="block text-sm text-gray-500">{{ details }}</span>
            </div>
            <button v-if="call.callable" type="button" class="flex h-11 shrink-0 items-center gap-1 rounded-lg border border-[#c8000a] px-3 text-sm font-bold text-[#c8000a] active:bg-red-50"
                :aria-label="`Call back ${call.display_number} from the business line`" @click="callBack">
                <PhoneForwarded class="size-4" />Call back
            </button>
            <span v-if="call.blocked" class="shrink-0 text-sm font-semibold text-gray-500">Blocked</span>
            <button v-else-if="call.blockable" type="button" class="h-11 shrink-0 rounded-lg border border-gray-300 px-3 text-sm font-bold active:bg-gray-100" @click="block">
                Block
            </button>
        </div>

        <div v-if="call.recorded" class="mt-2 grid grid-cols-2 gap-2 pl-8">
            <button type="button" class="flex h-11 items-center justify-center gap-1 rounded-lg border border-gray-300 text-sm font-bold active:bg-gray-100"
                :aria-expanded="open" @click="open ? (open = false) : show(true)">
                <component :is="open ? X : Play" class="size-4" />{{ open ? 'Close' : 'Play' }}
            </button>
            <button v-if="canTranscribe" type="button" class="flex h-11 items-center justify-center gap-1 rounded-lg border border-gray-300 text-sm font-bold active:bg-gray-100" @click="transcribe">
                <FileText class="size-4" />{{ call.transcript_status === 'failed' ? 'Try again' : 'Transcribe' }}
            </button>
            <button v-else-if="!open" type="button" class="flex h-11 items-center justify-center gap-1 rounded-lg border border-gray-300 text-sm font-bold active:bg-gray-100" @click="show(false)">
                <FileText class="size-4" />{{ pending ? 'Preparing...' : 'Transcript' }}
            </button>
        </div>

        <div v-if="open" class="mt-2 pl-8">
            <audio controls :autoplay="autoplay" preload="metadata" class="w-full" :src="route('crm.calls.recording', call.id)">
                Your browser cannot play this recording.
            </audio>
            <p v-if="pending" class="mt-2 text-sm text-gray-500">Preparing...</p>
            <p v-else-if="call.transcript_status === 'failed'" class="mt-2 text-sm text-[#c8000a]">The transcript could not be made.</p>
            <p v-else-if="call.transcript" class="mt-2 whitespace-pre-line rounded-lg bg-gray-50 p-3 text-sm text-gray-800">{{ call.transcript }}</p>
        </div>
    </li>
</template>
