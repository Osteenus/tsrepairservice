<script setup>
  import 'vue3-carousel/carousel.css'
  import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

  const carouselConfig = {
    height: 360,
    itemsToShow: 4,
    gap: 20,
  }

  const images = Array.from({ length: 10 }, (_, index) => ({
    id: index + 1,
    url: `https://picsum.photos/seed/${Math.random()}/800/600`,
  }))

  const reviews = [
    {
      "reviewColorClass": "bg-green-800",
      "reviewShort": "S",
      "reviewAuthor": "Sarah M.",
      "reviewDate": "June 3, 2024",
      "reviewText": "I called for a fridge repair and they came out the same day. The technician explained everything clearly and the price was exactly what I was quoted. My fridge has been working perfectly ever since. I highly recommend their service!"
    },
    {
      "reviewColorClass": "bg-violet-800",
      "reviewShort": "D",
      "reviewAuthor": "David R.",
      "reviewDate": "July 15, 2024",
      "reviewText": "Excellent service from start to finish. Booking was easy, the technician arrived on time, and the repair was done quickly. I also appreciated that they cleaned up after the job. I'll definitely use them again for any appliance issues."
    },
    {
      "reviewColorClass": "bg-red-800",
      "reviewShort": "M",
      "reviewAuthor": "Maria L.",
      "reviewDate": "August 22, 2024",
      "reviewText": "Our washer stopped spinning and I was worried I'd need a new one. The technician diagnosed the problem right away and had the part on hand to fix it. The whole process was smooth and professional. Great experience overall!"
    },
    {
      "reviewColorClass": "bg-yellow-500",
      "reviewShort": "J",
      "reviewAuthor": "James P.",
      "reviewDate": "September 2, 2024",
      "reviewText": "Very professional and knowledgeable team. They repaired my oven and gave me tips on how to keep it running longer. The upfront pricing gave me peace of mind, and the warranty makes me feel confident about the repair."
    },
    {
      "reviewColorClass": "bg-blue-800",
      "reviewShort": "L",
      "reviewAuthor": "Linda C.",
      "reviewDate": "September 10, 2024",
      "reviewText": "I've had other repair companies before, but this one stood out for their honesty and friendliness. The technician explained everything in plain language, and the repair was done faster than expected. Truly excellent customer service!"
    }
  ]

</script>

