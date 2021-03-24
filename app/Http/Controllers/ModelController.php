<?php

namespace App\Http\Controllers;

use App\Models\LcmModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['data' => LcmModel::with('firmware')->get()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Model = $request->input('Model');
        $Firmware = $request->input('Firmware');
        try {
            LcmModel::create([
                'model' => $Model,
                'firmware' => $Firmware,
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;
            // var_dump($errorInfo[1]);

            return response()->json(['DataBase_ErrorCode' => $errorInfo[1]], 400);
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
        return response()->json(['data' => LcmModel::with('firmware')->find($id)], 200);
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
        $Firmware = $request->input('Firmware');

        try {
            LcmModel::where('id', $id)->update([
                'firmware' => $Firmware,
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;

            return response()->json(['DataBase_ErrorCode' => $errorInfo[1]], 400);
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
        $flight = LcmModel::find($id);
        $flight->delete();

        return response()->json(['status' => 'OK'], 200);
    }
}
