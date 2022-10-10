<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Experience;

class ExperienceController extends Controller
{
    public function index(){
        return Experience::with('tags')->get();
    }
    public function store(Request $request){
        $params = $request->only(['user_id','schedule_id','name','color','start_on','end_on','quote','period','efficiency','note']);
        //TODO バリデーション
        $experience = Experience::create($params);

        $tags = array_fill_keys($request->input('tags'), ['user_id' => $experience->user_id]);
        $experience->tags()->sync($tags);
        return $experience;
    }
    public function show(Experience $experience){
        return $experience;
    }
    public function update(Request $request, Experience $experience){
        $params = $request->only(['user_id','schedule_id','name','color','start_on','end_on','quote','period','efficiency','note']);
        //TODO バリデーション
        $experience->update($params);
        
        $tags = array_fill_keys($request->input('tags'), ['user_id' => $experience->user_id]);
        $experience->tags()->sync($tags);
        return $experience;
    }
    public function destroy(Experience $experience){
        $experience->delete();
        return $experience;
    }
}
