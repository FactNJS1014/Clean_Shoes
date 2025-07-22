<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $get_cleanl = DB::table('TSCLEANL_TBL as tscl')
            ->join('VEMPLOYEE_TBL as emp', 'tscl.TSCLEANL_EMPNO', '=', 'emp.VEMPLOYEE_ID')
            ->select('tscl.*', 'emp.VEMPLOYEE_THFNAME', 'emp.VEMPLOYEE_THLNAME', 'emp.VEMPLOYEE_THPREFIX', 'emp.VEMPLOYEE_SECTION')

            ->get();

        return response()->json($get_cleanl);
    }

    /**
     * TODO: ดึงข้อมูลจาก Procedure เป็นการแสดงข้อมูลเกินระยะเวลาตามวันที่กำหนด
     * * 2025-07-21
     */

    public function GetProcedureCleanOfDay()
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare('EXEC dbo.PSCLEAN_LIST_OVERDAY');
        $stmt->execute();

        $allResults = [];

        do {
            // สำคัญ!! → เช็คว่ามีผลลัพธ์หรือไม่
            if ($stmt->columnCount() > 0) {
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $allResults[] = $rows;
                }
            }
        } while ($stmt->nextRowset());

        if (empty($allResults)) {
            return response()->json(['message' => 'No
            data returned.']);
        }

        return response()->json($allResults);
    }

    public function FilterCleanPSC(Request $request)
    {
        $section = $request->input('sec'); // เช่น A01

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare('EXEC dbo.PSCLEAN_LIST_OVERDAY');
        $stmt->execute();

        $filter_db_clean = [];

        do {
            if ($stmt->columnCount() > 0) {
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $filter_db_clean = array_merge($filter_db_clean, $rows);
                }
            }
        } while ($stmt->nextRowset());

        // กรองข้อมูลตาม CLEAN_SECTION
        if ($section) {
            $filter_db_clean = array_filter($filter_db_clean, function ($row) use ($section) {
                return isset($row['SECTCD']) && $row['SECTCD'] === $section;
            });
            $filter_db_clean = array_values($filter_db_clean); // reset array index
        }

        if (empty($filter_db_clean)) {
            return response()->json(['message' => 'No data returned.']);
        }

        return response()->json($filter_db_clean);
    }

    public function JoinDataCleanUser(){
        $query = DB::select('EXEC dbo.PSCLEAN_LIST_OVERDAY ');
        return response()->json($query);
    }

    
}
