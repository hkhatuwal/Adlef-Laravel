<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        $title = 'API Documentation - ' . env('APP_NAME');
        $description = 'Complete API documentation for integrating with our payment gateway API. Learn how to generate payments, retrieve payment details, and handle webhooks.';
        $keywords = 'API documentation, payment gateway, REST API, webhooks, integration guide';
        
        return view('frontend.api-documentation.index', compact('title', 'description', 'keywords'));
    }
}
