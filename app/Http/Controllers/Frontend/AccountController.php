<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Individual;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function showAddAccountForm()
    {
        return view('frontend.account.add');
    }

    public function addAccount(Request $request)
    {
        $account = BankAccount::create([
            "account_type" => BankAccount::TYPE_OWN,
            "account_holder_name" => $request->get('account_holder_name'),
            "bank_name" => $request->get('bank_name'),
            "swift" => $request->get('swift_code'),
            "account_number" => $request->get('account_number'),
            "shortcode" => $request->get('shortcode'),
            "branch_code" => $request->get('branch_code'),
            "user_id" => auth()->user()->id,
        ]);


        // Handle account addition logic here


        return redirect()->back()->with('success', 'Account added successfully');
    }


    public function addThirdPartyAccount(Request $request)
    {
        $account = BankAccount::create([
            "account_type" => BankAccount::TYPE_THIRD_PARTY,
            "account_holder_name" => $request->get('account_holder'),
            "bank_name" => $request->get('bank_name'),
            "swift" => $request->get('swift_code'),
            "account_number" => $request->get('account_number'),
            "shortcode" => $request->get('shortcode'),
            "branch_code" => $request->get('branch_code'),
            "user_id" =>auth()->user()->id,
        ]);
        if ($request->get('third_party_type') == Individual::TYPE) {
            $individual = Individual::create([
                "fname" => $request->get('first_name'),
                "lname" => $request->get('last_name'),
                "dob" => $request->get('date_of_birth'),
                "gender" => $request->get('gender'),
                "email" => $request->get('first_name'),
                "contact" => $request->get('first_name'),
                "country_of_origin" => $request->get('country_of_birth'),
                "relationship" => $request->get('counterparty_relationship'),
                "document_id_number" => $request->get('document_number'),
                "document_issued_country" => $request->get('document_country'),
                "document_url" => $request->get('id_proof_path'),
                "third_party_account_id" => $account->id,
            ]);

            return redirect()->back()->with('success',"Account Send For Verification");
        }
        return  redirect()->back()->with('error',"Failed to create account");


    }
}
