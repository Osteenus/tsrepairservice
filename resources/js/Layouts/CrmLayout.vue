<script setup>
import { computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { House, Inbox, Users, Wrench, ShieldCheck, LogOut } from 'lucide-vue-next';
import Banner from '@/Components/Banner.vue';

defineProps({
    title: { type: String, required: true },
    back: { type: String, default: null },
});

const page = usePage();
const path = computed(() => page.url.split('?')[0]);

const tabs = [
    { label: 'Home', href: '/crm', icon: House, exact: true },
    { label: 'Requests', href: '/crm/requests', icon: Inbox },
    { label: 'Jobs', href: '/crm/jobs', icon: Wrench },
    { label: 'Customers', href: '/crm/customers', icon: Users },
];

const active = tab => tab.exact ? path.value === tab.href : path.value.startsWith(tab.href);
const logout = () => router.post(route('logout'));
</script>

<template>
    <Head :title="title">
        <meta name="robots" content="noindex, nofollow">
    </Head>

    <div class="min-h-screen bg-gray-100 pb-24 text-gray-900">
        <Banner />

        <header class="sticky top-0 z-20 border-b-4 border-[#c8000a] bg-[#151515] text-white">
            <div class="mx-auto flex h-14 max-w-3xl items-center gap-2 px-3">
                <Link v-if="back" :href="back" class="-ml-1 flex size-11 items-center justify-center rounded-lg text-2xl hover:bg-white/10" aria-label="Back">‹</Link>
                <h1 class="min-w-0 flex-1 truncate text-lg font-bold">{{ title }}</h1>
                <slot name="actions" />
                <Link :href="route('profile.show')" class="flex size-11 items-center justify-center rounded-lg hover:bg-white/10" aria-label="Account and security">
                    <ShieldCheck class="size-5" />
                </Link>
                <button type="button" class="flex size-11 items-center justify-center rounded-lg hover:bg-white/10" aria-label="Log out" @click="logout">
                    <LogOut class="size-5" />
                </button>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-3 py-4">
            <slot />
        </main>

        <nav class="fixed inset-x-0 bottom-0 z-20 border-t border-gray-200 bg-white pb-[env(safe-area-inset-bottom)]">
            <div class="mx-auto grid max-w-3xl grid-cols-4">
                <Link
                    v-for="tab in tabs"
                    :key="tab.href"
                    :href="tab.href"
                    class="flex h-16 flex-col items-center justify-center gap-1 text-xs font-semibold"
                    :class="active(tab) ? 'text-[#c8000a]' : 'text-gray-500'"
                >
                    <component :is="tab.icon" class="size-6" />
                    {{ tab.label }}
                </Link>
            </div>
        </nav>
    </div>
</template>
