<script setup>
defineOptions({ inheritAttrs: false });

const model = defineModel();

defineProps({
    label: { type: String, required: true },
    error: { type: String, default: null },
    as: { type: String, default: 'input' },
    hint: { type: String, default: null },
});
</script>

<template>
    <label class="block">
        <span class="mb-1 block text-sm font-semibold text-gray-700">{{ label }}</span>
        <textarea
            v-if="as === 'textarea'"
            v-model="model"
            v-bind="$attrs"
            rows="3"
            class="block w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:border-[#c8000a] focus:ring-[#c8000a]"
        />
        <select
            v-else-if="as === 'select'"
            v-model="model"
            v-bind="$attrs"
            class="block h-12 w-full rounded-lg border border-gray-300 bg-white px-3 text-base focus:border-[#c8000a] focus:ring-[#c8000a]"
        >
            <slot />
        </select>
        <input
            v-else
            v-model="model"
            v-bind="$attrs"
            class="block h-12 w-full rounded-lg border border-gray-300 px-3 text-base focus:border-[#c8000a] focus:ring-[#c8000a]"
        >
        <span v-if="hint" class="mt-1 block text-xs text-gray-500">{{ hint }}</span>
        <span v-if="error" class="mt-1 block text-sm text-red-600">{{ error }}</span>
    </label>
</template>
