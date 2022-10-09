<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function index(){
        return Tag::all();
    }
    public function store(Request $request){
        return Tag::create($request->all());
    }
    public function show(Tag $tag){
        return $tag;
    }
    public function update(Request $request, Tag $tag){
        $tag->update($request->all());
        return $tag;
    }
    public function destroy(Tag $tag){
        $tag->delete();
        return $tag;
    }
}