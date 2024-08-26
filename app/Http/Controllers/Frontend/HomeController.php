<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $topNews=Post::query()->whereHas('category',function ($query){
            $query->where('name','!=','Podcasts');
        })->limit(3)->latest()->get();
        return view('frontend.home.home',compact('topNews'));
    }
}
