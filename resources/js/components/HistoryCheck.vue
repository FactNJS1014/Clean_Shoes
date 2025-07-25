<template>
  <div class="flex justify-center gap-1 p-2">
    <div class="w-full shadow-sm card bg-base-100 card-xs">
      <div class="card-body">
        <p class="text-[18px] font-medium text-center">
          <v-icon name="bi-table" scale="1.3" fill="#156064"></v-icon>
          ตารางแสดงข้อมูลประวัติการทำความสะอาดรองเท้า
        </p>
        <div class="flex items-center justify-end p-2">
          <DatePicker
            v-model="date"
            date-format="yy-mm-dd"
            showIcon
            placeholder="เลือกวันที่แสดงข้อมูล"
            @update:modelValue="SearchDate"
          />
        </div>

        <div class="mt-3">
          <DataTable
            :value="cln_data"
            tableStyle="min-width: 100px"
            showGridlines
            paginator
            :rows="7"
            :rowsPerPageOptions="[7,10, 20, 50]"
          >
            <Column
              field="TSCLEANL_EMPNO"
              header="รหัสพนักงาน"
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
            <Column header="ชื่อ-นามสกุล" class="text-[16px]" :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                         background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }">
              <template #body="{ data }">
                {{ data.VEMPLOYEE_THPREFIX }}{{ data.VEMPLOYEE_THFNAME }}&nbsp;
                {{ data.VEMPLOYEE_THLNAME }}
              </template>
            </Column>
            <Column field="VEMPLOYEE_SECTION" header="แผนก" class="text-[16px]" :pt="{
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
              field="count"
              header="จำนวนครั้งในการทำความสะอาดรองเท้า"
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
            <Column header="วัน-เวลาทำความสะอาด" class="text-[16px]" :pt="{
                    headerCell: {
                      class:'header-cell text-lg font-semibold',
                      id:'z',
                      prime: 'vue',
                      style: {
                         background: '#b8e6fe',
                        color: '#1f2937',
                      }

                    },
                  }">
              <template #body="{ data }">
                {{ formatt_Date(data.TSCLEANL_LSTDT) }}
              </template>
            </Column>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import dayjs from "dayjs";

const cleaning_user = ref([]);
const date = ref();
const cln_data = ref([]);

const fetch_cleaning_user_all = () => {
  axios.get("/Cleaning_Shoes/api/data-cleaning-all").then((res) => {
    console.log(res.data);
    cleaning_user.value = res.data;
  });
};

const SearchDate = () => {
  const date_filter = dayjs(date.value).format("YYYY-MM-DD");
  console.log(date_filter);

  axios
    .get("/Cleaning_Shoes/api/filter-date-check", {
      params: {
        date: date_filter,
      },
    })
    .then((res) => {
      // 🔁 ยุบข้อมูลซ้ำแบบเดียวกับ fetch_cleanl
      const merge = {};
      res.data.forEach((d) => {
        const date_formatt = dayjs(d.TSCLEANL_LSTDT).format("YYYY-MM-DD");
        const key = `${d.TSCLEANL_EMPNO}_${date_formatt}`;

        if (merge[key]) {
          merge[key].count += 1;
        } else {
          merge[key] = { ...d, count: 1 };
        }
      });

      const result = Object.values(merge);
      cln_data.value = result;
    });
};

const fetch_cleanl = () => {
  axios.get("/Cleaning_Shoes/api/get-cleanl").then((res) => {
    // cln_data.value = res.data;
    // console.log(cln_data.value);
    const merge = {};

    res.data.forEach((d) => {
      const date_formatt = dayjs(d.TSCLEANL_LSTDT).format("YYYY-MM-DD");
      const key = `${d.TSCLEANL_EMPNO}_${date_formatt}`;

      console.log(key);
      if (merge[key]) {
        merge[key].count += 1;
      } else {
        merge[key] = { ...d, count: 1 };
      }
    });

    const result = Object.values(merge);
    cln_data.value = result;
    console.log(cln_data.value);
  });
};

const formatt_Date = (value) => {
  const date_js = dayjs(value).format("YYYY-MM-DD HH:mm:ss");
  return date_js;
};

onMounted(() => {
  fetch_cleaning_user_all(), fetch_cleanl();
});
</script>
