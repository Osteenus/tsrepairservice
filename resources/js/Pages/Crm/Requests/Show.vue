<script setup>
import { Link, router } from '@inertiajs/vue3';
import { MessageSquare, Phone } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Button from '@/Components/Crm/Button.vue';

const props = defineProps({
    request: Object,
});

const tel = (props.request.phone || '').replace(/[^\d+]/g, '');
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
            <p class="text-xs text-gray-500">Received {{ request.received }}</p>
            <p class="mt-1 text-lg font-semibold">{{ request.phone }}</p>
            <p v-if="request.email" class="text-gray-600">{{ request.email }}</p>
            <p class="text-gray-600">{{ request.location }}</p>
            <div v-if="tel" class="mt-3 grid grid-cols-2 gap-2">
                <a :href="`tel:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#c8000a] font-bold text-white"><Phone class="size-5" />Call</a>
                <a :href="`sms:${tel}`" class="flex h-12 items-center justify-center gap-1 rounded-lg bg-[#151515] font-bold text-white"><MessageSquare class="size-5" />Text</a>
            </div>
        </section>

        <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">Appliance</h2>
            <p class="font-semibold">{{ request.appliance }}</p>
            <p class="text-sm text-gray-600">{{ [request.brand, request.model].filter(Boolean).join(' · ') }}</p>
            <h2 class="mt-3 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Problem</h2>
            <p class="whitespace-pre-line">{{ request.description }}</p>
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
