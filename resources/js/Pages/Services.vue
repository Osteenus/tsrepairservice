<script>
import Layout from '../Layouts/Layout.vue'
import OurServicesSection from '@/Components/Custom/OurServicesSection.vue';

export default {
    // Using a render function...
    // layout: (h, page) => h(Layout, [page]),

    // Using shorthand syntax...
    layout: Layout,
}
</script>

<script setup>
defineProps({ user: Object })
</script>

<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/service-maintenance-worker-repairing.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24">Services</h1>
    </div>

    <OurServicesSection>

    </OurServicesSection>

</template>
