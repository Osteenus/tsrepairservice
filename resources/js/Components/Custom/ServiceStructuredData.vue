<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
const page = usePage();
// These pages predate the service schemas already present on newer service pages.
const components = ['Services/RefrigeratorRepairService', 'Services/WasherRepairService', 'Services/DryerRepairService', 'Services/ElectronicRepairService'];
const service = computed(() => components.includes(page.component)
    ? page.props.repair.services.find(item => item.url === page.url.split('?')[0]) : null);
const json = computed(() => service.value ? JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Service',
    '@id': `https://tsrepairservice.com${service.value.url}#service`,
    name: service.value.name,
    serviceType: service.value.name,
    url: `https://tsrepairservice.com${service.value.url}`,
    provider: { '@type': 'Organization', '@id': 'https://tsrepairservice.com/#organization', name: 'TS Repair Service', telephone: page.props.repair.phone },
    areaServed: page.props.repair.serviceAreas.map(name => ({ '@type': 'Place', name })),
}).replace(/</g, '\\u003c') : '');
</script>

<template>
    <Head v-if="service">
        <component :is="'script'" type="application/ld+json" head-key="service-schema">{{ json }}</component>
    </Head>
</template>
