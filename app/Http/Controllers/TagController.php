<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function index(){
        return Tag::with('schedules')->get()->all();
    }
    public function store(Request $request){
        $params = $request->only(['user_id','name','color']);
        //TODO バリデーション
        $tag = Tag::create($params);
        return $tag;
    }
    public function show(Tag $tag){
        return $tag;
    }
    public function update(Request $request, Tag $tag){
        $params = $request->only(['user_id','name','color']);
        //TODO バリデーション
        $tag->update($params);
        return $tag;
    }
    public function destroy(Tag $tag){
        $tag->delete();
        return $tag;
    }
}
