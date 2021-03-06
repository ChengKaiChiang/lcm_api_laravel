<?php

namespace App\Http\Controllers;

use App\Models\LcmInfo;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Salman\Mqtt\MqttClass\Mqtt;
use Carbon\Carbon;

class LcmController extends Controller
{
    public function updateLcmModel(Request $request)
    {
        $Datas = $request->all();
        unset($Datas['id']);

        $Devices = $request->input('id');

        foreach ($Devices as $Device) {
            $id = $Device['position'] . '_' . $Device['device'];
            $MqttMessage = json_encode($Datas);
            $this->SendMsgViaMqtt($MqttMessage, $id);
        }

        $time = Carbon::now()->toDate()->format('Y-m-d H:i:s.u');

        foreach ($Devices as $Device) {
            try {
                Device::where('device', '=', $Device['device'])
                    ->where('position', '=', $Device['position'])
                    ->update([
                        'send_mqtt_at' => $time,
                    ]);
            } catch (\Illuminate\Database\QueryException $exception) {
                // You can check get the details of the error using `errorInfo`:
                $errorInfo = $exception->errorInfo;

                return response()->json(['DataBase_ErrorCode' => $errorInfo[1], 'DataBase_Error' => $errorInfo[2]], 400);
                // Return the response to the client..
            }
        }
        return response()->json('OK', 200);
    }

    public function getStatus(Request $request)
    {
        $Device = $request->input('device');
        $Position = $request->input('position');
        $datas = DB::table('lcm_datas')
            ->where('device', '=', $Device)
            ->where('position', '=', $Position)
            ->get();
        $arr = array();
        foreach ($datas as $data) {
            switch ($data->color) {
                case "White":
                    $arr += ['light' => $data];
                    break;
                case "Black":
                    $arr += ['dark' => $data];
                    break;
                case "Red":
                    $arr += ['danger' => $data];
                    break;
                case "Green":
                    $arr += ['success' => $data];
                    break;
                case "Blue":
                    $arr += ['primary' => $data];
                    break;
                case "V127":
                    $arr += ['secondary' => $data];
                    break;
            }
        };
        return response()->json([$arr], 200);
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

        $Threshold = 350;

        $Status = Device::where('device', '=', $Device)
            ->where('position', '=', $Position)
            ->select('model', 'status')
            ->get();

        if ($Status[0]['model'] != 'LQ144P1JX01')
            $Threshold = 425;

        if ($Status[0]['status'] == '1') {
            return response()->json(['status' => 'updating'], 200);
        } else if ($Status[0]['status'] == '2') {
            return response()->json(['status' => 'error'], 200);
        } else {
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

            if ($Bcurrent > ($Threshold + 20) || $Bcurrent < $Threshold) {
                Device::where('device', '=', $Device)
                    ->where('position', '=', $Position)
                    ->update([
                        'lcm_status' => 2,
                        'status' => 0,
                    ]);
            } else {
                Device::where('device', '=', $Device)
                    ->where('position', '=', $Position)
                    ->update([
                        'lcm_status' => 1,
                        'status' => 0,
                    ]);
            }
        }
        return response()->json(['status' => 'OK'], 200);
    }


    public function SendMsgViaMqtt($message, $id)
    {
        $mqtt = new Mqtt();
        $client_id = 'test';
        $output = $mqtt->ConnectAndPublish('OTA/UPDATE/NOTIFY/' . $id, $message, $client_id);

        if ($output === true) {
            return "published";
        }

        return "Failed";
    }
}
