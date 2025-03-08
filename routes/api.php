<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request){
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/events/{event}/attendees', [AttendeeController::class, 'store']);
//     Route::delete('/events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);
// });

Route::post('/login', [AuthController::class, 'login']);
  //POST http://127.0.0.1:8000/api/login + header key:accept value:application/json
    //body {"email" : "king.lelia@example.org","password" : "password"} and you receive the token
    //authorization:type:bearer token: paste your token, then GET http://127.0.0.1:8000/api/user
    //then test POST(with token) http://127.0.0.1:8000/api/events?include=user  body {"name":"Event created with authentication","start_time":"2023-07-01 15:00:00","end_time":"2023-07-01 16:00:00"}

    //x set on postman to apply auto the token: scriptspost-response:
        //const json = pm.response.json();  // Ottiene il JSON dalla risposta
        //pm.environment.set("TOKEN", json.token);
    //now in Environments(barra a sx)>global>add:  variable:TOKEN   current value: mytokenoftheuser
    //now in request Authorization:bearer token: {{TOKEN}}

Route::post('/logout', [AuthController::class, 'logout'])  //POST http://127.0.0.1:8000/api/logout  (you have to do it while you are still signIn)
    ->middleware('auth:sanctum');

Route::apiResource('events', EventController::class);  //i am using events through auth
Route::apiResource('events.attendees',AttendeeController::class)  //sub-resource i.e. GET /events/{event}/attendees ({event} is a num) and managed by AttendeeController
    ->scoped()->except(['update']);  //scoped() x apply to a specific sub-resource



