<template>
    <header class="flex flex-row">
        <a href="/" class="block w-full w-64">
            <img src="/img/logo.png" alt="Logo" class="">
        </a>
        <nav class="flex flex-auto justify-center w-64 pb-3">
            <NavLink href="/" active="true" class="px-8">Home</NavLink>
            <NavLink href="/about" class="px-8">About Us</NavLink>
            <NavLink href="/services" class="px-8">Services</NavLink>
            <NavLink href="/contact" class="px-8">Contact</NavLink>
        </nav>
        <div class="content-center px-12">
            <DangerButton>
                Request service
            </DangerButton>
        </div>
    </header>

</template>

<script setup>
import { ref } from 'vue'
import NavLink from '../NavLink.vue'
import DangerButton from '../DangerButton.vue'


</script>

<style scoped>

</style>
