<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firmware;
use Illuminate\Support\Facades\Storage;

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
        $File = $request->input('File');
        $Size = $request->input('Size');
        $MD5 = $request->input('MD5');
        $FileData = $request->input('data');

        $characters = explode(',', $FileData);

        $binary = '';
        foreach ($characters as $character) {
            $binary .= pack('C', $character);
        }
        $this->test($binary, $File);

        try {
            Firmware::create([
                'firmware' => $Name,
                'file' => $File,
                'size' => $Size,
                'version' => '1',
                'MD5' => $MD5
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
        $File = $request->input('File');
        $Size = $request->input('Size');
        $MD5 = $request->input('MD5');
        $version = $request->input('Version');
        $FileData = $request->input('data');

        $characters = explode(',', $FileData);

        $binary = '';
        foreach ($characters as $character) {
            $binary .= pack('C', $character);
        }
        $this->test($binary, $File);
        
        try {
            Firmware::where('id', $id)->update([
                'file' => $File,
                'size' => $Size,
                'version' => $version,
                'MD5' => $MD5
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
        $flight = Firmware::find($id);

        $flight->delete();
        return response()->json(['status' => 'OK'], 200);
    }

    public function test($FileData,  $FileName)
    {
        // var_dump($FileData);

        Storage::disk('public')->put($FileName, $FileData);
    }
}
