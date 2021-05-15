<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lcm', 'LcmController@index');
Route::post('/mqtt', 'LcmController@mqtt');
Route::post('/getStatus', 'LcmController@getStatus');
Route::post('/FOTA', 'LcmController@updateLcmModel');
Route::post('/updateData', 'LcmController@updateData');
Route::put('/setDeviceOffline/{id}', 'DeviceController@setDeviceOffline');
Route::apiResource('/model', 'ModelController');
Route::apiResource('/firmware', 'FirmwareController');
Route::apiResource('/device', 'DeviceController');

Route::post('/device/ReceiveMQTT', 'DeviceController@ReceiveMQTT');
Route::post('/device/StartDownload', 'DeviceController@StartDownload');
Route::post('/device/EndDownload', 'DeviceController@EndDownload');

Route::get('/test', 'LcmController@test');