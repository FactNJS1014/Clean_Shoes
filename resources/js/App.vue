<template>
    <div class="flex items-center justify-center">
        <div class="w-[90%] max-w-7xl shadow-sm card bg-white mt-5">
            <h2 class="p-4 text-2xl font-semibold text-center text-gray-800">
                แบบฟอร์มบันทึกผู้ที่ได้รับการยกเว้นในการ Check ESD
            </h2>

            <div class="flex items-center justify-center w-[100%]">
                <form @submit.prevent="handleSubmit" class="w-full p-6 text-lg">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 ">
                        <div class="flex flex-col space-y-2">
                            <label for="employeeCode" class="font-medium text-gray-700">Employee Code
                                (ระบุรหัสพนักงาน)</label>
                            <input type="text" id="employeeCode" v-model="esd.employeeCode"
                                class="w-full input input-info text-[20px] focus:outline-none"
                                :class="{ 'border-red-500': errors.employeeCode }" placeholder="ระบุรหัสพนักงาน" />
                            <span v-if="errors.employeeCode" class="mt-1 text-red-500 text-md">
                                {{ errors.employeeCode }}
                            </span>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label for="esdType" class="font-medium text-gray-700">ESD Type (ประเภทของ ESD)</label>
                            <select id="esdType" v-model="esd.esdType" class="w-full select select-info text-[20px]"
                                :class="{ 'border-red-500': errors.esdType }">
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
                            <label for="remark" class="font-medium text-gray-700">Remark (เหตุผล)</label>
                            <input id="remark" v-model="esd.remark" class="w-full input input-info text-[20px]"
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
    <div class="flex items-center justify-center p-4">
        <div class="w-[90%] max-w-7xl shadow-sm card bg-white mt-5 p-2">
            <h2 class="text-2xl font-semibold text-center">ตารางผู้ที่ได้รับการยกเว้น Check ESD</h2>
            <div class="mt-5 overflow-y-auto border rounded-box border-base-content/5 bg-base-100 max-h-[400px]">
                <table class="table w-full">
                    <!-- head -->
                    <thead class="sticky top-0 z-10 text-lg">
                        <tr class="text-black bg-amber-300 text-[20px] text-center">
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>ESD Type Not Check</th>
                            <th>ESD Remark</th>
                        </tr>
                        <tr class="bg-gray-200 ">
                            <th><input type="text" placeholder="Search Employee Code"
                                    class="input input-neutral text-[16px]" @input="SearchFilterESD"
                                    v-model="esd_empid" /></th>
                            <th><input type="text" placeholder="Search Employee Name"
                                    class="input input-neutral text-[16px]" @input="SearchFilterESD" /></th>
                            <th><input type="text" placeholder="Search ESD Type" class="input input-neutral text-[16px]"
                                    @input="SearchFilterESD" /></th>
                            <th><input type="text" placeholder="Search Remark" class="input input-neutral text-[16px]"
                                    @input="SearchFilterESD" /></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="esd_exc, index in esd_db" :key="index" class="text-center">
                            <td class="text-[18px] font-semibold">{{ esd_exc.TExcludeEsd_EmpCd }}</td>
                            <td class="text-[18px] font-semibold">{{ esd_exc.VEMPLOYEE_THFNAME }}&nbsp;{{
                                esd_exc.VEMPLOYEE_THLNAME }}</td>
                            <td class="text-[18px] font-semibold">{{ esd_exc.TExcludeEsd_EsdTy }}</td>
                            <td class="text-[18px] font-semibold" v-if="esd_exc.TExcludeEsd_Remk !== null">{{ esd_exc.TExcludeEsd_Remk }}</td>
                            <td class="text-[18px] font-semibold text-error" v-else>ไม่มีข้อมูล</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, ref, toRaw } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

// Reactive variables for form inputs
const esd = reactive({
    employeeCode: '',
    esdType: '',
    remark: ''
})
const esd_db = ref([])
const esd_empid = ref('')

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

        axios.post('/ESDCheck/insert-check',
            {
                esd_inst: toRaw(esd)
            },
            {
                headers: {
                    'Content-Type': 'application/json'
                }
            }
        ).then(res => {
            console.log(res.data)
            if (res.data.data) {
                Swal.fire({
                    title: 'บันทึกเสร็จสิ้น',
                    icon: 'success',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Optionally, reset the form after successful submission
                    employeeCode.value = '';
                    esdType.value = '';
                    remark.value = '';
                    location.reload()
                })
            } else {
                Swal.fire({
                    title: 'บันทึกไม่ผ่าน',
                    text: 'โปรดตรวจสอบข้อมูลก่อนบันทึก หรือแจ้งทาง IT',
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload()
                })
            }
        })




    } else {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
    }
};

const fetch_db_esd = () => {
    axios.get('/ESDCheck/api/data-check')
        .then(res => {
            const db = res.data;
            esd_db.value = db;
            console.log(esd_db)
        })


}
const SearchFilterESD = () => {

    axios.get('/ESDCheck/api/search-check', {
        params: {
            empid: esd_empid.value
        }
    }).then(res => {
        esd_db.value = res.data

    })
}

onMounted(() => {
    fetch_db_esd()
})
</script>
