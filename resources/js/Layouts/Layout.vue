<template>
    <main>
        <div class="bg-blue-900 hover:bg-blue-800 transition duration-300 cursor-pointer">
            <p class="text-md text-white text-center py-1">Get a quote for FREE estimate! Call us right now!</p>
        </div>
        <header class="flex flex-col md:flex-row min-h-18">
            <div class="flex flex-row">
                <a href="/" class="flex-auto md:block w-72 md:w-64">
                    <img src="/storage/img/logo.png" alt="Logo" class="">
                </a>
                <div class="md:hidden flex items-center">
                    <button @click="toggleMenu" :class="{ 'hamburger-button': true, 'active': isMenuOpen }" class="mx-8">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button> 
                </div>
            </div>
            <Transition name="slide-down">
                <ul v-show="this.isMenuOpen || !this.isMobile" class="flex flex-col w-full md:flex-auto md:flex-row justify-center md:w-64 md:pb-3">
                    <li class="content-center">
                        <Link href="/" :class="{ 'active-link-style': $page.url === '/' }" class="flex nav-link px-8 py-6 text-2xl md:text-xl border-b-2 border-gray-200 md:border-none">
                            Home
                        </Link>
                    </li>

                    <li class="content-center">
                        <Link href="/about" :class="{ 'active-link-style': $page.url === '/about'}" class="flex nav-link px-8 py-6 text-2xl md:text-xl border-b-2 border-gray-200 md:border-none">
                            About
                        </Link>
                    </li>

                    <li class="content-center" 
                        @mouseover="dropdownIsVisible = true" 
                        @mouseleave="dropdownIsVisible = false">
                        <Link href="/services" 
                            class="flex nav-link px-8 py-6 text-2xl md:text-xl border-b-2 border-gray-200 md:border-none" 
                            :class="{'active-link-style': 
                            $page.url === '/services'}"
                            >
                            Services
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-7 md:size-6 float-right">
                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </Link>

                        <!-- Dropdown -->

                        <Transition name="fade">
                            <div 
                                id="dropdown" 
                                text="Dropdown Button" 
                                v-show="dropdownIsVisible" 
                                class="absolute z-10 top-32 md:top-26 bg-white shadow-md flex flex-col min-w-54"
                            >
                                <ul class="py-2">
                                    <li v-for="service in services" :key="service.id" class="w-full">
                                        <Link 
                                        @click="toggleMenu"
                                        :href="service.url" 
                                        class="
                                            flex 
                                            px-12 py-2.5 
                                            md:px-4 md:py-1 
                                            border-b 
                                            border-gray-300 
                                            hover:bg-gray-100
                                        ">{{ service.name }}</Link>
                                    </li>
                                </ul>
                            </div>
                        </Transition>
                        

                    </li>

                    <li class="content-center border-b-2 border-gray-200 md:border-none">
                        <Link href="/contact" :class="{ 'active-link-style': $page.url === '/contact'}" class="flex nav-link px-8 py-6 text-2xl md:text-xl">
                            Contact
                        </Link>
                    </li>
                    <!-- <li class="content-center">
                        <Link href="/extra" :class="{ 'active-link-style': $page.url === '/extra'}" class="flex nav-link px-8 py-6 text-2xl md:text-xl">
                            Extra
                        </Link>
                    </li> -->
                </ul>
            </Transition>
            
            <div class="hidden md:block content-center px-12">
                <a :href="`tel:${contactNumber}`">
                    <button class="
                        px-4 py-2
                        text-xl
                        font-bold
                        text-red-500 
                        transition 
                        duration-300 
                        border-2 
                        rounded-xl 
                        border-red-500 
                        hover:border-red-600 
                        hover:text-red-600
                        cursor-pointer">
                            {{ contactNumber }}
                    </button>
                </a>
            </div>
        </header>
        <article>
            <slot/>
        </article>
        <footer class="">
            <div class="flex flex-col flex-wrap md:px-30 md:flex-row bg-gray-700 px-12 py-8">
                <div class="col-span-2 w-64 md:-px-20">
                    <img :src="'../storage/img/components/logo-footer.png'" alt="Logo" class="pb-4">
                    <p class="pb-4 text-sm text-white">Localy owned and family operated appliance repair service when you need it most in Moorpark, CA</p>
                    <div class="flex flex-row">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-6"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z" fill="#ffffff"></path> <path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z" fill="#ffffff"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z" fill="#ffffff"></path> </g></svg>
                        <svg fill="#ffffff" width="48" height="48" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff" class="mr-6"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M234.33057,69.79736a23.96369,23.96369,0,0,0-14.50489-16.34619C185.55615,40.28223,130.97949,40.39209,128,40.40771c-2.978-.02392-57.55518-.126-91.8252,13.04346A23.96415,23.96415,0,0,0,21.66992,69.79639C19.083,79.72705,16,97.88574,16,128c0,30.11377,3.083,48.27246,5.66943,58.20264a23.96369,23.96369,0,0,0,14.50489,16.34619c32.80615,12.60693,84.22168,13.04541,91.167,13.04541.6206.00049.69678.00049,1.31738,0,6.95069-.00049,58.36231-.43945,91.16651-13.04541a23.96415,23.96415,0,0,0,14.50488-16.34522C236.917,176.273,240,158.11426,240,128,240,97.88623,236.917,79.72754,234.33057,69.79736Zm-72.11182,61.53076-48,32A3.99967,3.99967,0,0,1,108,160V96a3.99968,3.99968,0,0,1,6.21875-3.32813l48,32a3.99979,3.99979,0,0,1,0,6.65625Z"></path> </g></svg>
                        <svg width="48" height="48 " viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-6"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H15V13.9999H17.0762C17.5066 13.9999 17.8887 13.7245 18.0249 13.3161L18.4679 11.9871C18.6298 11.5014 18.2683 10.9999 17.7564 10.9999H15V8.99992C15 8.49992 15.5 7.99992 16 7.99992H18C18.5523 7.99992 19 7.5522 19 6.99992V6.31393C19 5.99091 18.7937 5.7013 18.4813 5.61887C17.1705 5.27295 16 5.27295 16 5.27295C13.5 5.27295 12 6.99992 12 8.49992V10.9999H10C9.44772 10.9999 9 11.4476 9 11.9999V12.9999C9 13.5522 9.44771 13.9999 10 13.9999H12V21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z" fill="#ffffff"></path> </g></svg>
                    </div>
                    <div class="">
                        <h3 class="text-white text-xl pt-4">{{ contactNumber }}</h3>
                    </div>
                </div>
                <div class="flex-col mt-16 md:mt-2 md:px-20">
                    <span class="text-lg md:text-sm text-white font-bold">Our Services</span>
                    <ul class="pt-3 text-lg md:text-sm text-white">
                        <li v-for="service in services" :key="service.id" class="pt-4 md:pt-1.5"><a :href="service.url">{{ service.name }}</a></li>
                    </ul>
                </div>
                <div class="flex-col mt-16 md:mt-2 md:px-20">
                    <span class="text-lg md:text-sm text-white font-bold">Quick Links</span>
                    <ul class="pt-3 text-lg md:text-sm text-white">
                        <li class="pt-4 md:pt-1.5"><a href="/about">About Us</a></li>
                        <li class="pt-4 md:pt-1.5"><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="mt-6 w-full">
                    <span class="text-white text-md md:text-sm text-nowrap">© 2024 Tech Solutions Repair Service Inc.</span>
                </div>
            </div>
        </footer>
    </main>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { Transition, ref } from 'vue';
