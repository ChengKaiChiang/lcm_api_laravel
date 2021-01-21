<?php

namespace App\Http\Controllers;

use App\Models\LcmInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Salman\Mqtt\MqttClass\Mqtt;

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
        $lcm_id = explode(',', $request->input('id'));
        $model = $request->input('model');
        foreach ($lcm_id as $x) {
            LcmInfo::where('id', $x)->update(['lcm_model' => $model]);
        }
        return response()->json(true, 200);
    }

    public function getStatus(Request $request)
    {
        $data = DB::table('lcm_status')
            ->where('color', '=', $request->input('color'))
            ->where('lcm_ip', '=', $request->input('device_ip'))
            ->get();
        return response()->json($data, 200);
    }

    public function mqtt(Request $request)
    {
        $message = $request->input('message');
        $this->SendMsgViaMqtt($message);
        return response()->json(true, 200);
    }

    public function SendMsgViaMqtt($message)
    {
        $mqtt = new Mqtt();
        $client_id = 'test';
        $output = $mqtt->ConnectAndPublish('lcmData', $message, $client_id);

        if ($output === true) {
            return "published";
        }

        return "Failed";
    }
}
