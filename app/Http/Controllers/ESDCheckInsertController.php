<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\TExcludeEsd;

class ESDCheckInsertController extends Controller
{
    /**
     * TODO: Insert Data ESD Setting Not Check to Sql
     * ? DB: ESD , Table: TExcludeEsd_Tbl
     */
    public function insertCheck(Request $request)
    {


        $db_ins = $request->input('esd_inst');
        $dateTime = date('Y-m-d H:i:s');
        // return response()->json($db_ins);

        try {
            DB::beginTransaction();
            $esd_user_check = new TExcludeEsd();
            $esd_user_check->TExcludeEsd_EmpCd = $db_ins['employeeCode'];
            $esd_user_check->TExcludeEsd_EsdTy = $db_ins['esdType'];
            $esd_user_check->TExcludeEsd_Remk = $db_ins['remark'];
            $esd_user_check->TExcludeEsd_Std = 1;
            $esd_user_check->TExcludeEsd_Lstdt = $dateTime;
            $esd_user_check->save();

            DB::commit();
            Log::info('Insert Successfully:', $esd_user_check->toArray());
            return response()->json([
                'message' => 'Inserted successfully',
                'data' => $esd_user_check
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in insertChangeModel: ' . $e->getMessage() .
                ' on line ' . $e->getLine() .
                ' in file ' . $e->getFile());
            return response()->json([
                'message' => 'Insertion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