import Aos from 'aos';

export default {
    beforeMount() {
        if (this.screenWidth <= 768) {
            this.isMobile = true
        }
    },
    mounted() {
        Aos.init
    },
    data() {
        return {    
            services: [
            {
                id: 1,
                name: 'Refrigerator Repair',
                url: '/services/refrigerator-repair-moorpark',
                logoUrl: '/storage/img/components/side-by-side-fridge.png',
            },
            {
                id: 2,
                name: 'Washer Repair',
                url: '/services/washer-repair',
                logoUrl: '/storage/img/components/top-load-washer.jpeg'
            },
            {
                id: 3,
                name: 'Dryer Repair',
                url: '/services/dryer-repair',
                logoUrl: '/storage/img/components/dryer-1.jpg'
            },
            {
                id: 4,
                name: 'Oven Repair',
                url: '/services/oven-repair',
                logoUrl: '/storage/img/components/oven-microwave-combo-whirlpool-1.jpeg'
            },
            {
                id: 5,
                name: 'Dishwasher Repair',
                url: '/services/dishwasher-repair',
                logoUrl: '/storage/img/components/dishwasher.png'
            },
            {
                id: 6,
                name: 'Stove & Range Repair',
                url: '/services/stove-range-repair',
                logoUrl: '/storage/img/components/cooktop-gas-1.jpeg'
            },
            {
                id: 7,
                name: 'Microwave Repair',
                url: '/services/microwave-repair',
                logoUrl: '/storage/img/components/microwave.png'
            },
            {
                id: 8,
                name: 'Range Hood Repair',
                url: '/services/range-hood-repair',
                logoUrl: '/storage/img/components/range-hood-1.jpeg'
            },
            {
                id: 9,
                name: 'Trash Compactor Repair',
                url: '/services/trash-compactor-repair',
                logoUrl: '/storage/img/components/trash-compactor-1.jpeg'
            },
            {
                id: 10,
                name: 'Electronic Repair',
                url: '/services/electronic-repair',
                logoUrl: '/storage/img/components/pcb.png'
            },
            // {
            //     id: 11,
            //     name: 'Other',
            //     url: '#',
            //     logoUrl: ''
            // },
            ],
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
            screenWidth: ref(window.innerWidth),
            isMobile: false,

            contactNumber: '(805)-243-9549'
        }
    },
    methods: {
        toggleMenu() {
            this.isMenuOpen = !this.isMenuOpen;
        }
    },
    provide() {
        return {
            services: this.services,
            contactNumber: this.contactNumber,
            faqRefrigerator: this.faqRefrigerator,
            whyChooseUsItems: this.whyChooseUsItems
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

.nav-link:hover::after {
    width: 100%; /* Expand to full width */
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

@import "tailwindcss";
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
