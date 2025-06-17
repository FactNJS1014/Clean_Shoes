<template>
    <div class="flex items-center justify-center">
        <div class="w-[90%] max-w-5xl shadow-sm card bg-white mt-5">
            <h2 class="p-4 text-2xl font-semibold text-center text-gray-800">
                แบบฟอร์มบันทึกผู้ที่ได้รับการยกเว้นในการ Check ESD
            </h2>

            <div class="flex items-center justify-center w-[100%]">
                <form @submit.prevent="handleSubmit" class="w-full p-6 text-lg">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 ">
                        <div class="flex flex-col space-y-2">
                            <label for="employeeCode" class="font-medium text-gray-700">Employee Code
                                (ระบุรหัสพนักงาน)</label>
                            <input type="text" id="employeeCode" v-model="employeeCode"
                                class="w-full input input-info text-[20px] focus:outline-none" :class="{ 'border-red-500': errors.employeeCode }"
                                placeholder="ระบุรหัสพนักงาน" />
                            <span v-if="errors.employeeCode" class="mt-1 text-red-500 text-md">
                                {{ errors.employeeCode }}
                            </span>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label for="esdType" class="font-medium text-gray-700">ESD Type (ประเภทของ ESD)</label>
                            <select id="esdType" v-model="esdType" class="w-full select select-info text-[20px]"
                                :class="{ 'border-red-500': errors.esdType }">
                                <option value="" disabled>-- เลือกประเภท ESD --</option>
                                <option v-for="type in esd_types" :key="type.id" :value="type.name">
                                    {{ type.name }}
                                </option>
                            </select>
                            <span v-if="errors.esdType" class="mt-1 text-red-500 text-md">
                                {{ errors.esdType }}
                            </span>
                        </div>

                        <div class="flex flex-col w-full col-span-1 space-y-2 md:col-span-2">
                            <label for="remark" class="font-medium text-gray-700">Remark (เหตุผล)</label>
                            <input id="remark" v-model="remark" class="w-full input input-info text-[20px]"
                                :class="{ 'border-red-500': errors.remark }" placeholder="ระบุเหตุผล">
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
</template>
<script setup>
import { ref } from 'vue';

// Reactive variables for form inputs
const employeeCode = ref('');
const esdType = ref('');
const remark = ref('');

// Sample data for ESD types dropdown
const esd_types = ref([
  { id: 1, name: 'WRIST' },
  { id: 2, name: 'SHOES' }
]);

// Basic validation state
const errors = ref({});

// Function to handle form submission
const handleSubmit = () => {
  errors.value = {}; // Clear previous errors

  // Simple validation
  if (!employeeCode.value) {
    errors.value.employeeCode = 'กรุณาระบุรหัสพนักงาน';
  }
  if (!esdType.value) {
    errors.value.esdType = 'กรุณาเลือกประเภท ESD';
  }
  if (!remark.value) {
    errors.value.remark = 'กรุณาระบุเหตุผล';
  }

  // If there are no errors, proceed with submission
  if (Object.keys(errors.value).length === 0) {
    console.log('Form Submitted:', {
      employeeCode: employeeCode.value,
      esdType: esdType.value,
      remark: remark.value,
    });
    alert('บันทึกข้อมูลสำเร็จ!\nรหัสพนักงาน: ' + employeeCode.value + '\nประเภท ESD: ' + esdType.value + '\nเหตุผล: ' + remark.value);

    // Optionally, reset the form after successful submission
    employeeCode.value = '';
    esdType.value = '';
    remark.value = '';
  } else {
    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
  }
};
</script>
