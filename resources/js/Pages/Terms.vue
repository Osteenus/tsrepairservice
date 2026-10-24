<script setup>
import Breadcrumbs from '@/Components/Custom/Breadcrumbs.vue';
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineOptions({ layout: Layout });
const page = usePage();
const phone = computed(() => page.props.repair.phone);
const title = 'Terms of Service | Tech Solutions Repair Service';
const description = 'Terms for repair requests and text messages from Tech Solutions Repair Service in Moorpark, CA, including message frequency, STOP and HELP.';
const canonical = 'https://tsrepairservice.com/terms';
</script>

<template>
    <Breadcrumbs :items="[{ label: 'Terms of Service' }]" />
    <Head>
        <title>{{ title }}</title>
        <meta name="description" :content="description" head-key="description">
        <link rel="canonical" :href="canonical" head-key="canonical">
        <meta property="og:title" :content="title" head-key="og:title">
        <meta property="og:description" :content="description" head-key="og:description">
        <meta property="og:url" :content="canonical" head-key="og:url">
        <meta property="og:type" content="website" head-key="og:type">
    </Head>

    <section class="legal mx-auto max-w-3xl px-6 py-10 md:py-14">
        <h1>Terms of Service</h1>
        <p class="text-sm text-gray-500">Effective October 24, 2026</p>
        <p>These terms apply when you request service from Tech Solutions Repair Service, Inc. ("we", "us") on this website, by phone or by text message.</p>

        <h2>Repair requests</h2>
        <p>A request sent on this website or taken by our automated phone assistant is a request for service, not a confirmed appointment. We contact you to confirm availability, the visit time and the diagnostic fee. Repair work starts only after you approve an estimate.</p>

        <h2>Text messages (SMS)</h2>
        <ul>
            <li><strong>Program:</strong> Tech Solutions Repair Service customer care texts.</li>
            <li><strong>What you receive:</strong> messages about your repair request, such as a confirmation, appointment scheduling, technician arrival updates, and estimates or invoices. No marketing.</li>
            <li><strong>How you opt in:</strong> by giving us your mobile number in a repair request on our website, by calling us at {{ phone }}, or by texting us.</li>
            <li><strong>Frequency:</strong> message frequency varies, usually a few messages per request.</li>
            <li><strong>Cost:</strong> message and data rates may apply.</li>
            <li><strong>Opt out:</strong> reply <strong>STOP</strong> to any message to stop receiving texts. We will send one message confirming that you have been unsubscribed.</li>
            <li><strong>Help:</strong> reply <strong>HELP</strong> to any message, call <a :href="`tel:${phone}`">{{ phone }}</a> or email <a href="mailto:service@tsrepairservice.com">service@tsrepairservice.com</a>.</li>
            <li>Carriers are not liable for delayed or undelivered messages.</li>
        </ul>
        <p>See our <Link href="/privacy">Privacy Policy</Link> for how we handle your information. No mobile information will be shared with third parties or affiliates for marketing or promotional purposes.</p>

        <h2>Contact</h2>
        <p>Tech Solutions Repair Service, Inc., Moorpark, CA · <a :href="`tel:${phone}`">{{ phone }}</a> · <a href="mailto:service@tsrepairservice.com">service@tsrepairservice.com</a></p>
    </section>
</template>

<style scoped>
.legal { line-height: 1.75; color: #1f2937; }
.legal h1 { font-size: 2rem; font-weight: 600; line-height: 1.2; }
.legal h2 { margin-top: 2rem; font-size: 1.25rem; font-weight: 600; }
.legal p, .legal ul { margin-top: 0.75rem; }
.legal ul { list-style: disc; padding-left: 1.5rem; }
.legal li + li { margin-top: 0.5rem; }
.legal a { color: #b91c1c; text-decoration: underline; }
</style>
