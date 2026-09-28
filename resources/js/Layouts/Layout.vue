<template>
    <ServiceStructuredData />
    <main>
        <div class="sticky top-0 z-50 bg-blue-900 hover:bg-blue-800 transition duration-300 cursor-pointer">
            <a :href="`tel:${contactNumber}`" class="block text-sm text-white text-center px-4 py-3 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">Need appliance repair? Call {{ contactNumber }}</a>
        </div>
        <header ref="header" class="flex flex-col lg:flex-row lg:items-center min-h-18" @keydown.esc="closeWithEscape">
            <div class="flex items-center justify-between lg:shrink-0">
                <a href="/" class="w-56 max-w-[calc(100%-6rem)] lg:max-w-none lg:w-52 xl:w-64">
                    <img src="/storage/img/logo.png" alt="Tech Solutions Repair Service home">
                </a>
                <div class="lg:hidden">
                    <button ref="menuToggle" type="button" @click="toggleMenu"
                        :aria-expanded="isMenuOpen" aria-controls="header-navigation"
                        :aria-label="isMenuOpen ? 'Close navigation menu' : 'Open navigation menu'"
                        :class="{ active: isMenuOpen }" class="hamburger-button mx-6">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
            <nav id="header-navigation" aria-label="Main navigation"
                :inert="isMobile && !isMenuOpen" :aria-hidden="isMobile && !isMenuOpen"
                class="header-navigation lg:flex-1" :class="{ 'header-navigation-open': isMenuOpen }">
                <div class="header-navigation-content">
                    <ul class="flex flex-col lg:flex-row lg:justify-center">
                        <li class="content-center">
                            <Link href="/" @click="closeMenus" :class="{ 'active-link-style': $page.url === '/' }"
                                class="flex nav-link px-8 py-6 text-2xl lg:px-4 lg:text-xl border-b-2 border-gray-200 lg:border-none">
                                Home
                            </Link>
                        </li>
                        <li class="content-center">
                            <Link href="/about" @click="closeMenus" :class="{ 'active-link-style': $page.url === '/about' }"
                                class="flex nav-link px-8 py-6 text-2xl lg:px-4 lg:text-xl border-b-2 border-gray-200 lg:border-none">
                                About
                            </Link>
                        </li>
                        <li ref="servicesMenu" class="relative content-center"
                            @pointerenter="openDropdownOnHover" @pointerleave="closeDropdownOnLeave"
                            @focusout="closeDropdownOnFocusOut">
                            <button ref="servicesToggle" type="button" @click="toggleDropdown"
                                aria-controls="services-dropdown" :aria-expanded="dropdownIsVisible"
                                :class="{ 'active-link-style': $page.url === '/services' || $page.url.startsWith('/services/') || $page.url === '/washer-repair-moorpark' || $page.url === '/oven-stove-repair-moorpark' || $page.url === '/dishwasher-repair-moorpark' || $page.url === '/microwave-repair-moorpark' || $page.url === '/range-hood-repair-moorpark' || $page.url === '/trash-compactor-repair-moorpark' }"
                                class="services-toggle nav-link flex items-center justify-between gap-2 w-full px-8 py-6 text-left text-2xl lg:px-4 lg:text-xl border-b-2 border-gray-200 lg:border-none cursor-pointer">
                                <span>Services</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    :class="{ 'rotate-180': dropdownIsVisible }" class="size-6 transition-transform duration-300" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <Transition name="header-dropdown">
                                <div id="services-dropdown" v-show="dropdownIsVisible" :inert="!dropdownIsVisible"
                                    class="header-dropdown-wrapper lg:absolute lg:top-full lg:left-0 z-50 w-full lg:w-64 bg-white shadow-md">
                                    <ul class="header-dropdown-content">
                                        <li>
                                            <Link href="/services" @click="closeMenus"
                                                class="flex px-12 py-4 lg:px-4 lg:py-3 border-b border-gray-300 hover:bg-gray-100 focus-visible:bg-gray-100">
                                                All Services
                                            </Link>
                                        </li>
                                        <li v-for="service in services" :key="service.id">
                                            <Link @click="closeMenus" :href="service.url"
                                                class="flex px-12 py-4 lg:px-4 lg:py-3 border-b border-gray-300 hover:bg-gray-100 focus-visible:bg-gray-100">
                                                {{ service.name }}
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </Transition>
                        </li>
                        <li class="content-center">
                            <Link href="/contact" @click="closeMenus" :class="{ 'active-link-style': $page.url.split('?')[0] === '/contact' }"
                                class="flex nav-link px-8 py-6 text-2xl lg:px-4 lg:text-xl border-b-2 border-gray-200 lg:border-none">
                                Contact
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="hidden lg:block shrink-0 px-4 xl:px-12">
                <a :href="`tel:${contactNumber}`"
                    class="inline-flex px-4 py-2 text-xl font-bold text-red-500 transition duration-300 border-2 rounded-xl border-red-500 hover:border-red-600 hover:text-red-600 whitespace-nowrap">
                    {{ contactNumber }}
                </a>
            </div>
        </header>
        <article>
            <slot/>
        </article>
        <footer class="site-footer">
            <div class="footer-grid bg-gray-700">
                <div class="footer-business">
                    <img :src="'/storage/img/components/logo-footer.png'" alt="TS Repair Service" class="pb-4">
                    <p class="pb-4 text-sm text-white">Locally owned and family operated appliance repair service when you need it most in Moorpark, CA</p>
                    <div class="">
                        <a :href="`tel:${contactNumber}`" class="inline-block text-white text-xl py-3 focus-visible:outline-2 focus-visible:outline-white">{{ contactNumber }}</a>
                    </div>
                </div>
                <div class="footer-links">
                    <span class="text-sm text-white font-bold">Our Services</span>
                    <ul class="pt-3 text-sm text-white">
                        <li v-for="service in services" :key="service.id" class="py-2"><a :href="service.url">{{ service.name }}</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <span class="text-sm text-white font-bold">Quick Links</span>
                    <ul class="pt-3 text-sm text-white">
                        <li class="py-2"><a href="/about">About Us</a></li>
                        <li class="py-2"><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-meta">
                    <BusinessRegistration class="text-white mb-3" />
                    <span class="text-white text-md md:text-sm ">© {{ new Date().getFullYear() }} Tech Solutions Repair Service Inc.</span>
                </div>
            </div>
        </footer>
    </main>
