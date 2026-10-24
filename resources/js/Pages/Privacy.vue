<script setup>
import Breadcrumbs from '@/Components/Custom/Breadcrumbs.vue';
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineOptions({ layout: Layout });
const page = usePage();
const phone = computed(() => page.props.repair.phone);
const title = 'Privacy Policy | Tech Solutions Repair Service';
const description = 'How Tech Solutions Repair Service collects, uses and protects the information you share when you request appliance repair by phone, text or on our website.';
const canonical = 'https://tsrepairservice.com/privacy';
</script>

<template>
    <Breadcrumbs :items="[{ label: 'Privacy Policy' }]" />
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
        <h1>Privacy Policy</h1>
        <p class="text-sm text-gray-500">Effective October 24, 2026</p>
        <p>Tech Solutions Repair Service, Inc. ("we", "us") repairs home appliances in Moorpark, California and nearby areas. This policy explains what information we collect when you contact us and how we use it.</p>

        <h2>Information we collect</h2>
        <ul>
            <li>Details you give us in a repair request on this website: name, phone number, email address, city or ZIP code, appliance type, brand, model, a description of the problem and any photos you attach.</li>
            <li>Details you give us by phone. Calls to {{ phone }} may be answered by an automated assistant that converts your spoken answers (name, appliance problem and city) to text, together with the phone number you call from. We do not record calls.</li>
            <li>Text messages you send to us and our replies.</li>
            <li>For customers we serve: service address, appliance details, the work performed, estimates, invoices, payments and signatures on those documents.</li>
            <li>Basic technical data our web server logs automatically, such as IP address and browser type, used to keep the site working and secure.</li>
        </ul>

        <h2>How we use it</h2>
        <p>We use your information only to respond to your request and provide service: to call or text you about your request, schedule and confirm appointments, send arrival and service updates, prepare estimates and invoices, honor warranties, and keep the business records required by law. We do not use it for advertising and we do not sell it.</p>

        <h2>Text messages (SMS)</h2>
        <p>If you give us your mobile number in a repair request, by calling us, or by texting us, you agree that we may text you about that request, such as a confirmation, appointment scheduling and technician arrival updates. Message frequency varies. Message and data rates may apply. Reply STOP to stop receiving texts and HELP for help. See our <Link href="/terms">Terms</Link> for the full SMS terms.</p>
        <p><strong>No mobile information will be shared with third parties or affiliates for marketing or promotional purposes.</strong> Text messaging originator opt-in data and consent will not be shared with any third parties, except the service providers that deliver our messages.</p>

        <h2>Who we share it with</h2>
        <p>We share information only with service providers that help us run the business, and only as needed for that purpose: our website hosting provider, our email provider, Twilio (phone calls and text messages), and payment processors when you pay. We may also disclose information when required by law. We do not sell or rent personal information.</p>

        <h2>How long we keep it</h2>
        <p>We keep repair requests and customer records for as long as needed to provide service, honor warranties and meet tax and licensing record-keeping requirements. Requests that do not lead to a job may be deleted earlier.</p>

        <h2>Security</h2>
        <p>Our website uses encrypted connections (HTTPS), and access to customer records is limited to our staff and protected by two-factor sign-in.</p>

        <h2>Your choices</h2>
        <p>You can ask us to show you, correct or delete the personal information we hold about you, except records we must keep by law. Reply STOP to any text to stop messages.</p>

        <h2>Contact us</h2>
        <p>Tech Solutions Repair Service, Inc., Moorpark, CA<br>Phone: <a :href="`tel:${phone}`">{{ phone }}</a><br>Email: <a href="mailto:service@tsrepairservice.com">service@tsrepairservice.com</a></p>
        <p>We may update this policy; the effective date above shows the latest version.</p>
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
