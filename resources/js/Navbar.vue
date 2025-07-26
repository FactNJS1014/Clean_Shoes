<script setup>
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

function navigateWithReload(path) {
  if (route.path !== path) {
    router.push(path).then(() => {
      window.location.reload();
    });
  } else {
    window.location.reload();
  }
}
</script>

<template>
  <div class="flex items-center justify-center p-4">
    <ul class="gap-3 menu menu-vertical lg:menu-horizontal bg-base-200 rounded-box">
      <li>
        <a
          href="#"
          @click.prevent="navigateWithReload('/')"
          :class="{
            'bg-[#ffc300] text-[#000814] font-bold text-[16px]': route.path === '/',
            'text-[16px] font-semibold': true,
          }"
        >
          <v-icon name="fa-history" scale="1.2"></v-icon>
          ข้อมูลประวัติการทำความสะอาดรองเท้า
        </a>
      </li>
      <li>
        <a
          href="#"
          @click.prevent="navigateWithReload('/historybysection')"
          :class="{
            'bg-[#ffc300] text-[#000814] font-bold text-[16px]':
              route.path === '/historybysection',
            'text-[16px] font-semibold': true,
          }"
        >
          <v-icon name="ci-color-icn" scale="1.3"></v-icon>
          ข้อมูลการทำความสะอาดรองเท้าของแต่ละแผนก
        </a>
      </li>
    </ul>
  </div>
</template>
