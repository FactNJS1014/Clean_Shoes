<template>
  <div class="flex flex-wrap justify-between p-4 gap-4">
    <!-- ฝั่งซ้าย -->
    <div class="w-full lg:w-[30%]">
      <div class="shadow-sm card bg-white mt-5">
        <h2 class="p-4 text-2xl font-medium text-center text-gray-800">
          แบบฟอร์มบันทึกผู้ที่ได้รับการยกเว้นในการ Check ESD แต่ละประเภท
        </h2>
        <div class="flex items-center justify-center w-full">
          <!-- ฟอร์ม -->
          <form @submit.prevent="handleSubmit" class="w-full p-6 text-lg">
            <div class="grid grid-cols-1 space-y-3">
              <!-- Employee Code -->
              <div class="flex flex-col space-y-2">
                <input
                  type="text"
                  id="employeeCode"
                  v-model="esd.employeeCode"
                  class="w-full input bg-white text-black border border-black text-[20px] focus:outline-none"
                  :class="{ 'border-red-500': errors.employeeCode }"
                  placeholder="ระบุรหัสพนักงาน"
                />
                <span v-if="errors.employeeCode" class="mt-1 text-red-500 text-md">
                  {{ errors.employeeCode }}
                </span>
              </div>

              <!-- ESD Type -->
              <div class="flex flex-col space-y-2">
                <select
                  id="esdType"
                  v-model="esd.esdType"
                  class="w-full select bg-white text-black border border-black text-[20px] focus:outline-none"
                  :class="{ 'border-red-500': errors.esdType }"
                >
                  <option value="" disabled selected>-- เลือกประเภท ESD --</option>
                  <option v-for="type in esd_types" :key="type.id" :value="type.name">
                    {{ type.name }}
                  </option>
                </select>
                <span v-if="errors.esdType" class="mt-1 text-red-500 text-md">
                  {{ errors.esdType }}
                </span>
              </div>

              <!-- Remark -->
              <div class="flex flex-col w-full">
                <input
                  id="remark"
                  v-model="esd.remark"
                  class="w-full input bg-white text-black border border-black text-[20px] focus:outline-none"
                  :class="{ 'border-red-500': errors.remark }"
                  placeholder="ระบุเหตุผล"
                />
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button type="submit" class="px-8 py-2 btn btn-info text-[18px]">
                <v-icon name="md-saveas" scale="1.2"></v-icon>บันทึกข้อมูล
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ฝั่งขวา -->
    <div class="w-full lg:w-[67%]">
      <div class="shadow-sm card bg-base-100 mt-5 p-4">
        <p class="text-2xl font-medium text-center">
          ตารางผู้ที่ได้รับการยกเว้น Check ESD แต่ละประเภท
        </p>

        <div class="flex items-center justify-end mt-4">
          <input
            type="text"
            placeholder="ค้นหารหัสพนักงาน"
            class="input bg-white text-black border border-black text-[16px] focus:outline-none"
            @input="SearchFilterESD"
            v-model="esd_empid"
          />
          <select
            class="input bg-white text-black border border-black text-[16px] focus:outline-none ms-2"
            @change="SearchFilterESD"
            v-model="esd_section"
          >
            <option value="" disabled selected>--เลือกแผนกที่ต้องการ--</option>
            <option
              v-for="(sec, index) in esd_sec"
              :key="index"
              :value="sec.VEMPLOYEE_SECTION"
            >
              {{ sec.VEMPLOYEE_SECTION }}
            </option>
          </select>
        </div>

        <DataTable
          :value="esd_db"
          tableStyle="min-width: 50rem"
          paginator
          showGridlines
          :rows="5"
          :rowsPerPageOptions="[5, 10, 20, 50]"
          class="mt-3"
        >
          <Column field="TExcludeEsd_EmpCd" header="รหัสพนักงาน"></Column>
          <Column header="ชื่อ-นามสกุล">
            <template #body="slotProps">
              <span class="text-[16px]">
                {{ slotProps.data.VEMPLOYEE_THPREFIX
                }}{{ slotProps.data.VEMPLOYEE_THFNAME }}&nbsp;{{
                  slotProps.data.VEMPLOYEE_THLNAME
                }}
              </span>
            </template>
          </Column>
          <Column header="ประเภทตรวจสอบ">
            <template #body="slotProps">
              <Tag :value="slotProps.data.TExcludeEsd_EsdTy" severity="warn"></Tag>
            </template>
          </Column>
          <Column field="VEMPLOYEE_SECTION" header="แผนก"></Column>
          <Column header="ชื่อ-นามสกุล">
            <template #body="slotProps">
              <button
                class="btn btn-error"
                @click="
                  DeleteData(
                    slotProps.data.TExcludeEsd_EmpCd,
                    slotProps.data.TExcludeEsd_EsdTy
                  )
                "
              >
                Delete
              </button>
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, toRaw } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

