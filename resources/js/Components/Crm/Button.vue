<script setup>
defineProps({
    variant: { type: String, default: 'primary' },
});
</script>

<template>
    <button
        class="inline-flex h-12 items-center justify-center gap-2 rounded-lg px-5 text-base font-bold disabled:opacity-50"
        :class="{
            'bg-[#c8000a] text-white active:bg-red-800': variant === 'primary',
            'bg-[#151515] text-white active:bg-black': variant === 'dark',
            'border border-gray-300 bg-white text-gray-900 active:bg-gray-100': variant === 'secondary',
        }"
    >
        <slot />
    </button>
</template>
