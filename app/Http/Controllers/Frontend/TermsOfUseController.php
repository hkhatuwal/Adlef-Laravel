<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermsOfUseController extends Controller
{
    public function index()
    {
        return view('frontend.terms-of-use.terms-of-use');
    }
}
