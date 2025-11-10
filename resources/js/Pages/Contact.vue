<script>
import Layout from '../Layouts/Layout.vue'
import ScheduleFormSection from '../Components/Custom/ScheduleFormSection.vue';

export default {
    components: {ScheduleFormSection},
    layout: Layout,
}
</script>

<script setup>
defineProps({ user: Object })
</script>

<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/service-maintenance-worker-repairing.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24">Contact Us</h1>
    </div>
    
    <ScheduleFormSection>

    </ScheduleFormSection>

</template>
