<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const emit = defineEmits(['update:modelValue']);
defineProps({ modelValue: String });

const canvas = ref(null);
let ctx;
let drawing = false;
let dirty = false;

const resize = () => {
    const el = canvas.value;
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    el.width = el.offsetWidth * ratio;
    el.height = el.offsetHeight * ratio;
    ctx = el.getContext('2d');
    ctx.scale(ratio, ratio);
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#0b1f5c';
    clear();
};

const point = event => {
    const rect = canvas.value.getBoundingClientRect();
    return [event.clientX - rect.left, event.clientY - rect.top];
};

const start = event => {
    event.preventDefault();
    canvas.value.setPointerCapture(event.pointerId);
    drawing = true;
    const [x, y] = point(event);
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x + 0.1, y + 0.1);
    ctx.stroke();
};

const move = event => {
    if (!drawing) return;
    event.preventDefault();
    const [x, y] = point(event);
    ctx.lineTo(x, y);
    ctx.stroke();
    dirty = true;
};

const end = () => {
    if (!drawing) return;
    drawing = false;
    if (dirty) emit('update:modelValue', canvas.value.toDataURL('image/png'));
};

const clear = () => {
    ctx.clearRect(0, 0, canvas.value.width, canvas.value.height);
    dirty = false;
    emit('update:modelValue', null);
};

onMounted(() => {
    resize();
    window.addEventListener('resize', resize);
});
onBeforeUnmount(() => window.removeEventListener('resize', resize));
</script>

<template>
    <div>
        <div class="relative rounded-lg border-2 border-dashed border-gray-400 bg-white">
            <canvas
                ref="canvas"
                class="block h-44 w-full touch-none"
                @pointerdown="start"
                @pointermove="move"
                @pointerup="end"
                @pointercancel="end"
                @pointerleave="end"
            />
            <span class="pointer-events-none absolute bottom-8 left-4 right-4 border-b border-gray-300" />
            <span class="pointer-events-none absolute bottom-2 left-4 text-xs text-gray-400">Sign above the line</span>
        </div>
        <button type="button" class="mt-1 h-11 px-2 text-sm font-semibold text-gray-600 underline" @click="clear">Clear signature</button>
    </div>
</template>
