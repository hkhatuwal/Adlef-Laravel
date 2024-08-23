<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class NewsInsightsController extends Controller
{
    public function index(){

        $categoriesWithPosts=Category::where('name','!=','Podcasts')->get();
        $podcasts=Post::whereHas('category',function ($query){
            $query->where('name','Podcasts');
        })->get();


        return view('frontend.news_insights.news_insights',compact('categoriesWithPosts','podcasts'));
    }
}
