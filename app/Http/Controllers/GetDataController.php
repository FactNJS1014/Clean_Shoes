<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// Ensure this model exists if you want to use it

class GetDataController extends Controller
{
    /**
     * TODO: สิ่งที่เรียกข้อมูลขึ้นมาโชว์
     * ? TExcludeEsd_EmpCd = รหัสพนักงาน
     * ? 'emp.VEMPLOYEE_THFNAME','emp.VEMPLOYEE_THLNAME' ดึง
     *
     */
    public function DataCheck()
    {
        try {
            $data = DB::table('TExcludeEsd_Tbl as esdt')
                ->join('VEMPLOYEE_TBL as emp', 'esdt.TExcludeEsd_EmpCd', '=', 'emp.VEMPLOYEE_ID')
                ->select('esdt.*', 'emp.VEMPLOYEE_THFNAME', 'emp.VEMPLOYEE_THLNAME', 'emp.VEMPLOYEE_THPREFIX', 'emp.VEMPLOYEE_SECTION')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * TODO: filter table from empid and section
     * ? ฟิลเตอร์ข้อมูลรหัสพนักงานและแผนก
     */

    public function SearchCheck(Request $request)
    {
        $searchEmpid = $request->input('empid');
        $searchSection = $request->input('section');

        $query = DB::table('TExcludeEsd_Tbl as esdt')
            ->join('VEMPLOYEE_TBL as emp', 'esdt.TExcludeEsd_EmpCd', '=', 'emp.VEMPLOYEE_ID')
            ->select('esdt.*', 'emp.VEMPLOYEE_THFNAME', 'emp.VEMPLOYEE_THLNAME', 'emp.VEMPLOYEE_SECTION');

        if (!empty($searchEmpid)) {
            $query->where('esdt.TExcludeEsd_EmpCd', 'LIKE', '%' . $searchEmpid . '%');
        }
        if (!empty($searchSection)) {
            $query->where('emp.VEMPLOYEE_SECTION', $searchSection);
        }

        $result = $query->get();
        return response()->json($result);
    }

    /**
     * TODO: Get Section to select in Writtap.vue
     * ? ดึงข้อมูลแผนก
     */

    public function GetSection()
    {
        $db_employee = DB::table('VEMPLOYEE_TBL')
            ->select('VEMPLOYEE_SECTION')
            ->distinct()
            ->get();

        return response()->json($db_employee);
    }

    public function GetCleaningCheckAll()
    {
        $db_cleaning_all = DB::table('TSCLEANH_TBL as tsch')
            ->join('VEMPLOYEE_TBL as emp', 'tsch.TSCLEANH_EMPNO', '=', 'emp.VEMPLOYEE_ID')
            ->select('tsch.*', 'emp.VEMPLOYEE_THFNAME', 'emp.VEMPLOYEE_THLNAME', 'emp.VEMPLOYEE_THPREFIX', 'emp.VEMPLOYEE_SECTION')
            ->get();

        return response()->json($db_cleaning_all);
    }

    /**
     * TODO: เลือกวันที่แสดงผลจากฐานข้อมูล
     * * 2025-07-21
     */

    public function FilterDateCheck(Request $request)
    {

        $date_filter = $request->input('date');

        $filter_db = DB::table('TSCLEANL_TBL as tscl')
            ->join('VEMPLOYEE_TBL as emp', 'tscl.TSCLEANL_EMPNO', '=', 'emp.VEMPLOYEE_ID')
            ->select('tscl.*', 'emp.VEMPLOYEE_THFNAME', 'emp.VEMPLOYEE_THLNAME', 'emp.VEMPLOYEE_THPREFIX', 'emp.VEMPLOYEE_SECTION')

            ->whereDate('tscl.TSCLEANL_LSTDT', $date_filter)
            ->get();



        return response()->json($filter_db);
    }

    /**
     * TODO: ดึงข้อมูลพนักงานที่ทำความสะอาดในแต่ละครั้ง
     * * 2025-07-21
     */

    public function GetCleanL()
    {
        $now = Carbon::now()->format('Y-m-d');
        $get_cleanl = DB::table('TSCLEANL_TBL as tscl')
            ->join('VEMPLOYEE_TBL as emp', 'tscl.TSCLEANL_EMPNO', '=', 'emp.VEMPLOYEE_ID')
            ->select('tscl.*', 'emp.VEMPLOYEE_THFNAME', 'emp.VEMPLOYEE_THLNAME', 'emp.VEMPLOYEE_THPREFIX', 'emp.VEMPLOYEE_SECTION')
            ->whereDate('tscl.TSCLEANL_LSTDT', $now)
            ->get();

        return response()->json($get_cleanl);
    }

    /**
     * TODO: ดึงข้อมูลจาก Procedure เป็นการแสดงข้อมูลเกินระยะเวลาตามวันที่กำหนด
     * * 2025-07-21
     */

    public function GetProcedureCleanOfDay()
    {
        // เรียก stored procedure และได้รายการ EMPID
        $procData = DB::select('EXEC dbo.PSCLEAN_LIST_OVERDAY @DAYS = 0');

        // ดึงข้อมูลจาก TSCLEANH_TBL โดยใช้ EMPNO เป็น key เพื่อ join
        $userData = DB::table('TSCLEANH_TBL')->select('TSCLEANH_EMPNO', 'TSCLEANH_LSTDT')->get()->keyBy('TSCLEANH_EMPNO');

        $merged = [];

        foreach ($procData as $row) {
            $row = (array) $row;
            $empId = $row['EMPID'] ?? null; // field จาก procedure

            if ($empId && isset($userData[$empId])) {
                // ถ้ามีข้อมูลใน table ให้เพิ่ม field LSTDT เข้าไป
                $row['TSCLEANH_LSTDT'] = $userData[$empId]->TSCLEANH_LSTDT ?? null;
            } else {
                // ถ้าไม่มี match ให้ใส่ค่า null
                $row['TSCLEANH_LSTDT'] = null;
            }

            $merged[] = $row;
        }

        return response()->json($merged);
    }

    public function FilterCleanPSC(Request $request)
    {
        $sect = $request->input('sec'); // รับ sect จาก frontend

        // ดึงข้อมูลจาก stored procedure
        $procData = DB::select('EXEC dbo.PSCLEAN_LIST_OVERDAY @DAYS = 0');

        // ดึงข้อมูลจาก table
        $userData = DB::table('TSCLEANH_TBL')
            ->select('TSCLEANH_EMPNO', 'TSCLEANH_LSTDT')
            ->get()
            ->keyBy('TSCLEANH_EMPNO');

        $merged = [];

        foreach ($procData as $row) {
            $row = (array) $row;

            // Filter ด้วย SECTCD หลังจาก query จาก procedure
            if (isset($row['SECTCD']) && $row['SECTCD'] != $sect) {
                continue; // ข้ามถ้าไม่ตรง sect ที่ต้องการ
            }

            $empId = $row['EMPID'] ?? null;

            if ($empId && isset($userData[$empId])) {
                $row['TSCLEANH_LSTDT'] = $userData[$empId]->TSCLEANH_LSTDT ?? null;
            } else {
                $row['TSCLEANH_LSTDT'] = null;
            }

            $merged[] = $row;
        }

        return response()->json($merged);
    }

    public function JoinDataCleanUser()
    {
        // เรียก stored procedure และได้รายการ EMPID
        $procData = DB::select('EXEC dbo.PSCLEAN_LIST_OVERDAY @DAYS = 0');

        // ดึงข้อมูลจาก TSCLEANH_TBL โดยใช้ EMPNO เป็น key เพื่อ join
        $userData = DB::table('TSCLEANH_TBL')->select('TSCLEANH_EMPNO', 'TSCLEANH_LSTDT')->get()->keyBy('TSCLEANH_EMPNO');

        $merged = [];

        foreach ($procData as $row) {
            $row = (array) $row;
            $empId = $row['EMPID'] ?? null; // field จาก procedure

            if ($empId && isset($userData[$empId])) {
                // ถ้ามีข้อมูลใน table ให้เพิ่ม field LSTDT เข้าไป
                $row['TSCLEANH_LSTDT'] = $userData[$empId]->TSCLEANH_LSTDT ?? null;
            } else {
                // ถ้าไม่มี match ให้ใส่ค่า null
                $row['TSCLEANH_LSTDT'] = null;
            }

            $merged[] = $row;
        }

        return response()->json($merged);
    }
}
