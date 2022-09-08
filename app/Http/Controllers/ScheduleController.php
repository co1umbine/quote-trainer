<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;

class ScheduleController extends Controller
{
    public function index(){
        return Schedule::all();
    }
    public function store(Request $request){
        return Schedule::create($request->all());
    }
    public function show(Schedule $schedule){
        return $schedule;
    }
    public function update(Request $request, Schedule $schedule){
        $schedule->update($request->all());
        return $schedule;
    }
    public function destroy(Schedule $schedule){
        $schedule->delete();
        return $schedule;
    }
}
