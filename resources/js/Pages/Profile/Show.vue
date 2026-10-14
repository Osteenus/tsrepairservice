<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CrmLayout from '@/Layouts/CrmLayout.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const page = usePage();
const needsTwoFactor = computed(() => !page.props.auth.user.two_factor_confirmed_at);
</script>

<template>
    <CrmLayout title="Account & security">
        <div v-if="needsTwoFactor" class="mb-4 rounded-xl border-2 border-[#c8000a] bg-white p-4">
            <p class="font-bold">Two-factor authentication is required.</p>
            <p class="mt-1 text-sm text-gray-700">
                Install an authenticator app (Google Authenticator, Authy, 1Password…), tap “Enable”, scan the QR code,
                enter the 6-digit code to confirm, and store the recovery codes somewhere safe.
            </p>
        </div>

        <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />

        <SectionBorder />

        <UpdateProfileInformationForm :user="$page.props.auth.user" />

        <SectionBorder />

        <UpdatePasswordForm />

        <SectionBorder />

        <LogoutOtherBrowserSessionsForm :sessions="sessions" />
    </CrmLayout>
</template>
