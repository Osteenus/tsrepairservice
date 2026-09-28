<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const registration = computed(() => page.props.repair.registration);
</script>

<template>
  <p v-if="registration" class="text-sm leading-6" data-business-registration>License #{{ registration.number }}</p>
</template>
