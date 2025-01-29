<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function showAddAccountForm()
    {
        return view('frontend.account.add');
    }

    public function addAccount(Request $request)
    {
        $account=BankAccount::create([
            "account_type"=>BankAccount::TYPE_OWN,
            "account_holder_name"=>$request->get('account_holder_name'),
            "bank_name"=>$request->get('bank_name'),
            "swift"=>$request->get('swift_code'),
            "account_number"=>$request->get('account_number'),
            "shortcode"=>$request->get('shortcode'),
            "branch_code"=>$request->get('branch_code'),
            "user_id"=>auth()->user()->id,
        ]);



        // Handle account addition logic here


        return redirect()->back()->with('success', 'Account added successfully');
    }
}
