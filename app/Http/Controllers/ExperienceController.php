<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Experience;

class ExperienceController extends Controller
{
    public function index(){
        return Experience::with('tags')->get()->all();
    }
    public function store(Request $request){
        $params = $request->only(['user_id','schedule_id','name','color','start_on','end_on','quote','period','efficiency','note']);
        //TODO バリデーション
        $experience = Experience::create($params);

        $tags = $request->input('tags');
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
        $tags = $request->input('tags');
        $experience->tags()->sync($tags);
        return $experience;
    }
    public function destroy(Experience $experience){
        $experience->delete();
        return $experience;
    }
}
