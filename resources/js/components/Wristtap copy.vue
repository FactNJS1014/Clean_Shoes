<template>
  <div class="flex items-center justify-center">
    <div class="w-[100%] max-w-7xl shadow-sm card bg-white mt-5">
      <h2 class="p-4 text-2xl font-semibold text-center text-gray-800">
        แบบฟอร์มบันทึกผู้ที่ได้รับการยกเว้นในการ Check ESD แต่ละประเภท
      </h2>

      <div class="flex items-center justify-center w-[100%]">
        <form @submit.prevent="handleSubmit" class="w-full p-6 text-lg">
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="flex flex-col space-y-2">
              <label for="employeeCode" class="font-medium text-gray-700"
                >Employee Code (ระบุรหัสพนักงาน)</label
              >
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

            <div class="flex flex-col space-y-2">
              <label for="esdType" class="font-medium text-gray-700"
                >ESD Type (ประเภทของ ESD)</label
              >
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

            <div class="flex flex-col w-full col-span-1 space-y-2 md:col-span-2">
              <label for="remark" class="font-medium text-gray-700"
                >Remark (เหตุผล)</label
              >
              <input
                id="remark"
                v-model="esd.remark"
                class="w-full input bg-white text-black border border-black text-[20px] focus:outline-none"
                :class="{ 'border-red-500': errors.remark }"
                placeholder="ระบุเหตุผล"
              />
              <span v-if="errors.remark" class="mt-1 text-red-500 text-md">
                {{ errors.remark }}
              </span>
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
  <div class="flex items-center justify-center p-4">
    <div class="w-[100%] max-w-7xl shadow-sm card bg-white mt-5 p-2">
      <h2 class="mt-5 text-2xl font-semibold text-center text-black">
        ตารางผู้ที่ได้รับการยกเว้น Check ESD แต่ละประเภท
      </h2>
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
      <div class="mt-4 max-h-[400px] overflow-auto border border-gray-300 rounded">
        <table class="table w-full min-w-[2000px]">
          <!-- head -->
          <thead class="sticky top-0 z-10 text-lg">
            <tr class="text-black bg-amber-300 text-[20px] text-center">
              <th>รหัสพนักงาน</th>
              <th>ชื่อ-สกุลพนักงาน</th>
              <th>ประเภท ESD</th>
              <th>แผนก</th>
              <th>หมายเหตุ</th>
              <th>Action</th>
            </tr>
            <!-- <tr class="bg-gray-200 ">
                            <th><input type="text" placeholder="Search Employee Code"
                                    class="input input-neutral text-[16px]" @input="SearchFilterESD"
                                    v-model="esd_empid" /></th>
                            <th><input type="text" placeholder="Search Employee Name"
                                    class="input input-neutral text-[16px]" @input="SearchFilterESD" /></th>
                            <th><input type="text" placeholder="Search ESD Type" class="input input-neutral text-[16px]"
                                    @input="SearchFilterESD" /></th>
                            <th><input type="text" placeholder="Search Remark" class="input input-neutral text-[16px]"
                                    @input="SearchFilterESD" /></th>
                        </tr> -->
          </thead>
          <tbody>
            <tr v-for="(esd_exc, index) in esd_db" :key="index" class="text-center">
              <td class="text-[18px] font-semibold">
                <div class="badge badge-info p-5">{{ esd_exc.TExcludeEsd_EmpCd }}</div>
              </td>
              <td class="text-[18px] font-semibold text-gray-900">
                {{ esd_exc.VEMPLOYEE_THPREFIX }}{{ esd_exc.VEMPLOYEE_THFNAME }}&nbsp;{{
                  esd_exc.VEMPLOYEE_THLNAME
                }}
              </td>
              <td class="text-[18px] font-semibold">
                <div class="badge badge-outline badge-warning p-5">
                  {{ esd_exc.TExcludeEsd_EsdTy }}
                </div>
              </td>
              <td class="text-[18px] font-semibold text-gray-900">
                {{ esd_exc.VEMPLOYEE_SECTION }}
              </td>
              <td
                class="text-[18px] font-semibold"
                v-if="esd_exc.TExcludeEsd_Remk !== null"
              >
                {{ esd_exc.TExcludeEsd_Remk }}
              </td>
              <td class="text-[18px] font-semibold text-error" v-else>ยังไม่มีข้อมูล</td>

              <td>
                <button
                  class="text[16px] btn btn-error w-[100px]"
                  @click="
                    DeleteData(esd_exc.TExcludeEsd_EmpCd, esd_exc.TExcludeEsd_EsdTy)
                  "
                >
                  ลบข้อมูล
                </button>
              </td>
            </tr>
          </tbody>
        </table>
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
]);
const esd_sec = ref([]);

// const esd_sec = ref([
//     {sec_name: 'Auto Mount-Direct-AM', sec_nic: 'AM' },
//     {sec_name: 'Production Car Audio-Direct', sec_nic: 'PROD-CA' }
//     // {sec_name: 'Manual Mount Technology-Direct-MT', sec_nic: 'MT' },
//     // {sec_name: 'Quality Control-Direct-MT', sec_nic: 'QC-MT' },
//     // {sec_name: 'Material Control Section', sec_nic: 'MATERIAL' },
//     // {sec_name: 'Production Planning (EMS)', sec_nic: 'PP-EMS' },
//     // {sec_name: 'Auto Mount Engineer-AME', sec_nic: 'AME' },
//     // {sec_name: 'Production Engineering Section (EMS)', sec_nic: 'PE-EMS' },
//     // {sec_name: 'Safety Health & Environment Section', sec_nic: 'SAF' },
//     // { sec_name: 'Quality Assurance-Indirect', sec_nic: 'QA' },
//     // { sec_name: 'Information Technology Section', sec_nic: 'SYSTEM' },
//     // { sec_name: 'Quality Planning Section', sec_nic: 'QP' },
//     // { sec_name: 'Logistic', sec_nic: 'LC' },
//     // { sec_name: 'Production Engineering Section (Car Audio)', sec_nic: 'PE-CA' },
//     // { sec_name: 'Quality Control-Indirect', sec_nic: 'QC-INDIRECT' },
//     // { sec_name: 'Purchase Section', sec_nic: 'PURCHASE' },
//     // { sec_name: 'Manufacturing Engineering Section (Car Audio)', sec_nic: 'ME' }
// ])

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
  if (!remark.value) {
    errors.value.remark = "กรุณาระบุเหตุผล";
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
