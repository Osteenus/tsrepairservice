<template>
  <div class="py-16 bg-radial from-indigo-600 to-indigo-400">
    <form class="py-12 px-12 max-w-3xl mx-auto rounded-xl bg-white">
    <div >
      <div class="border-gray-900/10 pb-4">
        <h2 class="text-3xl font-semibold text-gray-500">Schedule Service</h2>
        <div class="mt-6 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <label for="username" class="block text-sm/6 font-medium text-gray-500">Name<span class="text-red-500 pl-1">*</span></label>
            <div class="mt-2">
              <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                <div class="shrink-0 text-base text-gray-500 select-none sm:text-sm/6"></div>
                <input type="text" name="username" id="username" class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="Enter Your Name" />
              </div>
            </div>
          </div>

          <div class="sm:col-span-4">
            <label for="tel" class="block text-sm/6 font-medium text-gray-500">Phone number<span class="text-red-500 pl-1">*</span></label>
            <div class="mt-2">
              <input id="tel" name="tel" type="tel" autocomplete="tel" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <div class="sm:col-span-4">
            <label for="email" class="block text-sm/6 font-medium text-gray-500">Email address</label>
            <div class="mt-2">
              <input id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <div class="sm:col-span-3">
            <label for="appliance" class="block text-sm/6 font-medium text-gray-500">Appliance Type</label>
            <div class="mt-2 grid grid-cols-1">
              <select id="appliance" name="appliance" autocomplete="appliance-name" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-500 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                <option selected>Choose what do you want to fix</option>
                <option>Washer</option>
                <option>Dryer</option>
                <option>Oven&Range</option>
              </select>
              <ChevronDownIcon class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4" aria-hidden="true" />
            </div>
          </div>

          <div class="col-span-full">
            <label for="about" class="block text-sm/6 font-medium text-gray-500">Describe the Problem<span class="text-red-500 pl-1">*</span></label>
            <div class="mt-2">
              <textarea name="about" id="about" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- <div class="col-span-full">
            <label for="cover-photo" class="block text-sm/6 font-medium text-gray-500">Photo</label>
            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
              <div class="text-center">
                <PhotoIcon class="mx-auto size-12 text-gray-300" aria-hidden="true" />
                <div class="mt-4 flex text-sm/6 text-gray-600">
                  <label for="file-upload" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-indigo-600 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-indigo-600 hover:text-indigo-500">
                    <span>Upload a file</span>
                    <input id="file-upload" name="file-upload" type="file" class="sr-only" />
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
              </div>
            </div>
          </div> -->

        </div>
      </div>
    </div>

    <div class="mt-6 flex items-center justify-start gap-x-6">
      <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
    </div>
  </form>
  </div>
</template>

<script setup>
  import { ChevronDownIcon } from '@heroicons/vue/16/solid';

</script>

<style lang="">
  
</style>