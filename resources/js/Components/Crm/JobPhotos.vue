<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Camera, X } from 'lucide-vue-next';
import { shrinkImages } from '@/forms/images.js';

const props = defineProps({
    jobId: Number,
    photos: Array,
    kinds: Object,
});

const input = ref(null);
const viewing = ref(null);
const form = useForm({ photos: [], kind: 'before', caption: '' });

const preparing = ref(false);

const upload = async event => {
    if (!event.target.files.length) return;
    preparing.value = true;
    form.photos = await shrinkImages(event.target.files);
    preparing.value = false;
    form.post(route('crm.photos.store', props.jobId), {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => {
            input.value.value = '';
            form.reset('photos', 'caption');
        },
    });
};

const remove = photo => {
    if (!confirm('Remove this photo?')) return;
    viewing.value = null;
    router.delete(route('crm.photos.destroy', photo.id), { preserveScroll: true });
};
</script>

<template>
    <section class="mt-3 rounded-xl bg-white p-4 shadow-sm">
        <h2 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#c8000a]">Photos</h2>

        <div v-if="photos.length" class="mb-3 grid grid-cols-3 gap-2">
            <button v-for="photo in photos" :key="photo.id" type="button" class="relative aspect-square overflow-hidden rounded-lg bg-gray-100" @click="viewing = photo">
                <img :src="photo.url" :alt="photo.caption || photo.kind" loading="lazy" class="size-full object-cover">
                <span v-if="photo.from_customer" class="absolute left-1 top-1 rounded bg-[#c8000a] px-1.5 py-0.5 text-[10px] font-bold text-white">Customer</span>
                <span class="absolute inset-x-0 bottom-0 bg-black/60 px-1 py-0.5 text-[11px] font-semibold text-white">{{ photo.kind }}</span>
            </button>
        </div>

        <div class="grid grid-cols-4 gap-1 rounded-lg bg-gray-100 p-1 text-center text-xs font-semibold">
            <button v-for="(label, key) in kinds" :key="key" type="button" class="rounded-md py-2.5" :class="form.kind === key ? 'bg-white shadow-sm' : 'text-gray-500'" @click="form.kind = key">{{ label }}</button>
        </div>
        <input v-model="form.caption" type="text" placeholder="Caption (optional)" class="mt-2 block h-12 w-full rounded-lg border border-gray-300 px-3 text-base">
        <label class="mt-2 flex h-12 cursor-pointer items-center justify-center gap-2 rounded-lg bg-[#151515] font-bold text-white" :class="(form.processing || preparing) && 'opacity-50'">
            <Camera class="size-5" />
            {{ preparing ? 'Preparing photos…' : form.processing ? `Uploading… ${form.progress?.percentage ?? 0}%` : 'Take or add photos' }}
            <input ref="input" type="file" accept="image/*" multiple class="hidden" :disabled="form.processing || preparing" @change="upload">
        </label>
        <p v-for="(message, key) in form.errors" :key="key" class="mt-1 text-sm text-red-600">{{ message }}</p>

        <div v-if="viewing" class="fixed inset-0 z-50 flex flex-col bg-black" @click.self="viewing = null">
            <div class="flex items-center justify-between p-2 text-white">
                <span class="px-2 text-sm">{{ viewing.from_customer ? 'From customer' : viewing.kind }}<template v-if="viewing.caption"> · {{ viewing.caption }}</template></span>
                <button type="button" class="flex size-11 items-center justify-center" aria-label="Close" @click="viewing = null"><X class="size-6" /></button>
            </div>
            <img :src="viewing.url" alt="" class="min-h-0 flex-1 object-contain">
            <button type="button" class="m-3 h-12 rounded-lg border border-white/40 font-semibold text-white" @click="remove(viewing)">Remove photo</button>
        </div>
    </section>
</template>
