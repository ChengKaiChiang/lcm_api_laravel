<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['data' => Device::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $Device = $request->input('deviceID');
        $Position = $request->input('deviceLocation');
        $Model = $request->input('model');
        $Status = $request->input('status');
        $Firmware = $request->input('firmwareName');
        $Version = $request->input('firmwareVer');

        $Check = Device::where('device', '=', $Device)->where('position', '=', $Position)->exists();
        try {
            if (!$Check) {
                Device::create([
                    'device' => $Device,
                    'position' => $Position,
                    'status' => $Status,
                    'model' => $Model,
                    'firmware' => $Firmware,
                    'version' => $Version,
                ]);

                $colors = array('White', 'Black', 'Red', 'Green', 'Blue', 'V127');
                foreach ($colors as $color) {
                    DB::table('lcm_datas')
                        ->insert([
                            'device' => $Device,
                            'position' => $Position,
                            'color' => $color,
                            'created_at' => Carbon::now(),
                        ]);
                }
            } else {
                Device::where('device', '=', $Device)
                    ->where('position', '=', $Position)
                    ->update([
                        'status' => $Status,
                        'model' => $Model,
                        'firmware' => $Firmware,
                        'version' => $Version,
                    ]);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;
            // var_dump($errorInfo[1]);

            return response()->json(['DataBase_ErrorCode' => $errorInfo[1], 'DataBase_Error' => $errorInfo[2]], 400);
            // Return the response to the client..
        }


        return response()->json(['status' => 'OK'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $Status = $request->input('status');
        $Description = $request->input('description');
        $Position = $request->input('deviceLocation');
        try {
            Device::where('device', '=', $id)
                ->where('position', '=', $Position)
                ->update([
                    'status' => $Status,
                    'description' => $Description
                ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;
            // var_dump($errorInfo[1]);

            return response()->json(['DataBase_ErrorCode' => $errorInfo[1], 'DataBase_Error' => $errorInfo[2]], 400);
            // Return the response to the client..
        }
        return response()->json(['status' => 'OK'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}