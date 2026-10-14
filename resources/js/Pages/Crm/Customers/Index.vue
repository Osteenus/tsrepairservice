<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Plus, Search } from 'lucide-vue-next';
import CrmLayout from '@/Layouts/CrmLayout.vue';

const props = defineProps({
    customers: Object,
    search: String,
});

const term = ref(props.search);
let timer;

watch(term, value => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(route('crm.customers.index'), value ? { search: value } : {}, { preserveState: true, replace: true });
    }, 300);
});
</script>

<template>
    <CrmLayout title="Customers">
        <template #actions>
            <Link :href="route('crm.customers.create')" class="flex size-11 items-center justify-center rounded-lg hover:bg-white/10" aria-label="New customer">
                <Plus class="size-6" />
            </Link>
        </template>

        <label class="relative mb-3 block">
            <Search class="absolute left-3 top-3.5 size-5 text-gray-400" />
            <input
                v-model="term"
                type="search"
                inputmode="search"
                placeholder="Name or phone"
                class="block h-12 w-full rounded-lg border border-gray-300 pl-10 pr-3 text-base focus:border-[#c8000a] focus:ring-[#c8000a]"
            >
        </label>

        <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm">
            <li v-for="customer in customers.data" :key="customer.id">
                <Link :href="route('crm.customers.show', customer.id)" class="block px-4 py-3 active:bg-gray-50">
                    <p class="font-semibold">{{ customer.name }}</p>
                    <p class="text-sm text-gray-500">{{ [customer.phone, customer.city].filter(Boolean).join(' · ') }}</p>
                </Link>
            </li>
            <li v-if="!customers.data.length" class="px-4 py-6 text-center text-gray-500">No customers found.</li>
        </ul>

        <div v-if="customers.prev_page_url || customers.next_page_url" class="mt-3 flex justify-between">
            <Link v-if="customers.prev_page_url" :href="customers.prev_page_url" class="rounded-lg bg-white px-4 py-3 font-semibold shadow-sm">‹ Previous</Link>
            <span v-else />
            <Link v-if="customers.next_page_url" :href="customers.next_page_url" class="rounded-lg bg-white px-4 py-3 font-semibold shadow-sm">Next ›</Link>
        </div>
    </CrmLayout>
</template>