<template lang="">
  <div class="flex flex-col bg-blue-800 pt-8 pb-16">
    <h1 class="py-10 text-5xl text-center text-white font-semibold">Customer Reviews</h1>
    <div class="px-9">
      <Carousel 
        :items-to-show="1"
        breakpoint-mode="carousel"
        :breakpoints="{
          700: {
            itemsToShow: 3,
            snapAlign: 'center',
          },
          1000: {
            itemsToShow: 4,
            snapAlign: 'start',
          }
        }"
        :gap="18"
      >
        <Slide v-for="review in reviews" :key="review.id">
          <div class="h-full rounded-xl bg-white">
            <div class="flex flex-row p-1">
              <div :class="review.reviewColorClass" class="flex justify-center align-middle not-even:rounded-full w-12 h-12 m-4" >
                <span class="mt-1 text-white text-3xl">{{ review.reviewShort }}</span>
              </div>
              <div class="flex flex-col justify-center">
                <span class="text-xl">{{ review.reviewAuthor }}</span>
                <span class="text-sm">{{ review.reviewDate }}</span>
              </div>
            </div>
            <div class="flex flex-col px-4 pt-2 pb-8">
              <div class="flex flex-row p-1">
                <span v-for="stars in 5">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.4135 2.43604C11.9763 2.43604 11.6221 2.62941 11.3551 2.89573C11.1079 3.14219 10.9194 3.46478 10.7614 3.79699C10.4522 4.44729 10.1454 5.39153 9.77046 6.54552L9.40751 7.66258C9.29347 8.01354 9.22765 8.21216 9.16409 8.35135C9.13721 8.41021 9.11883 8.43954 9.10948 8.45238C9.09437 8.45731 9.0608 8.46573 8.99652 8.4731C8.8445 8.49054 8.63526 8.49176 8.26624 8.49176L7.09169 8.49176C5.87832 8.49173 4.8855 8.49171 4.17146 8.58486C3.8067 8.63244 3.44163 8.71208 3.13086 8.87095C2.79506 9.04261 2.50169 9.31976 2.3666 9.73553C2.2315 10.1513 2.30594 10.548 2.47671 10.8842C2.63475 11.1954 2.88329 11.4744 3.15041 11.7273C3.67333 12.2224 4.47655 12.8059 5.45821 13.5191L6.40844 14.2095C6.70698 14.4264 6.87554 14.5504 6.98828 14.6538C7.03595 14.6976 7.05816 14.7241 7.06749 14.737C7.0675 14.7529 7.06514 14.7874 7.05228 14.8508C7.02189 15.0008 6.9584 15.2002 6.84436 15.5511L6.4814 16.6682C6.10643 17.8222 5.7996 18.7664 5.66755 19.4743C5.60008 19.8359 5.56302 20.2077 5.61807 20.5523C5.67756 20.9247 5.85049 21.2894 6.20417 21.5464C6.55784 21.8033 6.9581 21.8551 7.33066 21.7966C7.67546 21.7425 8.01762 21.5923 8.34069 21.4164C8.9731 21.0721 9.77627 20.4885 10.7579 19.7753L11.7081 19.0849C12.0067 18.868 12.1767 18.746 12.3099 18.6707C12.3663 18.6389 12.3984 18.626 12.4135 18.6211C12.4286 18.626 12.4607 18.6389 12.5171 18.6707C12.6503 18.746 12.8203 18.868 13.1188 19.0849L14.0691 19.7753C15.0507 20.4885 15.8539 21.0721 16.4863 21.4164C16.8094 21.5923 17.1515 21.7425 17.4963 21.7966C17.8689 21.8551 18.2691 21.8033 18.6228 21.5464C18.9765 21.2894 19.1494 20.9247 19.2089 20.5523C19.264 20.2077 19.2269 19.8359 19.1594 19.4743C19.0274 18.7664 18.7206 17.8222 18.3456 16.6682L17.9854 15.5596C17.8707 15.2066 17.8068 15.006 17.7763 14.8551C17.7634 14.7914 17.761 14.7567 17.7611 14.7407C17.7705 14.7278 17.793 14.7012 17.8411 14.6575C17.9551 14.554 18.1254 14.4303 18.4269 14.2139L19.3812 13.529C20.3759 12.8151 21.1891 12.2315 21.7188 11.7363C21.9893 11.4834 22.2409 11.2046 22.4016 10.8934C22.5752 10.5572 22.6527 10.159 22.5181 9.7405C22.3835 9.322 22.0883 9.04373 21.7512 8.87179C21.4392 8.71265 21.0722 8.63285 20.705 8.58515C19.9859 8.49171 18.9851 8.49173 17.7607 8.49176L16.5607 8.49176C16.1917 8.49176 15.9825 8.49054 15.8305 8.4731C15.7662 8.46573 15.7326 8.45731 15.7175 8.45238C15.7082 8.43954 15.6898 8.41021 15.6629 8.35135C15.5993 8.21216 15.5335 8.01354 15.4195 7.66258L15.0565 6.54554C14.6816 5.39153 14.3748 4.4473 14.0656 3.79699C13.9076 3.46478 13.7191 3.14219 13.4719 2.89573C13.2049 2.62941 12.8507 2.43604 12.4135 2.43604Z" fill="#ffdc2e" class="my-path"></path>
                  </svg>
                </span>
              </div>
              <p class="p-2">
                {{ review.reviewText }}
              </p>
            </div>
          </div>
      </Slide>
      <template #addons>
        <Navigation />
        <Pagination />
      </template>
    </Carousel>   
    </div>
  </div>
  
</template>

<style >
  .carousel {
  --vc-nav-background: rgba(0, 0, 0, 0.3);
  --vc-nav-color: white;
  --vc-nav-color-hover: #e5e5e5;
  --vc-nav-border-radius: 50%;
  --vc-nav-width: 40px;
  --vc-nav-height: 40px;
}

  .carousel__next {
    margin-right: 8px;
  }


  .carousel__prev {
    margin-left: 8px;
  }
  
</style>