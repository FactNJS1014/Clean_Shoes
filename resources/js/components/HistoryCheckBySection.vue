<template>
  <div class="flex justify-center gap-1 p-2">
    <div class="card bg-base-100 card-xs shadow-sm w-full">
      <div class="card-body">
        <p class="text-[18px] font-medium text-center">
          <v-icon name="bi-table" scale="1.3" fill="#E84855"></v-icon>
          ตารางแสดงข้อมูลทำความสะอาดรองเท้าแต่ละแผนก
        </p>
        <div class="flex justify-end items-center p-2">
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
            <Column field="SECTCD" header="แผนก (Section)" class="text-[16px]"></Column>
            <Column field="EMPID" header="รหัสพนักงาน" class="text-[16px]"></Column>
            <Column field="EMPNM" header="ชื่อ-สกุลพนักงาน" class="text-[16px]"></Column>
            <Column
              field="DAYS"
              header="จำนวนวันทำความสะอาดรองเท้า"
              class="text-[16px]"
            ></Column>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

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
  { id: 10, section: "MNG" },
  { id: 11, section: "MT" },
  { id: 12, section: "PE" },
  { id: 13, section: "PP" },
  { id: 14, section: "PRD-CA" },
  { id: 15, section: "PUR" },
  { id: 16, section: "PUR&BOI" },
  { id: 17, section: "QA" },
  { id: 18, section: "QC" },
  { id: 19, section: "QP" },
  { id: 20, section: "SAF" },
]);

const selectedSection = ref(null); // ← ต้องมี

const fetch_psc = () => {
  axios.get("/CheckESD/api/get-procedure-clean").then((res) => {
    psc_data.value = res.data;

    res.data.forEach((v) => {
      psc_data.value = v;
      console.log(psc_data.value);
    });
  });
};

const onSelectSection = () => {
  console.log(selectedSection.value.section);
  var sec = selectedSection.value.section;

  axios
    .get("/CheckESD/api/filter-clean", {
      params: {
        sec: sec,
      },
    })
    .then((res) => {
      psc_data.value = res.data;
    });
};

onMounted(() => {
  fetch_psc();
});
</script>
