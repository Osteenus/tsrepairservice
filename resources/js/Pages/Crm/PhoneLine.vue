<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import Button from '@/Components/Crm/Button.vue';
import Field from '@/Components/Crm/Field.vue';

const props = defineProps({
    settings: Object,
    modes: Object,
    days: Number,
    periods: Array,
    rateLimited: Number,
    variants: Array,
});

const form = useForm({ ...props.settings });
const save = () => form.put(route('crm.phone.update'), { preserveScroll: true });
const pct = (value) => (value === null ? '—' : `${value}%`);
const seconds = (value) => (value === null ? '—' : value >= 60 ? `${Math.floor(value / 60)}m ${value % 60}s` : `${value}s`);
</script>

<template>
    <CrmLayout title="Phone line">
        <form class="rounded-xl bg-white p-4 shadow-sm" @submit.prevent="save">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">Who answers (805) 334-8375</h2>
            <div class="mt-2 grid gap-2">
                <label v-for="(label, mode) in modes" :key="mode" class="flex h-12 items-center gap-3 rounded-lg border px-3 font-semibold"
                    :class="form.mode === mode ? 'border-[#c8000a] bg-red-50' : 'border-gray-300'">
                    <input v-model="form.mode" type="radio" :value="mode" class="text-[#c8000a] focus:ring-[#c8000a]">
                    {{ label }}
                </label>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                <template v-if="form.mode === 'ai'">The AI assistant talks to every caller and can transfer to you.</template>
                <template v-else-if="form.mode === 'transfer'">Greeting and recording notice, then the call rings your phone. If you do not answer, the AI assistant takes the request.</template>
                <template v-else>Each call is randomly answered by the AI (A) or transferred to you (B).</template>
            </p>
            <div class="mt-3 grid grid-cols-2 gap-3">
                <Field v-if="form.mode === 'ab'" v-model="form.ai_percent" label="Calls to AI (A), %" type="number" min="0" max="100" :error="form.errors.ai_percent" />
                <Field v-model="form.short_call_seconds" label="Short call, under (sec)" type="number" min="1" max="300" :error="form.errors.short_call_seconds"
                    hint="Counted as “hung up right away”." />
            </div>
            <Button class="mt-3 w-full" :disabled="form.processing || !form.isDirty">Save</Button>
        </form>

        <section class="mt-3">
            <div class="mb-2 flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#c8000a]">Calls, last {{ days }} days</h2>
                <div class="flex gap-1 rounded-lg bg-gray-200 p-1 text-sm font-semibold">
                    <Link v-for="period in periods" :key="period" :href="route('crm.phone.show', { days: period })" preserve-scroll
                        class="rounded-md px-3 py-1.5" :class="period === days && 'bg-white shadow-sm'">{{ period }}d</Link>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <article v-for="variant in variants" :key="variant.label" class="rounded-xl bg-white p-4 shadow-sm">
                    <h3 class="font-bold">{{ variant.label }}</h3>
                    <p class="text-3xl font-bold">{{ variant.calls }} <span class="text-sm font-semibold text-gray-500">calls</span></p>
                    <dl class="mt-2 grid grid-cols-[1fr_auto] gap-x-3 gap-y-1 text-sm">
                        <dt class="text-gray-600">Hung up under {{ settings.short_call_seconds }}s</dt>
                        <dd class="text-right font-semibold">{{ variant.short }} · {{ pct(variant.short_percent) }}</dd>
                        <dt class="text-gray-600">Average length</dt>
                        <dd class="text-right font-semibold">{{ seconds(variant.avg_seconds) }}</dd>
                        <dt class="text-gray-600">Leads saved</dt>
                        <dd class="text-right font-semibold">{{ variant.leads }} · {{ pct(variant.leads_percent) }}</dd>
                        <dt class="text-gray-600">Transfers answered / missed</dt>
                        <dd class="text-right font-semibold">{{ variant.transfer_answered }} / {{ variant.transfer_missed }}</dd>
                        <dt class="text-gray-600">Talked to AI</dt>
                        <dd class="text-right font-semibold">{{ variant.reached_ai }}</dd>
                        <dt class="text-gray-600">Hit the 5-minute limit</dt>
                        <dd class="text-right font-semibold">{{ variant.timed_out }}</dd>
                        <dt class="text-gray-600">AI cost (Claude)</dt>
                        <dd class="text-right font-semibold">${{ variant.ai_cost.toFixed(2) }}</dd>
                    </dl>
                    <p v-if="variant.calls && variant.with_duration < variant.calls" class="mt-2 text-xs text-gray-500">
                        Length known for {{ variant.with_duration }} of {{ variant.calls }} calls (needs the Twilio status callback).
                    </p>
                </article>
            </div>
            <p v-if="rateLimited" class="mt-2 text-sm text-gray-600">{{ rateLimited }} repeat calls were turned away by the 3-per-hour limit.</p>
            <p class="mt-2 text-xs text-gray-500">Twilio call minutes are not included in the cost.</p>
        </section>
    </CrmLayout>
</template>
