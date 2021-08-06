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

// For web application
Route::apiResource('/device', 'DeviceController');
Route::post('/getStatus', 'LcmController@getStatus');
Route::post('/FOTA', 'LcmController@updateLcmModel');
Route::get('/optical', 'OpticalController@index');

Route::put('/setDeviceOffline/{id}', 'DeviceController@setDeviceOffline');

# For IoT devices
Route::post('/updateData', 'LcmController@updateData');
Route::post('/device/ReceiveMQTT', 'DeviceController@ReceiveMQTT');
Route::post('/device/StartDownload', 'DeviceController@StartDownload');
Route::post('/device/EndDownload', 'DeviceController@EndDownload');

// For Identity verification
Route::post('/signup', 'AuthController@signup');
Route::post('/signin', 'AuthController@signin');

// For web application
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'AuthController@user');
    Route::get('/signout', 'AuthController@signout');
    Route::apiResource('/model', 'ModelController');
    Route::apiResource('/firmware', 'FirmwareController');
});