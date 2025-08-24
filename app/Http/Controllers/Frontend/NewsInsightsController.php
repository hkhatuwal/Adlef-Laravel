<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Services\NewsApiService;
use Illuminate\Http\Request;

class NewsInsightsController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService)
    {
        $this->newsApiService = $newsApiService;
    }

    public function index(){
        $categoriesWithPosts = Category::where('name','!=','Podcasts')->get();
        
        // Fetch Bitcoin news from NewsAPI
        $newsResponse = $this->newsApiService->getBitcoinNews(10);
        
        if ($newsResponse['success']) {
            $bitcoinNews = $newsResponse['articles'];
        } else {
            // Use fallback news if API fails
            $fallbackResponse = $this->newsApiService->getFallbackNews();
            $bitcoinNews = $fallbackResponse['articles'];
        }

        return view('frontend.news_insights.news_insights', compact('categoriesWithPosts', 'bitcoinNews'));
    }
}