</template>

<script>
import ServiceStructuredData from '@/Components/Custom/ServiceStructuredData.vue';
import BusinessRegistration from '@/Components/Custom/BusinessRegistration.vue';
import { Link } from '@inertiajs/vue3'
import { Transition } from 'vue';
import Aos from 'aos';

export default {
    components: { BusinessRegistration, ServiceStructuredData },
    mounted() {
        this.handleResize();
        window.addEventListener('resize', this.handleResize);
        document.addEventListener('pointerdown', this.handleOutsidePointer);
        Aos.init
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize);
        document.removeEventListener('pointerdown', this.handleOutsidePointer);
    },
    watch: {
        '$page.url'() {
            this.closeMenus();
        },
    },
    data() {
        return {    
            services: this.$page.props.repair.services,
            faqRefrigerator: [
                {
                    question: 'Why is my refrigerator not cooling?',
                    answer: 'This can be caused by several issues such as a faulty compressor, evaporator fan, dirty coils, or a control board problem. A proper diagnosis helps identify the exact cause.'
                },
                {
                    question: 'Why is my refrigerator leaking water?',
                    answer: 'Common reasons include a clogged or frozen defrost drain, damaged water line, or door seal issues.'
                },
                {
                    question: 'Is it worth repairing an old refrigerator?',
                    answer: 'In many cases, yes. It depends on the problem, age, and condition of the unit. We provide honest recommendations so you can decide.'
                },
                 {
                    question: 'Do you repair ice makers?',
                    answer: 'Yes, we diagnose and repair ice maker issues including water supply problems and mechanical failures.'
                },
                 {
                    question: 'Do you service commercial refrigerators?',
                    answer: 'Yes, we work on selected commercial refrigeration equipment. Contact us to confirm your unit.'
                },
                 {
                    question: 'What brands do you repair?',
                    answer: 'We service most major refrigerator brands. If you\'re unsure, just ask.'
                },
                {
                    question: 'How quickly can I schedule service?',
                    answer: 'We aim to provide fast scheduling, often with flexible appointment availability.'
                },
                {
                    question: 'Do you repair freezers as well?',
                    answer: 'Yes, we repair both refrigerators and standalone freezers.Yes, we repair both refrigerators and standalone freezers.'
                },

            ],
            whyChooseUsItems: [
                {
                    id: 1,
                    title: 'Fast and Convenient Scheduling',
                    description: 'We respect your time and aim to schedule service as quickly as possible.',
                    iconSvgPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />'
                },
                {
                    id: 2,
                    title: 'Clear Communication',
                    description: 'You receive straightforward explanations and recommendations without confusion.',
                    iconSvgPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />'
                },
                {
                    id: 3,
                    title: 'Careful Diagnostics',
                    description: 'We focus on identifying the real cause of the problem, not just temporary fixes.',
                    iconSvgPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />'
                },
                {
                    id: 4,
                    title: 'Local Family-Owned Service',
                    description: 'We are a local family-owned business, committed to serving our community with care and integrity.',
                    iconSvgPath: '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />'
                },
            ],
            dropdownIsVisible: false,
            isMenuOpen: false,
            isMobile: false,
            contactNumber: this.$page.props.repair.phone,
            whatWeRepair: {
                refrigerators: [
                    {
                        name: 'French Door Refrigerators',
                        url: '/services/refrigerator-repair-moorpark/french-door-refrigerators',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="22" y="8" width="66" height="94" fill="url(#paint0_linear_425_125)" stroke="#C90000" stroke-width="4" /> <path d="M24 69H90" stroke="#C90000" stroke-width="4" /> <path d="M28 76L83 76" stroke="#C90000" stroke-width="4" /> <path d="M55 70L55 8" stroke="#C90000" stroke-width="4" /> <path d="M62 63V25" stroke="#C90000" stroke-width="4" /> <path d="M48 63V25" stroke="#C90000" stroke-width="4" /> <path d="M74 15L83 15" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_125" x1="90" y1="55" x2="20" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFB1B1" /> <stop offset="0.5" stop-color="#FFD0D0" stop-opacity="0.48" /> <stop offset="1" stop-color="#FFB6B6" /> </linearGradient> </defs> </svg>',
                    },
                    {
                        name: 'Side-by-Side Refrigerators',
                        url: '/services/refrigerator-repair-moorpark/side-by-side-refrigerators',
                        svg: ' <svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="22" y="8" width="66" height="94" fill="url(#paint0_linear_425_135)" stroke="#C90000" stroke-width="4" /> <path d="M50 101L50 8" stroke="#C90000" stroke-width="4" /> <path d="M57 79.0093V25" stroke="#C90000" stroke-width="4" /> <path d="M43 79L43 25" stroke="#C90000" stroke-width="4" /> <path d="M73 16L82 16" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_135" x1="90" y1="55" x2="20" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFAAAA" /> <stop offset="0.5625" stop-color="#FFE6E6" /> <stop offset="1" stop-color="#FFA1A1" /> </linearGradient> </defs> </svg>'
                    },
                    {
                        name: 'Top Freezer Refrigerators',
                        url: '/services/refrigerator-repair-moorpark/top-freezer-refrigerators',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="27" y="8" width="56" height="94" fill="url(#paint0_linear_425_161)" stroke="#C90000" stroke-width="4" /> <path d="M28 40H82.0093" stroke="#C90000" stroke-width="4" /> <path d="M68 16L77 16" stroke="#C90000" stroke-width="4" /> <path d="M34 35V16" stroke="#C90000" stroke-width="4" /> <path d="M34 84L34 45" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_161" x1="85" y1="55" x2="25" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFA2A2" /> <stop offset="0.5" stop-color="#FFE7E7" /> <stop offset="1" stop-color="#FFCDCD" /> </linearGradient> </defs> </svg>'
                    },
                    {
                        name: 'Built-In Refrigerators',
                        url: '/services/refrigerator-repair-moorpark/built-in-refrigerators',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="22" y="8" width="66" height="94" fill="url(#paint0_linear_425_169)" stroke="#C90000" stroke-width="4" /> <path d="M50 101L50 23" stroke="#C90000" stroke-width="4" /> <path d="M21 22H90" stroke="#C90000" stroke-width="4" /> <path d="M57 84.0093V30" stroke="#C90000" stroke-width="4" /> <path d="M43 84L43 30" stroke="#C90000" stroke-width="4" /> <path d="M75 14L84 14" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_169" x1="90" y1="55" x2="20" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFB3B3" /> <stop offset="0.572115" stop-color="#FFE2E2" /> <stop offset="1" stop-color="#FFADAD" /> </linearGradient> </defs> </svg>'
                    },
                    {
                        name: 'Wine Coolers',
                        url: '/services/refrigerator-repair-moorpark/wine-coolers',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="32" y="13" width="45" height="85" fill="url(#paint0_linear_425_178)" stroke="#C90000" stroke-width="4" /> <path d="M31 18H78" stroke="#C90000" stroke-width="10" /> <path d="M31 34H78" stroke="#C90000" stroke-width="4" /> <path d="M31 47H78" stroke="#C90000" stroke-width="4" /> <path d="M31 60H78" stroke="#C90000" stroke-width="4" /> <path d="M31 73H78" stroke="#C90000" stroke-width="4" /> <path d="M32 86H79" stroke="#C90000" stroke-width="4" /> <path d="M64 17L73 17" stroke="#D9D9D9" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_178" x1="30" y1="55.5" x2="79" y2="55.5" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFCACA" /> <stop offset="1" stop-color="white" /> </linearGradient> </defs> </svg>'
                    },
                    {
                        name: 'Freezers',
                        url: '/services/refrigerator-repair-moorpark/freezers',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="27" y="8" width="56" height="94" fill="url(#paint0_linear_425_189)" stroke="#C90000" stroke-width="4" /> <path d="M34 79.0093V25" stroke="#C90000" stroke-width="4" /> <path d="M68 16L77 16" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_189" x1="25" y1="55" x2="85" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFB5B5" /> <stop offset="1" stop-color="white" /> </linearGradient> </defs> </svg>'
                    },
                    {
                        name: 'Commercial Refrigerators',
                        url: '/services/refrigerator-repair-moorpark/commercial-refrigerators',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="16" y="8" width="78" height="94" fill="url(#paint0_linear_425_195)" stroke="#C90000" stroke-width="4" /> <path d="M55 101L55 23" stroke="#C90000" stroke-width="4" /> <path d="M16 21H96" stroke="#C90000" stroke-width="4" /> <path d="M48 71V53H44" stroke="#C90000" stroke-width="4" /> <path d="M62 71V53H66" stroke="#C90000" stroke-width="4" /> <path d="M79 14L88 14" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_195" x1="96" y1="55" x2="14" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFABAB" /> <stop offset="0.5" stop-color="#FFFEFE" /> <stop offset="1" stop-color="#FFA8A8" /> </linearGradient> </defs> </svg>'
                    },
                    {
                        name: 'Reach-In Coolers',
                        url: '/services/refrigerator-repair-moorpark/reach-in-coolers',
                        svg: '<svg width="96" height="96" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="16" y="8" width="78" height="94" fill="url(#paint0_linear_425_204)" stroke="#C90000" stroke-width="4" /> <rect x="20" y="25" width="31" height="73" stroke="#C90000" stroke-width="4" /> <rect x="59" y="25" width="31" height="73" stroke="#C90000" stroke-width="4" /> <path d="M55 101L55 23" stroke="#C90000" stroke-width="4" /> <path d="M16 21H96" stroke="#C90000" stroke-width="4" /> <path d="M79 14L88 14" stroke="#C90000" stroke-width="4" /> <path d="M20 62L91 62" stroke="#C90000" stroke-width="4" /> <path d="M20 74L91 74" stroke="#C90000" stroke-width="4" /> <path d="M20 49L91 49" stroke="#C90000" stroke-width="4" /> <path d="M19 86L90 86" stroke="#C90000" stroke-width="4" /> <path d="M20 37L91 37" stroke="#C90000" stroke-width="4" /> <path d="M20 37L91 37" stroke="#C90000" stroke-width="4" /> <defs> <linearGradient id="paint0_linear_425_204" x1="96" y1="55" x2="14" y2="55" gradientUnits="userSpaceOnUse"> <stop stop-color="#FFA6A6" /> <stop offset="0.5" stop-color="white" /> <stop offset="1" stop-color="#FFA8A8" /> </linearGradient> </defs> </svg>'
                    },
                ],
            }
        }

    },
    methods: {
        toggleMenu() {
            this.isMenuOpen = !this.isMenuOpen;
            if (!this.isMenuOpen) this.dropdownIsVisible = false;
        },
        closeMenus() {
            this.isMenuOpen = false;
            this.dropdownIsVisible = false;
        },
        toggleDropdown() {
            this.dropdownIsVisible = !this.dropdownIsVisible;
        },
        openDropdownOnHover(event) {
            if (!this.isMobile && event.pointerType === 'mouse') this.dropdownIsVisible = true;
        },
        closeDropdownOnLeave(event) {
            if (!this.isMobile && event.pointerType === 'mouse' && !this.$refs.servicesMenu.contains(document.activeElement)) {
                this.dropdownIsVisible = false;
            }
        },
        closeDropdownOnFocusOut(event) {
            if (!event.currentTarget.contains(event.relatedTarget)) this.dropdownIsVisible = false;
        },
        handleOutsidePointer(event) {
            if (!this.$refs.header.contains(event.target)) {
                this.closeMenus();
            } else if (!this.$refs.servicesMenu.contains(event.target)) {
                this.dropdownIsVisible = false;
            }
        },
        closeWithEscape(event) {
            if (this.dropdownIsVisible) {
                this.dropdownIsVisible = false;
                this.$refs.servicesToggle.focus();
            } else if (this.isMobile && this.isMenuOpen) {
                this.closeMenus();
                this.$refs.menuToggle.focus();
            }
            event.preventDefault();
            event.stopPropagation();
        },
        handleResize() {
            const isMobile = window.innerWidth < 1024;
            if (this.isMobile !== isMobile) this.closeMenus();
            this.isMobile = isMobile;
        },
    },
    provide() {
        return {
            services: this.services,
            contactNumber: this.contactNumber,
            faqRefrigerator: this.faqRefrigerator,
            whyChooseUsItems: this.whyChooseUsItems,
            whatWeRepair: this.whatWeRepair,
        } 
    }
}

