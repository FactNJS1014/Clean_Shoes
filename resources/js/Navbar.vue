<template>
  <div class="flex items-center justify-center p-4">
    <ul class="gap-3 menu menu-vertical lg:menu-horizontal bg-base-200 rounded-box">

      <li>
        <router-link
          to="/"
          exact-active-class="bg-[#ffc300] text-[#000814] font-bold text-[16px]"
          class="text-[16px] font-semibold"
        >
          <v-icon name="fa-history" scale="1.2"></v-icon>
          ข้อมูลประวัติการทำความสะอาดรองเท้า
        </router-link>
      </li>
      <li>
        <router-link
          to="/historybysection"
          exact-active-class="bg-[#ffc300] text-[#000814] font-bold text-[16px]"
          class="text-[16px] font-semibold"
        >
          <v-icon name="ci-color-icn" scale="1.3"></v-icon>
          ข้อมูลการทำความสะอาดรองเท้าของแต่ละแผนก
        </router-link>
      </li>
    </ul>
  </div>
</template>
