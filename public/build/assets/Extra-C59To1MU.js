import{d as a,x as o,b as d,o as n,h as r,a as t,j as c,e as i}from"./app-4NQGG3MP.js";const l={class:"min-h-screen bg-gray-50 text-gray-900"},m={class:"bg-gray-100 py-8"},p={class:"container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6"},v={class:"md:w-2/3"},g={class:"mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"},x={components:{ServiceCard:{props:["title","subtitle"],template:`
        <div class="bg-white p-4 rounded shadow-sm flex flex-col items-start">
          <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-xl">🔧</div>
          <div class="mt-3 font-semibold">{{ title }}</div>
          <div class="text-xs text-gray-500">{{ subtitle }}</div>
        </div>
      `},TrustItem:{props:["icon","title","description"],template:`
        <div class="bg-white p-4 rounded flex flex-col items-start">
          <div class="flex items-center gap-3">
            <img :src="icon" alt="icon" class="w-8 h-8" />
            <div class="font-semibold">{{ title }}</div>
          </div>
          <div class="text-sm text-gray-500 mt-2">{{ description }}</div>
        </div>
      `},ReviewCard:{props:["author","text"],template:`
        <div class="bg-white p-4 rounded shadow">
          <div class="text-sm text-gray-500">★★★★★</div>
          <div class="mt-2 text-gray-700">{{ text }}</div>
          <div class="mt-3 text-xs text-gray-500">— {{ author }}</div>
        </div>
      `}}},y=Object.assign(x,{__name:"Extra",setup(f){return a(!1),a(!1),a({name:"",phone:"",note:""}),(u,e)=>{const s=o("TrustItem");return n(),d("div",l,[e[3]||(e[3]=r(" ... (header code unchanged) ... ",-1)),t("section",m,[t("div",p,[t("div",v,[e[0]||(e[0]=t("h3",{class:"text-xl font-semibold"},"Why Choose Us",-1)),e[1]||(e[1]=t("p",{class:"text-gray-600 mt-1"},"Fast calls, certified techs, transparent pricing, and warranties. We treat your home like ours.",-1)),t("div",g,[i(s,{icon:"/assets/icons/sameday.svg",title:"Same-Day Service",description:"We know appliances break at the worst times — that’s why we offer same-day repairs whenever possible."}),i(s,{icon:"/assets/icons/technician.svg",title:"Certified Techs",description:"Our team is trained and certified to work on all major brands, ensuring expert repairs every time."}),i(s,{icon:"/assets/icons/pricing.svg",title:"Upfront Pricing",description:"No hidden fees or surprises. You’ll always know the cost before we start the repair."}),i(s,{icon:"/assets/icons/warranty.svg",title:"Repair Warranty",description:"We stand behind our work with a parts and labor warranty, giving you peace of mind."})])]),e[2]||(e[2]=c('<div class="md:w-1/3"><div class="bg-white p-4 rounded shadow"><div class="text-sm text-gray-500">Service Area</div><div class="font-medium mt-1">Serving: Your City + Nearby Areas</div><div class="mt-3"><a href="#contact" class="inline-block bg-orange-500 text-white px-4 py-2 rounded">Check Availability</a></div></div></div>',1))])]),e[4]||(e[4]=r(" ... (rest of code unchanged) ... ",-1))])}}});export{y as default};
