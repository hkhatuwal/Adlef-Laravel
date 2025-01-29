<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function showAddAccountForm()
    {
        return view('frontend.account.add');
    }

    public function addAccount(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:bank_account,crypto_wallet',
            'ownership' => 'required|in:own,third_party',
            // Add more validation rules as needed
        ]);

        // Handle account addition logic here

        return redirect()->back()->with('success', 'Account added successfully');
    }
}
