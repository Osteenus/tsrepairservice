<script>
import Layout from '../Layouts/Layout.vue'

export default {
    // Using a render function...
    // layout: (h, page) => h(Layout, [page]),

    // Using shorthand syntax...
    layout: Layout,
}
</script>

<script setup>
defineProps({ user: Object })
</script>

<template>

    <div :style="{ 'background-image': 'url(' + '../storage/img/components/service-maintenance-worker-repairing.jpg' + ')' }" 
          class="bg-cover bg-center bg-stone-600 bg-blend-multiply">
        <h1 class="text-5xl text-white text-center font-semibold py-24">About Us</h1>
    </div>

    <div class="bg-white flex flex-col">

        <div class="grid grid-cols-2 gap-4">
            <div class="pl-16 pt-8 flex flex-col">
                <h1 class="leading-16 text-5xl text-left text-black font-semibold py-12">Your Local Family-Owned Appliance & Electronics Repair Experts</h1>
                <p class="leading-8">At <span class="font-semibold">Tech Solutions Repair Service</span>, we’re a young, family-owned business proudly serving homeowners in Ventura County. We combine professional skill with personal care to keep your appliances and electronics running like new. From refrigerators and washers to ovens, dryers, and TVs — if it plugs in, we can fix it.</p>
            </div>
            <img src="/storage/img/components/repair-man-2.jpg" alt="" class="max-h-[600px]">
        </div>

        <div class="grid grid-cols-2 gap-4 pb-12">
            <img src="/storage/img/components/repair-man-3.jpg" alt="" class="pl-16 max-h-[500px]">
            <div class="px-16 pt-8 flex flex-col">
                <h3 class="text-2xl font-semibold pb-4">Our Story</h3>
                <p class="leading-8">We started this business with a simple idea — to bring honesty, quality, and respect back to the repair industry. What began as a small family venture quickly grew through word of mouth and satisfied customers across [Your City or Area]. We believe in treating every customer like a neighbor, not a number.</p>
                <h3 class="text-2xl font-semibold pt-8 pb-4">Our Mission</h3>
                <p class="leading-8">To deliver fast, reliable, and affordable appliance and electronics repair with genuine customer care. Every repair is an opportunity to earn your trust, and we take pride in doing the job right — the first time.</p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-4 pt-8 gap-4 pb-12 bg-slate-900">
        <div class="col-span-2 flex flex-col py-12 px-22">
            <h1 class="pb-4 leading-16 text-4xl text-left text-white font-semibold">Why Customers Choose Us</h1>
            <p class="pb-12 leading-8 text-white">Homeowners in Moorpark choose us for honest service, dependable repairs, and friendly support. We keep every repair simple, fast, and transparent from start to finish.</p>
            <img src="/storage/img/components/technician-1.jpg" alt="" class="rounded-2xl">
        </div>
        
        <div class="col-span-2 flex flex-col pt-12 pr-24">

            <div class="flex flex-col mt-8 p-6 rounded-2xl bg-slate-800">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0314 9.3002C9.71176 9.3002 7.83136 11.1806 7.83136 13.5002C7.83136 15.8198 9.71176 17.7002 12.0314 17.7002C14.351 17.7002 16.2314 15.8198 16.2314 13.5002C16.2314 11.1806 14.351 9.3002 12.0314 9.3002ZM12.8314 11.5002C12.8314 11.0584 12.4732 10.7002 12.0314 10.7002C11.5895 10.7002 11.2314 11.0584 11.2314 11.5002V13.7002H10.0314C9.58953 13.7002 9.23136 14.0584 9.23136 14.5002C9.23136 14.942 9.58953 15.3002 10.0314 15.3002H12.0314C12.4732 15.3002 12.8314 14.942 12.8314 14.5002V11.5002Z" fill="#ffffff" class="my-path"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.23136 2.5002C9.23136 2.05837 9.58953 1.7002 10.0314 1.7002H14.0314C14.4732 1.7002 14.8314 2.05837 14.8314 2.5002C14.8314 2.94202 14.4732 3.3002 14.0314 3.3002H12.8314V4.73607C13.9254 4.83466 14.9617 5.13347 15.9033 5.59559L16.192 5.31623C17.3031 4.24075 19.0758 4.2697 20.1512 5.38087C21.2267 6.49204 21.1978 8.26467 20.0866 9.34014L19.8881 9.53223C20.4914 10.7245 20.8314 12.0726 20.8314 13.5002C20.8314 15.6422 20.0661 17.6054 18.794 19.1314L20.597 20.9345C20.9095 21.2469 20.9095 21.7535 20.597 22.0659C20.2846 22.3783 19.7781 22.3783 19.4657 22.0659L17.6626 20.2628C16.1366 21.5349 14.1734 22.3002 12.0314 22.3002C9.88936 22.3002 7.92611 21.5349 6.40011 20.2628L4.59704 22.0659C4.28462 22.3783 3.77809 22.3783 3.46567 22.0659C3.15325 21.7535 3.15325 21.2469 3.46567 20.9345L5.26874 19.1314C3.99666 17.6054 3.23136 15.6422 3.23136 13.5002C3.23136 12.0895 3.56331 10.7563 4.15338 9.57445L3.91128 9.34014C2.80011 8.26467 2.77117 6.49204 3.84664 5.38087C4.92211 4.2697 6.69474 4.24075 7.80591 5.31623L8.11647 5.61681C9.0695 5.14262 10.1207 4.83616 11.2314 4.73607V3.3002H10.0314C9.58953 3.3002 9.23136 2.94202 9.23136 2.5002ZM19.0165 8.14707C19.4504 7.68305 19.4483 6.95518 19.0015 6.49362C18.5428 6.01961 17.788 6.00513 17.3113 6.45955C17.9535 6.94191 18.5276 7.5101 19.0165 8.14707ZM6.71497 6.48703C6.07502 6.9729 5.5036 7.54436 5.01778 8.18435C4.54772 7.72286 4.53744 6.96774 4.99633 6.49362C5.45725 6.0174 6.21694 6.005 6.69316 6.46592L6.71497 6.48703ZM6.23136 13.5002C6.23136 10.2969 8.82811 7.7002 12.0314 7.7002C15.2346 7.7002 17.8314 10.2969 17.8314 13.5002C17.8314 16.7034 15.2346 19.3002 12.0314 19.3002C8.82811 19.3002 6.23136 16.7034 6.23136 13.5002Z" fill="#ffffff" class="my-path"></path>
                </svg>
                <h3 class="pt-3 pb-1 text-2xl text-white font-semibold">Fast Response</h3>
                <p class="leading-8 text-white">We know broken appliances can’t wait. That’s why we offer same-day or next-day repairs to get your home running again quickly.</p>
            </div>

            <div class="flex flex-col mt-8 p-6 rounded-2xl bg-slate-800">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0593 2.19983H8.94307C7.57825 2.19981 6.47308 2.19979 5.60276 2.3168C4.69711 2.43856 3.92692 2.70007 3.31417 3.31283C2.70141 3.92558 2.4399 4.69577 2.31814 5.60142C2.20113 6.47173 2.20115 7.57689 2.20117 8.9417V15.0579C2.20115 16.4228 2.20113 17.5279 2.31814 18.3982C2.4399 19.3039 2.70141 20.0741 3.31417 20.6868C3.92692 21.2996 4.69711 21.5611 5.60276 21.6829C6.47307 21.7999 7.57823 21.7999 8.94303 21.7998H12.0012C12.443 21.7998 12.8012 21.4417 12.8012 20.9998C12.8012 20.558 12.443 20.1998 12.0012 20.1998H9.00117C7.56434 20.1998 6.5672 20.1981 5.81596 20.0971C5.08773 19.9992 4.71146 19.8214 4.44554 19.5555C4.17961 19.2895 4.00178 18.9133 3.90387 18.185C3.80287 17.4338 3.80117 16.4367 3.80117 14.9998V8.99983C3.80117 7.563 3.80287 6.56586 3.90387 5.81461C4.00178 5.08638 4.17961 4.71012 4.44554 4.4442C4.71146 4.17827 5.08773 4.00044 5.81596 3.90253C6.5672 3.80153 7.56434 3.79983 9.00117 3.79983H12.0012C13.438 3.79983 14.4351 3.80153 15.1864 3.90253C15.9146 4.00044 16.2909 4.17827 16.5568 4.4442C16.8227 4.71012 17.0006 5.08638 17.0985 5.81461C17.1798 6.4198 17.1968 7.18456 17.2003 8.2102C14.9658 8.36466 13.2012 10.2262 13.2012 12.4998C13.2012 13.549 13.5769 14.5104 14.2012 15.2568L14.2012 19.5921C14.2011 19.6754 14.2011 19.7568 14.2052 19.8179C14.3023 21.277 16.0064 22.0175 17.1393 21.093C17.1867 21.0542 17.2462 20.9986 17.3071 20.9418L17.3257 20.9244L17.3643 20.8885L17.3717 20.8817C17.4464 20.8183 17.556 20.8183 17.6307 20.8817L17.6381 20.8885L17.6767 20.9244L17.6953 20.9418C17.7561 20.9986 17.8157 21.0543 17.8631 21.093C18.996 22.0175 20.7001 21.277 20.7972 19.8179C20.8013 19.7568 20.8012 19.6754 20.8012 19.5922L20.8012 15.2568C21.4254 14.5104 21.8012 13.549 21.8012 12.4998C21.8012 10.578 20.5404 8.95068 18.8009 8.39975C18.799 7.28094 18.7853 6.35349 18.6842 5.60142C18.5624 4.69577 18.3009 3.92558 17.6882 3.31283C17.0754 2.70007 16.3052 2.43856 15.3996 2.3168C14.5293 2.19979 13.4241 2.19981 12.0593 2.19983ZM15.8012 19.5666V16.4507C16.3226 16.6754 16.8974 16.7998 17.5012 16.7998C18.105 16.7998 18.6798 16.6754 19.2012 16.4507V19.5667C19.2012 19.6232 19.2012 19.6603 19.2009 19.6876C19.2008 19.7041 19.2006 19.7122 19.2005 19.7147C19.1877 19.8738 19.0022 19.9544 18.8771 19.8553C18.8751 19.8535 18.869 19.8481 18.857 19.8371L18.8248 19.8073L18.7608 19.7476C18.7323 19.721 18.7016 19.6924 18.6717 19.6667C17.9979 19.09 17.0045 19.09 16.3307 19.6667C16.3008 19.6924 16.2701 19.721 16.2416 19.7476L16.234 19.7547C16.1927 19.7933 16.1655 19.8186 16.1454 19.8371C16.1332 19.8482 16.1272 19.8536 16.1253 19.8553C16.0002 19.9544 15.8147 19.8738 15.8019 19.7147L15.8017 19.7056L15.8014 19.6876C15.8012 19.6602 15.8012 19.6231 15.8012 19.5666Z" fill="#ffffff" class="my-path"></path>
                    <path d="M6.00117 8.19983C5.55934 8.19983 5.20117 8.558 5.20117 8.99983C5.20117 9.44166 5.55934 9.79983 6.00117 9.79983H11.0012C11.443 9.79983 11.8012 9.44166 11.8012 8.99983C11.8012 8.558 11.443 8.19983 11.0012 8.19983H6.00117Z" fill="#ffffff" class="my-path"></path>
                    <path d="M5.20117 12.9998C5.20117 12.558 5.55934 12.1998 6.00117 12.1998H11.0012C11.443 12.1998 11.8012 12.558 11.8012 12.9998C11.8012 13.4417 11.443 13.7998 11.0012 13.7998H6.00117C5.55934 13.7998 5.20117 13.4417 5.20117 12.9998Z" fill="#ffffff" class="my-path"></path>
                </svg>
                <h3 class="pt-3 pb-1 text-2xl text-white font-semibold">Certified Technicians</h3>
                <p class="leading-8 text-white">Our factory-trained experts work on all major brands and models, ensuring every repair is done right the first time.</p>
            </div>

            <div class="flex flex-col mt-8 p-6 rounded-2xl bg-slate-800">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4993 15.3672L17.9994 15.3671C18.4412 15.367 18.7993 15.0088 18.7993 14.567C18.7992 14.1251 18.441 13.767 17.9991 13.7671L17.4991 13.7672C17.0573 13.7672 16.6992 14.1255 16.6992 14.5673C16.6993 15.0091 17.0575 15.3672 17.4993 15.3672Z" fill="#ffffff" class="my-path"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.7992 7.27324C18.7986 6.18723 18.7905 5.28373 18.6869 4.57735C18.5699 3.77904 18.3078 3.05489 17.6364 2.57095C16.965 2.08701 16.1951 2.06744 15.4008 2.20882C14.6384 2.3445 13.6917 2.66012 12.5426 3.04317L5.08776 5.52812C4.63116 5.68031 4.24223 5.80994 3.92841 5.94478C3.59575 6.08771 3.29298 6.25826 3.02939 6.51918C2.80874 6.73761 2.62595 6.99121 2.48851 7.26961C2.32433 7.60218 2.25828 7.94335 2.22788 8.30413C2.1992 8.6445 2.19921 9.05445 2.19922 9.53577L2.19922 17.1225C2.19919 18.0184 2.19917 18.7706 2.27958 19.3687C2.36473 20.0021 2.55302 20.5812 3.01933 21.0475C3.48563 21.5138 4.06474 21.7021 4.69808 21.7872C5.29623 21.8676 6.04834 21.8676 6.94432 21.8676H17.0541C17.9501 21.8676 18.7022 21.8676 19.3004 21.7872C19.9337 21.7021 20.5128 21.5138 20.9791 21.0475C21.4454 20.5812 21.6337 20.0021 21.7189 19.3687C21.7759 18.9446 21.7925 18.443 21.7973 17.8676H21.7993L21.7992 11.0408C21.7992 10.5976 21.7992 10.2251 21.7783 9.91881C21.7565 9.59916 21.7093 9.29356 21.5861 8.99606C21.3019 8.30998 20.7568 7.7649 20.0707 7.48071C19.7732 7.35749 19.4676 7.31027 19.148 7.28846C19.04 7.28109 18.9238 7.27633 18.7992 7.27324ZM15.6811 3.78406C15.0481 3.89673 14.2131 4.1729 12.9873 4.58149L5.62392 7.03595C5.3494 7.12746 5.12736 7.20168 4.94231 7.26758H17.1991C17.1981 6.16381 17.1885 5.38658 17.1039 4.80951C17.0133 4.19193 16.86 3.98365 16.7009 3.86894C16.5417 3.75423 16.2957 3.67469 15.6811 3.78406ZM16.4993 12.8676C15.9963 12.8676 15.8616 12.8715 15.7652 12.8907C15.2891 12.9854 14.917 13.3575 14.8223 13.8335C14.8032 13.93 14.7993 14.0646 14.7993 14.5676C14.7993 15.0706 14.8032 15.2053 14.8223 15.3017C14.917 15.7778 15.2891 16.1499 15.7652 16.2446C15.8616 16.2638 15.9963 16.2676 16.4993 16.2676H20.1993V12.8676H16.4993Z" fill="#ffffff" class="my-path"></path>
                </svg>
                <h3 class="pt-3 pb-1 text-2xl text-white font-semibold">Upfront Pricing</h3>
                <p class="leading-8 text-white">You’ll always know the cost before we start. We believe in clear, honest quotes — no hidden fees or surprise charges.</p>
            </div>

            <div class="flex flex-col mt-8 p-6 rounded-2xl bg-slate-800">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.9299 13.4587C10.9299 13.4587 10.9327 13.4574 10.9373 13.4567C10.9321 13.4587 10.9299 13.4587 10.9299 13.4587Z" fill="#ffffff" class="my-path"></path>
                    <path d="M10.9644 13.4587C10.9644 13.4587 10.9616 13.4574 10.957 13.4567C10.9601 13.4579 10.9633 13.4587 10.9633 13.4587L10.9644 13.4587Z" fill="#ffffff" class="my-path"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.2002 11.9997C2.2002 6.58732 6.5878 2.19971 12.0002 2.19971C17.4126 2.19971 21.8002 6.58732 21.8002 11.9997C21.8002 17.4121 17.4126 21.7997 12.0002 21.7997C6.5878 21.7997 2.2002 17.4121 2.2002 11.9997ZM17.2364 9.51246C17.5488 9.20004 17.5488 8.69351 17.2364 8.38109C16.9239 8.06867 16.4174 8.06867 16.105 8.38109L11.7957 12.6904C11.4464 13.0397 11.2401 13.2436 11.0743 13.3701C11.0078 13.4208 10.9677 13.4433 10.9472 13.4526C10.9266 13.4433 10.8865 13.4208 10.82 13.3701C10.6542 13.2436 10.4479 13.0397 10.0986 12.6904L8.56593 11.1577C8.25351 10.8453 7.74698 10.8453 7.43456 11.1577C7.12214 11.4701 7.12214 11.9767 7.43456 12.2891L9.00037 13.8549C9.30504 14.1597 9.58777 14.4425 9.84945 14.6421C10.1391 14.8631 10.4948 15.0561 10.9472 15.0561C11.3995 15.0561 11.7552 14.8631 12.0449 14.6421C12.3065 14.4424 12.5892 14.1597 12.8939 13.8549L17.2364 9.51246Z" fill="#ffffff" class="my-path"></path>
                </svg>
                <h3 class="pt-3 pb-1 text-2xl text-white font-semibold">Guaranteed Work</h3>
                <p class="leading-8 text-white">Every job includes a parts and labor warranty, giving you peace of mind long after the repair is complete.</p>
            </div>

        </div>
    </div>


    


</template>
