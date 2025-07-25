<template>
  <div class="flex justify-center gap-1 p-2">
    <div class="w-full shadow-sm card bg-base-100 card-xs">
      <div class="card-body">
        <p class="text-[18px] font-medium text-center">
          <v-icon name="bi-table" scale="1.3" fill="#E84855"></v-icon>
          ตารางแสดงข้อมูลทำความสะอาดรองเท้าแต่ละแผนก
        </p>
        <div class="flex items-center justify-end p-2">
          <Select
            v-model="selectedSection"
            :options="sect_data"
            optionLabel="section"
            placeholder="Select Section"
            class="w-full md:w-56"
            @change="onSelectSection"
          />
        </div>
        <div class="mt-3">
          <DataTable
            :value="psc_data"
            tableStyle="min-width: 100px"
            showGridlines
            paginator
            :rows="8"
            :rowsPerPageOptions="[8, 10, 20, 32]"
          >
            <Column field="SECTCD" header="แผนก (Section)" class="text-[16px]" :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                         background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }"></Column>
            <Column field="EMPID" header="รหัสพนักงาน" class="text-[16px]" :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                         background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }"></Column>
            <Column field="EMPNM" header="ชื่อ-สกุลพนักงาน" class="text-[16px]" :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                         background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }"></Column>
            <Column
              field="DAYS"
              header="จำนวนครั้งที่ทำความสะอาดต่อวัน"
              class="text-[16px]"
             :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                        background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }"
            ></Column>
            <Column
              field="TSCLEANH_LSTDT"
              header="วันที่ทำความสะอาดล่าสุด"
              class="text-[16px]"
             :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                        background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }"
            >
                <template #body="{ data }">
                    <span v-if="data.TSCLEANH_LSTDT !== null"><Tag severity="success" :value="formattdated(data.TSCLEANH_LSTDT) " class="text-[16px]"></Tag></span>
                    <span v-else><Tag severity="danger" value="ยังไม่มีการทำความสะอาดรองเท้า" class="text-[16px]"></Tag></span>
                </template>
            </Column>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import dayjs from "dayjs";

const psc_data = ref([]);
const sect_data = ref([
  { id: 1, section: "ACC" },
  { id: 2, section: "AM" },
  { id: 3, section: "AME" },
  { id: 4, section: "FAC" },
  { id: 5, section: "GA" },
  { id: 6, section: "HR" },
  { id: 7, section: "IT" },
  { id: 8, section: "LC" },
  { id: 9, section: "MC" },
  { id: 10, section: "ME" },
  { id: 11, section: "MNG" },
  { id: 12, section: "MT" },
  { id: 13, section: "PE" },
  { id: 14, section: "PP" },
  { id: 15, section: "PRD-CA" },
  { id: 16, section: "PUR" },
  { id: 17, section: "PUR&BOI" },
  { id: 18, section: "QA" },
  { id: 19, section: "QC" },
  { id: 20, section: "QP" },
  { id: 21, section: "SAF" },
]);

const selectedSection = ref(null); // ← ต้องมี

const fetch_psc = () => {
  axios.get("/Cleaning_Shoes/api/get-procedure-clean").then((res) => {

    res.data.forEach((item) => {
      psc_data.value.push({
        SECTCD: item.SECTCD,
        EMPID: item.EMPID,
        EMPNM: item.EMPNM,
        DAYS: item.DAYS,
        TSCLEANH_LSTDT: item.TSCLEANH_LSTDT,
      });

    });
  });
};

const onSelectSection = () => {
  console.log(selectedSection.value.section);
  var sec = selectedSection.value.section;

  axios
    .get("/Cleaning_Shoes/api/filter-clean", {
      params: {
        sec: sec,
      },
    })
    .then((res) => {
      psc_data.value = res.data;
    });
};
const formattdated = (item) => {
  const date = dayjs(item).format("YYYY-MM-DD HH:mm:ss");
    return date;
};

onMounted(() => {
  fetch_psc();
});
</script>
