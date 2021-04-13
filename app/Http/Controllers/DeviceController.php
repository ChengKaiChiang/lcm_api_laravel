<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
        $datas = Device::all();
        $res = collect();
        $online = 0;
        foreach ($datas as $data) {
            $data = collect($data);
            $LastUpdateTime =  strtotime('now') - strtotime($data->get('updated_at'));
            if ($data->get('lcm_status') === '1' && $LastUpdateTime < 300) {
                $Variant = 'success';
                $online++;
            } else if ($data->get('lcm_status') === '2' && $LastUpdateTime < 300) {
                $Variant = 'danger';
                $online++;
            } else if ($data->get('lcm_status') === '0') {
                $Variant = 'secondary';
            } else if ($LastUpdateTime > 300) {
                $Variant = 'secondary';
                $this->setDeviceOffline($data->get('id'));
            }
            $data->put('variant', $Variant);
            $res->push($data->toArray());
        }
        // return response('Ok');
        return response()->json([
            'data' => $res,
            'now_time' => date("Y-m-d H:i:s"),
            'online' => $online
        ], 200);
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
        $flight = Device::find($id);
        $flight->delete();

        return response()->json(['status' => 'OK'], 200);
    }


    public function setDeviceOffline($id)
    {
        //
        try {
            $flight = Device::find($id);

            $flight->lcm_status = 0;
            $flight->status = -1;

            $flight->save();
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;
            // var_dump($errorInfo[1]);

            return response()->json(['DataBase_ErrorCode' => $errorInfo[1], 'DataBase_Error' => $errorInfo[2]], 400);
            // Return the response to the client..
        }
        return response()->json(['status' => 'OK'], 200);
    }
}
