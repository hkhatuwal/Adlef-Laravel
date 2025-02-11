<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CryptoWallet;
use Illuminate\Support\Facades\Auth;

class CryptoWalletController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'wallet_name' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
            'network' => 'required|exists:currencies,id'
        ]);

        $wallet = new CryptoWallet();
        $wallet->user_id = Auth::id();
        $wallet->alias = $request->wallet_name;
        $wallet->wallet_address = $request->wallet_address;
        $wallet->currency_id = $request->network;
        $wallet->save();

        return redirect()->route('client.account.index')->with('success', 'Crypto Wallet Saved');
    }
}
