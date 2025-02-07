<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Sample data - replace with your actual data fetching logic
        $totalValue = 180.13;
        $assetClassesCount = 2;
        
        $assetClasses = [
            [
                'name' => 'Crypto',
                'value' => 120.13,
                'percentage' => 66.7,
                'offset' => 0,
                'color' => 'bg-purple-500'
            ],
            [
                'name' => 'Cash',
                'value' => 60.00,
                'percentage' => 33.3,
                'offset' => 66.7,
                'color' => 'bg-blue-500'
            ]
        ];
        
        $assets = [
            [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'icon' => asset('images/crypto/btc.png'),
                'balance' => '0.00324',
                'value' => 120.13,
                'change_24h' => 2.5
            ],
            [
                'name' => 'US Dollar',
                'symbol' => 'USD',
                'icon' => asset('images/fiat/usd.png'),
                'balance' => '60.00',
                'value' => 60.00,
                'change_24h' => 0
            ]
        ];
        
        return view('client.dashboard', compact('totalValue', 'assetClassesCount', 'assetClasses', 'assets'));
    }
}
