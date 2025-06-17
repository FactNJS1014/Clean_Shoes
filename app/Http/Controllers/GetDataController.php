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
            ->join('VEMPLOYEE_TBL as emp','esdt.TExcludeEsd_EmpCd','=','emp.VEMPLOYEE_ID')
            ->select('esdt.*','emp.VEMPLOYEE_THFNAME','emp.VEMPLOYEE_THLNAME','emp.VEMPLOYEE_THPREFIX')
            ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function SearchCheck(Request $request){
        $searchEmpid = $request->input('empid');

        $query = DB::table('TExcludeEsd_Tbl as esdt')
            ->join('VEMPLOYEE_TBL as emp','esdt.TExcludeEsd_EmpCd','=','emp.VEMPLOYEE_ID')
            ->select('esdt.*','emp.VEMPLOYEE_THFNAME','emp.VEMPLOYEE_THLNAME');

        if(!empty($searchEmpid)){
            $query->where('esdt.TExcludeEsd_EmpCd' , 'LIKE','%'. $searchEmpid . '%');
        }

        $result = $query->get();
        return response()->json($result);

    }
}