</script>

<style>

.nav-link {
    position: relative;
}

.nav-link::after {
    content: ""; /* Essential for pseudo-elements */
    position: absolute;
    bottom: 0; /* Position at the bottom */
    left: 0; /* Start from the left */
    width: 0; /* Initial width (hidden) */
    height: 2px; /* Desired border thickness */
    background-color: red; /* Desired border color */
    transition: width 0.3s ease-in-out; /* Add transition for smooth animation */
}

@media (min-width: 1024px) and (hover: hover) and (pointer: fine) {
    .nav-link:hover::after {
        width: 100%;
    }
}

.active-link-style {
    border: none;
}

.active-link-style::after {
    width: 100%;
    background-color: salmon;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-down-enter-active,
.slide-down-leave-active {
  transition: max-height 0.5s ease-in-out; /* Adjust duration and easing as needed */
  overflow: hidden; /* Crucial to prevent content overflow during transition */
}

.slide-down-enter-from,
.slide-down-leave-to {
  max-height: 0; /* Start/end state for the slide down */
}

.slide-down-enter-to,
.slide-down-leave-from {
  max-height: 1000px; /* A value larger than the maximum possible height of the content */
}

.hamburger-button {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  width: 36px;
  height: 30px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
}

.hamburger-line {
  display: block;
  width: 100%;
  height: 3px;
  background-color: black;
  transition: all 0.3s ease-in-out;
}

/* Active state for animation */
.hamburger-button.active .hamburger-line:nth-child(1) {
  transform: translateY(10px) rotate(45deg); /* Example: Move and rotate top line */
}

.hamburger-button.active .hamburger-line:nth-child(2) {
  opacity: 0; /* Example: Hide middle line */
}

.hamburger-button.active .hamburger-line:nth-child(3) {
  transform: translateY(-10px) rotate(-45deg); /* Example: Move and rotate bottom line */
}

.collapse-enter-active,
.collapse-leave-active {
  transition: max-height 0.3s ease-in-out;
  overflow: hidden;
}
.collapse-enter-from,
.collapse-leave-to {
  max-height: 0;
}
.collapse-enter-to,
.collapse-leave-from {
  max-height: 500px; /* Adjust based on expected content height */
}


@reference "tailwindcss";
@layer components {
    .btn-primary {
        /*border-radius: calc(infinity * 1px);*/
        border-radius: 10px;
        background-color: var(--color-red-400);
        padding-inline: --spacing(5);
        padding-block: --spacing(2);
        font-weight: var(--font-weight-semibold);
        color: var(--color-white);
        box-shadow: var(--shadow-md);
&:hover {

    @media (hover: hover) {
        background-color: var(--color-red-600);
    }
}
}
}

</style>

<style scoped>
.services-toggle[aria-expanded="true"]::after {
    width: 100%;
}

.header-navigation,
.header-dropdown-wrapper {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.3s ease-in-out;
}

.header-navigation-content,
.header-dropdown-content {
    min-height: 0;
    overflow: hidden;
}

.header-navigation-open,
.header-dropdown-wrapper {
    grid-template-rows: 1fr;
}

.header-dropdown-enter-from,
.header-dropdown-leave-to {
    grid-template-rows: 0fr;
}

@media (min-width: 1024px) {
    .header-navigation {
        display: block;
    }

    .header-navigation-content {
        overflow: visible;
    }

    .header-dropdown-content {
        max-height: calc(100dvh - 10rem);
        overflow-y: auto;
    }
}

@media (prefers-reduced-motion: reduce) {
    .header-navigation,
    .header-dropdown-wrapper {
        transition: none;
    }
}
</style>

<style scoped>
.footer-grid { display:grid; grid-template-columns:1fr; gap:24px; padding:32px 24px; }
.footer-business { max-width:280px; }
.footer-business img { max-width:240px; }
.footer-links ul { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); column-gap:20px; }
.footer-links a { display:inline-block; padding-block:4px; text-underline-offset:4px; }
.footer-links a:hover { text-decoration:underline; }
.footer-meta { grid-column:1/-1; border-top:1px solid #ffffff26; padding-top:20px; font-size:13px; }
@media(min-width:768px) { .footer-grid { grid-template-columns:1.2fr 1.4fr .7fr; gap:36px; padding:40px max(32px,calc((100% - 1200px)/2)); } .footer-links:last-of-type ul { grid-template-columns:1fr; } }
@media(max-width:1023px) {
.header-navigation .nav-link { padding:14px 24px; font-size:20px; }
.header-dropdown-content { max-height:32dvh; overflow-y:auto; overscroll-behavior:contain; }
.header-dropdown-content a { padding:12px 32px; font-size:15px; }
}
</style>
