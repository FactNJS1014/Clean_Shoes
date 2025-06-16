<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TExcludeEsd; // Ensure this model exists if you want to use it

class GetDataController extends Controller
{
    public function DataCheck()
    {
        try {
            $data = TExcludeEsd::all();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
