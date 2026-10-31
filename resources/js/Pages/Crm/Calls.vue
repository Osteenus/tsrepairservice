<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import CallRow from '@/Components/Crm/CallRow.vue';
import PhoneTabs from '@/Components/Crm/PhoneTabs.vue';

const props = defineProps({
    period: String,
    periods: Object,
    search: String,
    calls: Array,
    more: Boolean,
    frequent: Array,
    repeatCalls: Number,
    blocked: Array,
});

const term = ref(props.search);
let timer;

const query = (changes = {}) => {
    const params = { period: props.period, search: term.value, ...changes };
    if (params.period === '7') delete params.period;
    if (!params.search) delete params.search;
    return params;
};

watch(term, () => {
    clearTimeout(timer);
    timer = setTimeout(() => router.get(route('crm.calls.index'), query(), { preserveState: true, replace: true }), 300);
});

const block = caller => {
    if (confirm(`Block ${caller.display_number}? Calls from this number will be rejected.`)) {
        router.post(route('crm.blocked-numbers.store'), { phone: caller.number }, { preserveScroll: true });
    }
};
const unblock = number => {
    if (confirm(`Unblock ${number.display_number}?`)) router.delete(route('crm.blocked-numbers.destroy', number.id), { preserveScroll: true });
};
</script>

<template>
    <CrmLayout title="Calls">
        <PhoneTabs current="calls" />

        <section v-if="frequent.length" class="mb-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Frequent callers, last 7 days</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <li v-for="caller in frequent" :key="caller.number" class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="flex flex-wrap items-center gap-2">
                            <Link v-if="caller.customer" :href="route('crm.customers.show', caller.customer.id)" class="font-semibold text-[#c8000a]">{{ caller.customer.name }}</Link>
                            <span :class="caller.customer ? 'text-sm text-gray-700' : 'font-semibold'">{{ caller.display_number }}</span>
                            <span v-if="caller.repeat" class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">Repeat caller</span>
                        </p>
                        <p class="text-sm text-gray-500">{{ caller.calls }} calls · last {{ caller.last_call }}</p>
                    </div>
                    <span v-if="caller.blocked" class="shrink-0 text-sm font-semibold text-gray-500">Blocked</span>
                    <button v-else type="button" class="h-11 shrink-0 rounded-lg border border-gray-300 px-3 text-sm font-bold active:bg-gray-100" @click="block(caller)">Block</button>
                </li>
            </ul>
        </section>

        <div class="mb-2 grid grid-cols-3 gap-1 rounded-lg bg-gray-200 p-1 font-semibold">
            <Link v-for="(label, key) in periods" :key="key" :href="route('crm.calls.index', query({ period: key }))" preserve-scroll
                class="flex h-11 items-center justify-center rounded-md" :class="key === period ? 'bg-white shadow-sm' : 'text-gray-600'">{{ label }}</Link>
        </div>

        <label class="relative mb-3 block">
            <Search class="absolute left-3 top-3.5 size-5 text-gray-400" />
            <input v-model="term" type="search" inputmode="tel" placeholder="Search by number"
                class="block h-12 w-full rounded-lg border border-gray-300 pl-10 pr-3 text-base focus:border-[#c8000a] focus:ring-[#c8000a]">
        </label>

        <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
            <CallRow v-for="call in calls" :key="call.id" :call="call" />
            <li v-if="!calls.length" class="p-4 text-center text-gray-500">No calls.</li>
        </ul>
        <p v-if="more" class="mt-2 text-center text-sm text-gray-500">Showing the latest {{ calls.length }} calls. Search by number to find older ones.</p>

        <section v-if="blocked.length" class="mt-4">
            <h2 class="mb-2 text-sm font-bold uppercase tracking-wider text-[#c8000a]">Blocked numbers</h2>
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
                <li v-for="number in blocked" :key="number.id" class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold">{{ number.display_number }}</p>
                        <p class="text-sm text-gray-500">Since {{ number.since }}<template v-if="number.reason"> · {{ number.reason }}</template></p>
                    </div>
                    <button type="button" class="h-11 shrink-0 rounded-lg border border-gray-300 px-3 text-sm font-bold active:bg-gray-100" @click="unblock(number)">Unblock</button>
                </li>
            </ul>
        </section>
    </CrmLayout>
</template>
