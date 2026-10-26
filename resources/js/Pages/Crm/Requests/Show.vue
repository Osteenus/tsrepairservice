<script setup>
import { Link, router } from '@inertiajs/vue3';
import { MessageSquare, Phone, PhoneForwarded } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Button from '@/Components/Crm/Button.vue';

const props = defineProps({
    request: Object,
});

const tel = (props.request.phone || '').replace(/[^\d+]/g, '');
const calledBack = () => router.post(route('crm.requests.called-back', props.request.id), {}, { preserveScroll: true });
const voiceStatus = { new: 'No call-back requested', callback_due: 'Call back by', callback_done: 'Called back', overdue: 'Call-back overdue' };
const callFromBusinessLine = () => {
    if (confirm('Twilio will ring your phone first. Answer and press 1 to connect.')) router.post(route('crm.call'), { phone: props.request.phone, name: props.request.name }, { preserveScroll: true });
};
const convert = () => {
    if (confirm('Create a customer and a job from this request?')) router.post(route('crm.requests.convert', props.request.id));
};
const archive = () => {
    const message = props.request.archived ? 'Move this request back to New?' : 'Archive this request without creating a job?';
    if (confirm(message)) router.post(route('crm.requests.archive', props.request.id));
};
</script>

<template>
    <CrmLayout :title="request.name || 'Request'" :back="route('crm.requests.index')">
        <section class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">Received {{ request.received }}<template v-if="request.by_phone"> by phone (voice bot, speech-to-text)</template></p>
            <p class="mt-1 text-lg font-semibold">{{ request.phone }}</p>
            <p v-if="request.email" class="text-gray-600">{{ request.email }}</p>
            <p class="text-gray-600">{{ request.location }}</p>
            <div v-if="tel" class="mt-3 grid grid-cols-2 gap-2">
                <a :href="`tel:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#c8000a] font-bold text-white"><Phone class="size-5" />Call</a>
                <a :href="`sms:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#151515] font-bold text-white"><MessageSquare class="size-5" />Text</a>
            </div>
            <button v-if="tel" type="button" class="mt-2 flex h-12 w-full items-center justify-center gap-1 rounded-lg border border-[#c8000a] font-bold text-[#c8000a]" @click="callFromBusinessLine"><PhoneForwarded class="size-5" />Call from business line</button>
        </section>

        <section v-if="request.voice" class="mt-3 rounded-xl p-4 shadow-sm" :class="request.voice.status === 'overdue' ? 'bg-red-50' : 'bg-white'">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">AI phone assistant</h2>
            <p class="font-semibold">{{ voiceStatus[request.voice.status] }}<template v-if="request.voice.status === 'callback_due' && request.voice.due"> {{ request.voice.due }}</template></p>
            <p class="text-sm text-gray-600">$95 service call fee: {{ request.voice.fee_accepted ? 'agreed on the call' : 'not agreed' }}</p>
            <p v-if="request.voice.followup_notes" class="text-sm text-gray-600">Follow-up call: {{ request.voice.followup_notes }}</p>
            <button v-if="['callback_due', 'overdue', 'new'].includes(request.voice.status)" type="button" class="mt-2 flex h-12 w-full items-center justify-center rounded-lg bg-[#151515] font-bold text-white" @click="calledBack">I called back</button>
        </section>

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">Appliance</h2>
            <p class="font-semibold">{{ request.appliance }}</p>
            <p class="text-sm text-gray-600">{{ [request.brand, request.model].filter(Boolean).join(' · ') }}</p>
            <h2 class="mt-3 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Problem</h2>
            <p class="whitespace-pre-line">{{ request.description }}</p>
            <template v-if="request.photos.length">
                <h2 class="mt-3 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Customer photos</h2>
                <div class="mt-1 grid grid-cols-3 gap-2">
                    <a v-for="photo in request.photos" :key="photo.id" :href="photo.url" target="_blank" class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                        <img :src="photo.url" alt="Customer photo" loading="lazy" class="size-full object-cover">
                    </a>
                </div>
                <p v-if="!request.work_order_id" class="mt-1 text-xs text-gray-500">They move to the job when you create it.</p>
            </template>
        </section>

        <div class="mt-4 grid gap-2">
            <Link v-if="request.work_order_id" :href="route('crm.jobs.show', request.work_order_id)" class="flex h-12 items-center justify-center rounded-lg bg-[#151515] font-bold text-white">
                Open job #{{ request.work_order_id }}
            </Link>
            <Button v-else @click="convert">Create customer + job</Button>
            <Button v-if="!request.work_order_id" variant="secondary" @click="archive">{{ request.archived ? 'Move back to new' : 'Archive (no job)' }}</Button>
        </div>
    </CrmLayout>
</template>
