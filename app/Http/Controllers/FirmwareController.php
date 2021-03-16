<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firmware;

class FirmwareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['data' => Firmware::all()], 200);
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
        $Name = $request->input('Name');
        $Size = $request->input('Size');
        $MD5 = $request->input('MD5');
        Firmware::create([
            'firmware' => $Name,
            'size' => $Size,
            'version' => '1',
            'MD5' => $MD5
        ]);

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
        return response()->json(['data' => Firmware::find($id)], 200);
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
        $Size = $request->input('Size');
        $MD5 = $request->input('MD5');
        $version = $request->input('Version');
        Firmware::where('id', $id)->update([
            'size' => $Size,
            'version' => $version,
            'MD5' => $MD5
        ]);

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
        $flight = Firmware::find($id);

        $flight->delete();
        return response()->json(['status' => 'OK'], 200);
    }
}