// Reactive variables for form inputs
const esd = reactive({
  employeeCode: "",
  esdType: "",
  remark: "",
});
const esd_db = ref([]);
const esd_empid = ref("");
const esd_section = ref("");

// Sample data for ESD types dropdown
const esd_types = ref([
  { id: 1, name: "WRIST" },
  { id: 2, name: "SHOES" },
  { id: 3, name: "CLEAN" },
]);
const esd_sec = ref([]);

// Basic validation state
const errors = ref({});

// Function to handle form submission
const handleSubmit = () => {
  errors.value = {}; // Clear previous errors

  // Simple validation
  if (!employeeCode.value) {
    errors.value.employeeCode = "กรุณาระบุรหัสพนักงาน";
  }
  if (!esdType.value) {
    errors.value.esdType = "กรุณาเลือกประเภท ESD";
  }

  // If there are no errors, proceed with submission
  if (Object.keys(errors.value).length === 0) {
    console.log("Form Submitted:", {
      employeeCode: employeeCode.value,
      esdType: esdType.value,
      remark: remark.value,
    });

    axios
      .post(
        "/CheckESD/insert-check",
        {
          esd_inst: toRaw(esd),
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
      .then((res) => {
        console.log(res.data);
        if (res.data.status === "Insert" && res.data.data) {
          Swal.fire({
            title: "บันทึกเสร็จสิ้น",
            icon: "success",
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            // Optionally, reset the form after successful submission
            employeeCode.value = "";
            esdType.value = "";
            remark.value = "";
            location.reload();
          });
        } else if (res.data.status === "IsHave") {
          Swal.fire({
            title: "มีข้อมูลอยู่แล้ว",
            icon: "warning",
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            title: "บันทึกไม่ผ่าน",
            text: "โปรดตรวจสอบข้อมูลก่อนบันทึก หรือแจ้งทาง IT",
            icon: "error",
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            location.reload();
          });
        }
      });
  } else {
    Swal.fire({
      title: "กรุณากรอกให้ครบถ้วน",
      icon: "error",
      showCancelButton: false,
      showConfirmButton: false,
      timer: 1500,
    });
  }
};

const fetch_db_esd = () => {
  axios.get("/CheckESD/api/data-check").then((res) => {
    const db = res.data;
    esd_db.value = db;
    console.log(esd_db);
  });
};
const SearchFilterESD = () => {
  console.log(esd_section.value);

  axios
    .get("/CheckESD/api/search-check", {
      params: {
        empid: esd_empid.value,
        section: esd_section.value,
      },
    })
    .then((res) => {
      esd_db.value = res.data;
    });
};

const DeleteData = (code, type) => {
  console.log(code);
  console.log(type);
  axios
    .post(
      "/CheckESD/delete-check",
      {
        code: code,
        type: type,
      },
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    )
    .then((res) => {
      console.log(res.data);
      if (res.data) {
        Swal.fire({
          title: "ลบข้อมูลเสร็จสิ้น",
          icon: "success",
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        }).then(() => {
          location.reload();
        });
      } else {
        Swal.fire({
          title: "ลบไม่ผ่าน",
          icon: "error",
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });
      }
    });
};

const fetch_db_sec = () => {
  axios.get("/CheckESD/api/data-sec").then((res) => {
    esd_sec.value = res.data;
    console.log(esd_sec.value);
  });
};

onMounted(() => {
  fetch_db_esd(), fetch_db_sec();
});
</script>
