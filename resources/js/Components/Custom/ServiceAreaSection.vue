<template>
    <div class="flex flex-col bg-white">
        <h1 class="mt-6 text-5xl text-center font-semibold py-4">Service Area</h1>
        <div class="flex flex-row p-12">
            <ul class="flex flex-col basis-1/4 mt-6">
                <li v-for="location in locations" :key="location.id" class="flex flex-row py-2 pl-8">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.4584 9.66354C10.4584 8.88161 11.1224 8.2012 11.9999 8.2012C12.8773 8.2012 13.5413 8.88161 13.5413 9.66354C13.5413 10.4455 12.8773 11.1259 11.9999 11.1259C11.1224 11.1259 10.4584 10.4455 10.4584 9.66354Z" fill="#c90000" class="my-path"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.32769 4.66819C8.83875 0.710375 15.1616 0.710374 18.6727 4.66819C21.5094 7.86579 21.5094 12.5932 18.6727 15.7908L13.8467 21.2309C13.688 21.4098 13.5458 21.57 13.4194 21.6962C13.2857 21.8296 13.1305 21.9656 12.9348 22.0689C12.3514 22.3768 11.649 22.3768 11.0656 22.0689C10.8699 21.9656 10.7147 21.8296 10.581 21.6962C10.4545 21.57 10.3124 21.4098 10.1538 21.2309L5.32769 15.7908C2.49103 12.5932 2.49103 7.86579 5.32769 4.66819ZM11.9999 6.6012C10.291 6.6012 8.8584 7.94656 8.8584 9.66354C8.8584 11.3805 10.291 12.7259 11.9999 12.7259C13.7087 12.7259 15.1413 11.3805 15.1413 9.66354C15.1413 7.94656 13.7087 6.6012 11.9999 6.6012Z" fill="#c90000" class="my-path"></path>
                    </svg>
                    <span class="pl-1 text-xl">{{ location }}</span>
                </li>
            </ul>
            <div class="bg-white basis-3/4 px-6">
                <iframe src="https://www.google.com/maps/d/embed?mid=1vKpXKOZi0MRyeTJMq5PERRIDJJRIfmQ&ehbc=2E312F&noprof=1"
                    width="100%"
                    height="480">
                </iframe>
            </div>
        </div>
        
    </div>
    <div>

    </div>
</template>

<script>


export default {

    data() {
        return {
            locations: [
                "Moorpark",
                "Simi Valley",
                "Bakersfield",
                "Antelope Valley"
            ]
        }
    },

    name: "ServiceAreaSection"
}
</script>

<style scoped>

</style>