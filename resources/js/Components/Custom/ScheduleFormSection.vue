<script setup>
import { ChevronDownIcon } from '@heroicons/vue/16/solid';
import { inject } from 'vue'
import { useForm } from '@inertiajs/vue3';

const services = inject('services')

defineProps({ errors: Object })

const form = useForm({
  name: null,
  phone: null,
  email: null,
  serviceId: null,
  description: null,
  photo: null,
  street: null,
  city: null,
  state: null,
  zip: null,
});

// The submit handler
const submit = () => {
  // Use the .post() method, passing the route name or URL
  form.post(route('contact.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.name = ''
      form.phone = ''
      form.email = ''
      form.serviceId = null
      form.description = ''
      form.name = ''
    },
    onError: errors => {
      scrollToFirstError();
      console.log('Validation errors occurred:', errors);
    },
  });

  const scrollToFirstError = () => {
    // Find the first element with a validation error
    const firstErrorElement = document.querySelector('.error-message'); // Use a specific class or data attribute for error elements

    if (firstErrorElement) {
      firstErrorElement.scrollIntoView({
        behavior: 'smooth', // Smooth scrolling animation
        block: 'start',    // Aligns the top of the element to the start of the viewport
      });
    }
  };
}

</script>

<template>
  <div id="schedule" class="py-16 px-4 bg-radial bg-slate-700">

    <!-- FORM -->

    <form @submit.prevent="submit" action="/contact" method="post" class="
        relative 
        py-12 px-12 
        max-w-3xl 
        mx-auto 
        rounded-xl 
        bg-white
      ">
      <div>
        <div class="border-gray-900/10 pb-4">
          <h2 class="text-3xl font-semibold text-slate-700">Schedule Service</h2>
          <div class="mt-6 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-6">
            <div id="nameInput" class="sm:col-span-4">
              <label for="name" class="
                block 
                text-l 
                font-medium 
                text-slate-700
              ">Name<span class="text-red-500 pl-1">*</span></label>
              <div class="mt-2">
                <div class="
                flex items-center 
                rounded-md 
                bg-white 
                pl-3 
                outline-1 
                -outline-offset-1 
                outline-slate-700 
                focus-within:outline-3 
                focus-within:-outline-offset-3
                transition-all
                duration-300
              ">
                  <div class="shrink-0 text-base text-slate-700 select-none"></div>
                  <input v-model="form.name" type="text" name="name" id="name" class="
                    block 
                    min-w-0 
                    grow 
                    bg-white 
                    py-3 pr-3 pl-1 
                    text-gray-900 
                    placeholder:text-gray-400 
                    focus:outline-none 
                    text-xl
                  " placeholder="Enter Your Name" />

                </div>
              </div>
              <div v-if="form.errors.name" class="text-red-500 error-message">{{ form.errors.name }}</div>
            </div>
            <div id="phoneInput" class="sm:col-span-4">
              <label for="phone" class="
                block 
                text-l 
                font-medium 
                text-slate-700
              ">Phone Number<span class="text-red-500 pl-1">*</span></label>
              <div class="mt-2">
                <div class="
                flex items-center 
                rounded-md 
                bg-white 
                pl-3 
                outline-1 
                -outline-offset-1 
                outline-slate-700 
                focus-within:outline-3 
                focus-within:-outline-offset-3
                transition-all
                duration-300
              ">
                  <div class="shrink-0 text-base text-slate-700 select-none"></div>
                  <input v-model="form.phone" type="tel" name="phone" id="phone" class="
                    block 
                    min-w-0 
                    grow 
                    bg-white 
                    py-3 pr-3 pl-1 
                    text-gray-900 
                    placeholder:text-gray-400 
                    focus:outline-none 
                    text-xl
                  " placeholder="123-456-7890" />
                </div>
              </div>
              <div v-if="form.errors.phone" class="text-red-500 error-message">{{ form.errors.phone }}</div>
            </div>
            <div id="emailInput" class="sm:col-span-4">
              <label for="email" class="
                block 
                text-l 
                font-medium 
                text-slate-700
              ">Email address
              </label>
              <div class="mt-2">
                <div class="
                flex items-center 
                rounded-md 
                bg-white 
                pl-3 
                outline-1 
                -outline-offset-1 
                outline-slate-700 
                focus-within:outline-3 
                focus-within:-outline-offset-3
                transition-all
                duration-300
              ">
                  <div class="shrink-0 text-base text-slate-700 select-none"></div>
                  <input v-model="form.email" type="email" name="email" id="email" class="
                    block 
                    min-w-0 
                    grow 
                    bg-white 
                    py-3 pr-3 pl-1 
                    text-gray-900 
                    placeholder:text-gray-400 
                    focus:outline-none 
                    text-xl
                  " placeholder="Enter Email Address" />
                </div>

              </div>
              <div v-if="form.errors.email" class="text-red-500 error-message">{{ form.errors.email }}</div>
            </div>

            <div id="serviceInput" class="sm:col-span-4">
              <label for="service" class="
                block 
                text-l
                font-medium 
                text-slate-700">
                What Needs to be Fixed
              </label>
              <div class="mt-2 grid grid-cols-1">
                <select v-model="form.serviceId" id="service" name="service" autocomplete="service-name" class="
                  col-start-1 
                  row-start-1 
                  w-full
                  appearance-none 
                  rounded-md 
                  bg-white 
                  py-3 pr-8 pl-3 
                  text-xl text-slate-700 
                  outline-1 
                  -outline-offset-1 
                outline-slate-700 
                  focus-within:outline-3 
                  focus-within:-outline-offset-3+
                  transition-all
                  duration-300
                ">
                  <option :value="null" disabled>Choose a service...</option>
                  <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }}</option>
                </select>
                <ChevronDownIcon
                  class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-slate-700 sm:size-4"
                  aria-hidden="true" />
              </div>
              <div v-if="form.errors.serviceId" class="text-red-500 error-message">{{ form.errors.serviceId }}</div>
            </div>

            <div id="descriptionInput" class="col-span-full">
              <label for="description" class="
                block 
                text-l
                font-medium 
                text-slate-700
              ">Describe the Problem<span class="text-red-500 pl-1">*</span></label>
              <div class="mt-2">
                <textarea v-model="form.description" name="description" id="description" rows="5" class="
                  block 
                  w-full 
                  rounded-md 
                  bg-white 
                  px-3 py-1.5 
                  text-xl 
                  text-gray-900 
                  outline-1 
                  -outline-offset-1 
                outline-slate-700 
                  focus-within:outline-3 
                  focus-within:-outline-offset-3
                  transition-all
                  duration-300
                " />
                <div v-if="form.errors.description" class="text-red-500 error-message">{{ form.errors.description }}</div>
              </div>
            </div>
            <!-- <div class="col-span-full">
              <label for="photo" class="
                block 
                text-l
                font-medium 
                text-slate-700
              ">Upload a photo(s)</label>
              <div class="mt-2 flex justify-center rounded-lg border border-dashed border-slate-700 px-6 py-10">
                <div class="text-center">
                  <svg viewBox="0 0 24 24" fill="currentColor" data-slot="icon" aria-hidden="true"
                    class="mx-auto size-12 text-gray-600">
                    <path
                      d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                      clip-rule="evenodd" fill-rule="evenodd" />
                  </svg>
                  <div class="mt-4 flex text-sm text-gray-400">
                    <label for="file-upload" class="
                      relative 
                      cursor-pointer 
                      rounded-md 
                      bg-transparent 
                      font-semibold 
                      text-indigo-400 
                      focus-within:outline-2 
                      focus-within:outline-offset-2 
                      focus-within:outline-indigo-500 
                      hover:text-indigo-300">
                      <span class="text-l md:text-sm">Upload a file</span>
                      <input 
                        id="file-upload" 
                        type="file" 
                        @input="form.photo = $event.target.files[0]"
                        name="file-upload" 
                        class="sr-only" />
                    </label>
                    <p class="pl-1 hidden md:block">or drag and drop</p>
                  </div>
                  <p class="text-xs/5 text-gray-400 hidden md:block">PNG, JPG, GIF up to 10MB</p>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>

      <div class="
      mt-6 
      flex 
      items-center 
      justify-start 
      gap-x-6">


        <button type="submit" :disabled="form.processing" class="
            bg-red-700 hover:bg-red-800 text-white font-semibold 
            md:w-120 w-full
            cursor-pointer
            px-6 py-4 rounded-2xl shadow-lg 
            flex items-center justify-center gap-2 
            text-lg transition
          ">
          {{ form.processing ? 'Sending...' : 'Send Message' }}
        </button>
        <!-- <div v-if="$page.props.flash.success" class="alert-success">
          {{ $page.props.flash.success }}
        </div> -->
      </div>
      <progress v-if="form.progress" :value="form.progress.percentage" max="100">
        {{ form.progress.percentage }}%
      </progress>
      <div v-if="form.wasSuccessful" class="fixed top-0 left-0 z-100 w-screen h-screen bg-gray-300/80 backdrop-blur-sm">
        <div
          class="flex flex-col max-w-xl absolute left-0 right-0 top-30 md:m-auto mx-4 bg-white text-gray-700 rounded-xl px-8 md:px-12 py-16 shadow-md">
          <div class="flex justify-center">
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="32" cy="32" r="32" fill="#2DC130" />
              <path d="M10.9145 32.4001L23.9595 47.9465L53.1123 23.4844" stroke="white" stroke-width="8" />
            </svg>
          </div>
          <h3 class="text-center font-bold text-2xl md:text-3xl py-6">Submission Successful!</h3>
          <p class="text-center text-lg md:text-xl leading-10 pb-6">Thanks! We received your repair request. Our team
            will reach out shortly to confirm details and discuss options.</p>
          <div class="flex justify-center">
            <a href="/"
              class="bg-green-500 text-white size-fit text-xl rounded-xl cursor-pointer px-8 py-3 transition duration-150 ease-in-out hover:scale-110">Back
              to Home</a>
          </div>

        </div>

      </div>
    </form>
  </div>
</template>



<style lang="">

</style>