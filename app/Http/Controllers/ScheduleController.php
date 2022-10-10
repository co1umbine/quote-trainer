<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;

class ScheduleController extends Controller
{
    public function index(){
        return Schedule::with('tags')->get();
    }
    public function store(Request $request){
        $params = $request->only(['user_id','name','color','start_on','quote','note']);
        //TODO バリデーション
        $schedule = Schedule::create($params);

        $tags = array_fill_keys($request->input('tags'), ['user_id' => $schedule->user_id]);
        $schedule->tags()->sync($tags);
        return $schedule;
    }
    public function show(Schedule $schedule){
        return $schedule;
    }
    public function update(Request $request, Schedule $schedule){
        $params = $request->only(['user_id','name','color','start_on','quote','note']);
        //TODO バリデーション
        $schedule->update($params);
        $tags = array_fill_keys($request->input('tags'), ['user_id' => $schedule->user_id]);
        $schedule->tags()->sync($tags);
        return $schedule;
    }
    public function destroy(Schedule $schedule){
        $schedule->delete();
        return $schedule;
    }
}
