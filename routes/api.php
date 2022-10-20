<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/schedules', 'ScheduleController@index');
Route::post('/schedules', 'Schedulecontroller@store');
Route::get('/schedules/{schedule}', 'ScheduleController@show');
Route::put('/schedules/{schedule}', 'ScheduleController@update');
Route::delete('schedules/{schedule}', 'ScheduleController@destroy');
Route::get('/tags', 'TagController@index');
Route::post('/tags', 'TagController@store');
Route::get('/tags/{tag}', 'TagController@show');
Route::put('/tags/{tag}', 'TagController@update');
Route::delete('tags/{tag}', 'TagController@destroy');
Route::get('/experiences', 'ExperienceController@index');
Route::post('/experiences', 'ExperienceController@store');
Route::get('/experiences/quoteInRange/{min}/{max}', 'ExperienceController@showQuoteInRange');
Route::get('/experiences/periodInRange/{min}/{max}', 'ExperienceController@showPeriodInRange');
Route::get('/experiences/inPeriod/{start}/{end}', 'ExperienceController@showInPeriod');
Route::get('/experiences/{experience}', 'ExperienceController@show');
Route::put('/experiences/{experience}', 'ExperienceController@update');
Route::delete('experiences/{experience}', 'ExperienceController@destroy');
