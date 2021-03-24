<?php

namespace App\Http\Controllers;

use App\Models\LcmInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Salman\Mqtt\MqttClass\Mqtt;
use Carbon\Carbon;

class LcmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = LcmInfo::with(array('lcmmodel' => function ($query) {
            $query->select('id', 'model_name');
        }))->get();

        return response()->json(['data' => $datas], 200);
    }

    public function updateLcmModel(Request $request)
    {
        $MqttMessage = json_encode($request->all());
        $a = $this->SendMsgViaMqtt($MqttMessage);
        return response()->json($a, 200);
    }

    public function getStatus(Request $request)
    {
        $Device = $request->input('device');
        $Position = $request->input('position');
        $Color = $request->input('color');
        $data = DB::table('lcm_datas')
            ->where('color', '=', $Color)
            ->where('device', '=', $Device)
            ->where('position', '=', $Position)
            ->get();
        return response()->json($data, 200);
    }

    public function updateData(Request $request)
    {
        $Device = $request->input('deviceID');
        $Position = $request->input('deviceLocation');
        $Color = $request->input('color');
        $Lpower = $request->input('lcm_power');
        $Lcurrent = $request->input('lcm_current');
        $Bpower = $request->input('backlight_power');
        $Bcurrent = $request->input('backlight_current');

        DB::table('lcm_datas')
            ->where('color', '=', $Color)
            ->where('device', '=', $Device)
            ->where('position', '=', $Position)
            ->update([
                'lcm_power' => $Lpower,
                'lcm_current' => $Lcurrent,
                'backlight_power' => $Bpower,
                'backlight_current' => $Bcurrent,
                'updated_at' => Carbon::now(),
            ]);
        return response()->json(['status' => 'OK'], 200);
    }


    public function SendMsgViaMqtt($message)
    {
        $mqtt = new Mqtt();
        $client_id = 'test';
        $output = $mqtt->ConnectAndPublish('OTA/UPDATE/NOTIFY', $message, $client_id);

        if ($output === true) {
            return "published";
        }

        return "Failed";
    }
}
