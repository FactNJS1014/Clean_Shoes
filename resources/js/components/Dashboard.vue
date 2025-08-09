<template>
  <div class="p-2 flex items-center">
    <h3 class="text-[22px] font-semibold text-white mb-4">
      Cleanig shoes data for each department within a 7 days period
      (ตารางแสดงข้อมูลควบคุมทำความสะอาดรองเท้าของแต่ละแผนกภายในระยะเวลา 7 วัน) ,
      Shift:&nbsp;
      <span v-if="shift === 'DAYS'">
        <v-icon name="md-sunny" fill="#ffd60a" scale="1.2"></v-icon>
        <span class="text-[20px] font-bold text-white ms-1">DAYS</span>
      </span>
      <span v-else>
        <v-icon name="fa-moon" fill="#ffd60a" scale="1.2"></v-icon>
        <span class="text-[20px] font-bold text-white ms-1">NIGHT</span>
      </span>
    </h3>
  </div>
  <div class="p-2">
    <div class="grid grid-cols-4 gap-4">
      <div
        v-for="(section, index) in get_sec"
        :key="index"
        class="card bg-[#37474F] shadow-sm card-xs"
      >
        <div class="card-body">
          <div class="flex items-center justify-between">
            <div>
              <span><div class="badge bg-[#f87171] badge-xs mr-1 mb-3"></div></span>
              <span class="text-[20px] text-white font-semibold">
                Over: {{ section.filter((user) => user.DAYS > 7).length }} /
                {{ section.length }}</span
              >
              <!-- <span class="text-[16px] text-white"
                ><div class="badge bg-[#4ade80] badge-xs mr-1"></div>
                Checked: {{ section.filter((user) => user.DAYS <= 7).length }}</span
              > -->
            </div>

            <button class="btn btn-info" @click="showDetailsBySection(index)">
              <v-icon name="io-document-text" scale="1.0"></v-icon>&nbsp;Details
            </button>
          </div>
          <!-- <div class="mt-2">
            <p class="text-[16px] text-sky-500">
              จำนวนพนักงานทั้งหมด: {{ section.length }}
            </p>
          </div> -->
          <div>
            <SectionBarChart :sectionName="index" :sectionData="section" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <dialog id="details" class="modal">
    <div class="modal-box w-full max-w-5xl">
      <div class="mt-4">
        <DataTable
          :value="details_cln"
          tableStyle="min-width: 100px"
          showGridlines
          paginator
          :rows="5"
          :rowsPerPageOptions="[5, 10, 20]"
        >
          <Column
            field="SECTCD"
            header="แผนก (Section)"
            class="text-[16px]"
            :pt="{
              headerCell: {
                class: 'header-cell text-lg font-semibold',
                id: 'z',
                prime: 'vue',
                style: {
                  background: '#b8e6fe',
                  color: '#1f2937',
                },
              },
            }"
          ></Column>
          <Column
            field="EMPID"
            header="รหัสพนักงาน"
            class="text-[16px]"
            :pt="{
              headerCell: {
                class: 'header-cell text-lg font-semibold',
                id: 'z',
                prime: 'vue',
                style: {
                  background: '#b8e6fe',
                  color: '#1f2937',
                },
              },
            }"
          ></Column>
          <Column
            field="EMPNM"
            header="ชื่อ-สกุลพนักงาน"
            class="text-[16px]"
            :pt="{
              headerCell: {
                class: 'header-cell text-lg font-semibold',
                id: 'z',
                prime: 'vue',
                style: {
                  background: '#b8e6fe',
                  color: '#1f2937',
                },
              },
            }"
          ></Column>
          <Column
            field="DAYS"
            header="จำนวนวันที่ทำความสะอาดครั้งล่าสุด"
            class="text-[16px]"
            :pt="{
              headerCell: {
                class: 'header-cell text-lg font-semibold',
                id: 'z',
                prime: 'vue',
                style: {
                  background: '#b8e6fe',
                  color: '#1f2937',
                },
              },
            }"
          ></Column>
          <!-- <Column
            field="TSCLEANH_LSTDT"
            header="วันที่ทำความสะอาดล่าสุด"
            class="text-[16px]"
            :pt="{
              headerCell: {
                class: 'header-cell text-lg font-semibold',
                id: 'z',
                prime: 'vue',
                style: {
                  background: '#b8e6fe',
                  color: '#1f2937',
                },
              },
            }"
          >
            <template #body="{ data }">
              <span v-if="data.TSCLEANH_LSTDT !== null && data.DAYS <= 7">
                <Tag
                  severity="success"
                  :value="formattdated(data.TSCLEANH_LSTDT)"
                  class="text-[16px]"
                >
                </Tag>
              </span>
              <span v-else>
                <Tag
                  severity="danger"
                  value="ยังไม่มีการทำความสะอาดรองเท้า"
                  class="text-[16px]"
                ></Tag>
              </span>
            </template>
          </Column> -->
        </DataTable>
      </div>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>
</template>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import SectionBarChart from "./SectionBarChart.vue";

const shift = ref("");
const get_sec = ref([]);
const details_cln = ref([]);

var currentTime = dayjs().format("HH:mm");

if (currentTime > "19:59" && currentTime <= "07:59") {
  shift.value = "NIGHT";
} else {
  shift.value = "DAYS";
}

const fetchSection = async () => {
  try {
    await axios.get("/Cleaning_Shoes/api/get-procedure-clean").then((res) => {
      //   get_sec.value = res.data;
      const groupedData = res.data.reduce((acc, item) => {
        if (!acc[item.SECTCD]) {
          acc[item.SECTCD] = [];
        }
        acc[item.SECTCD].push(item);
        return acc;
      }, {});
      get_sec.value = groupedData;
      console.log(get_sec.value);
    });
  } catch (error) {
    console.error("Error fetching section data:", error);
  }
};

const showDetailsBySection = (sectionCode) => {
  const details = document.getElementById("details");
  details.showModal();
  console.log("Section Code:", sectionCode);

  // Fetch and display the details for the selected section
  axios
    .get(`/Cleaning_Shoes/api/get-section-details/${sectionCode}`)
    // .get(`/Cleaning_Shoes/api/get-section-details/${sectionCode}`)
    .then((response) => {
      // Assuming response.data contains the details for the section
      // You can update the modal content here with the fetched data
      details_cln.value = response.data;
      console.log("Section Details:", response.data);
      // For example, you can set a data property to hold the details and display them in the modal
    })
    .catch((error) => {
      console.error("Error fetching section details:", error);
    });
};
const formattdated = (item) => {
  const date = dayjs(item).format("YYYY-MM-DD HH:mm:ss");
  return date;
};
onMounted(() => {
  fetchSection();
});
</script>
