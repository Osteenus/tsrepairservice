<script setup>
import { ref, watch } from 'vue';
import Field from '@/Components/Crm/Field.vue';

const props = defineProps({
    form: Object,
    types: Array,
    brands: Array,
});

const OTHER = '__other__';
const isListed = props.types.includes(props.form.type);
const choice = ref(props.form.type === '' ? '' : (isListed ? props.form.type : OTHER));
const otherType = ref(isListed ? '' : props.form.type);

watch([choice, otherType], () => {
    props.form.type = choice.value === OTHER ? otherType.value : choice.value;
});
</script>

<template>
    <div class="grid gap-3">
        <Field v-model="choice" as="select" label="Type" :error="choice !== OTHER ? form.errors.type : null" required>
            <option value="" disabled>Choose…</option>
            <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
            <option :value="OTHER">Other…</option>
        </Field>
        <Field v-if="choice === OTHER" v-model="otherType" label="Other type" format="words" placeholder="e.g. Washer/dryer combo" :error="form.errors.type" required />
        <Field v-model="form.brand" label="Brand" format="words" list="crm-appliance-brands" autocomplete="off" :error="form.errors.brand" />
        <datalist id="crm-appliance-brands">
            <option v-for="brand in brands" :key="brand" :value="brand" />
        </datalist>
        <Field v-model="form.model" label="Model" format="upper" :error="form.errors.model" />
        <Field v-model="form.serial" label="Serial" format="upper" :error="form.errors.serial" />
        <Field v-model="form.notes" as="textarea" label="Notes" :error="form.errors.notes" />
    </div>
</template>
