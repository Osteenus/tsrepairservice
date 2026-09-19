<template>
    <section class="bg-gray-200 text-center rounded-2xl m-4 md:mx-12 p-4 md:p-8 shadow-lg">
        <component :is="headingTag" class="text-3xl font-semibold uppercase pt-4 pb-4">Service Area</component>
        <p class="max-w-3xl mx-auto text-lg md:text-xl leading-8">
            {{ description }}
        </p>
        <ul class="flex flex-wrap justify-center gap-x-6 gap-y-3 py-6 md:py-8">
            <li v-for="location in locations" :key="location" class="flex items-center gap-2 text-left">
                    <svg width="24px" height="24px" class="shrink-0" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.4584 9.66354C10.4584 8.88161 11.1224 8.2012 11.9999 8.2012C12.8773 8.2012 13.5413 8.88161 13.5413 9.66354C13.5413 10.4455 12.8773 11.1259 11.9999 11.1259C11.1224 11.1259 10.4584 10.4455 10.4584 9.66354Z" fill="#c90000" class="my-path"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.32769 4.66819C8.83875 0.710375 15.1616 0.710374 18.6727 4.66819C21.5094 7.86579 21.5094 12.5932 18.6727 15.7908L13.8467 21.2309C13.688 21.4098 13.5458 21.57 13.4194 21.6962C13.2857 21.8296 13.1305 21.9656 12.9348 22.0689C12.3514 22.3768 11.649 22.3768 11.0656 22.0689C10.8699 21.9656 10.7147 21.8296 10.581 21.6962C10.4545 21.57 10.3124 21.4098 10.1538 21.2309L5.32769 15.7908C2.49103 12.5932 2.49103 7.86579 5.32769 4.66819ZM11.9999 6.6012C10.291 6.6012 8.8584 7.94656 8.8584 9.66354C8.8584 11.3805 10.291 12.7259 11.9999 12.7259C13.7087 12.7259 15.1413 11.3805 15.1413 9.66354C15.1413 7.94656 13.7087 6.6012 11.9999 6.6012Z" fill="#c90000" class="my-path"></path>
                    </svg>
                <span class="text-base md:text-lg">{{ location }}</span>
            </li>
        </ul>
        <div class="w-full overflow-hidden rounded-xl border border-gray-300 bg-white">
            <iframe src="https://www.google.com/maps/d/embed?mid=1vKpXKOZi0MRyeTJMq5PERRIDJJRIfmQ&ehbc=2E312F&noprof=1"
                title="TS Repair Service service area map"
                class="block w-full h-[360px] md:h-[480px] border-0"
                width="100%" height="480" loading="lazy">
            </iframe>
        </div>
        <p class="max-w-2xl mx-auto pt-6 pb-2 text-base md:text-lg leading-7">
            Not sure if you’re in our service area?
            <a href="/contact" class="font-semibold text-red-700 underline underline-offset-4 hover:text-red-800">Contact us</a>
            and we’ll let you know.
        </p>
    </section>
</template>

<script>


export default {
    props: {
        description: { type: String, default: 'We provide refrigerator repair in Moorpark and surrounding areas, including:' },
        headingTag: { type: String, default: 'h1' },
    },

    data() {
        return {
            locations: [
                "Moorpark",
                "Simi Valley",
                "San Bernandino Valley",
                "Santa Clarita"
            ]
        }
    },

    name: "ServiceAreaSection"
}
</script>

<style scoped>

</style>