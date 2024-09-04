<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OpentrustApisPageController extends Controller
{
    public function index()
    {

        return view('frontend.trust_apis.open_trust_apis');
    }
}
