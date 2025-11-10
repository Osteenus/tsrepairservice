<script>
    import Layout from '../Layouts/Layout.vue'
    import MainSection from '../Components/Custom/MainSection.vue'
    import OurServicesSection from '../Components/Custom/OurServicesSection.vue';
    import BrandsSection from '../Components/Custom/BrandsSection.vue';
    import WhyChooseUsSection from '../Components/Custom/WhyChooseUsSection.vue';
    import ServiceAreaSection from '../Components/Custom/ServiceAreaSection.vue';
    import CustomerReviewsSection from '../Components/Custom/CustomerReviewsSection.vue';

    export default {
        components: {ServiceAreaSection, WhyChooseUsSection, BrandsSection, OurServicesSection, MainSection},
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
    <MainSection>

    </MainSection>
    <OurServicesSection>

    </OurServicesSection>
    <BrandsSection>

    </BrandsSection>
    <WhyChooseUsSection>

    </WhyChooseUsSection>
    <CustomerReviewsSection>

    </CustomerReviewsSection>
    <ServiceAreaSection>

    </ServiceAreaSection>

</template>
