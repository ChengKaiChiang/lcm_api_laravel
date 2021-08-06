<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestData;
use Illuminate\Support\Facades\DB;

class OpticalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas = TestData::all();
        $table = DB::table('test_datas')
        ->select('level', DB::raw('count(level) as count'))
        ->groupBy('level')
        ->get();
        return response()->json([
            'optical_data' => $datas,
            'table' => $table
        ], 200);
    }
}
