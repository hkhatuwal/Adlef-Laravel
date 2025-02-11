<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Individual;
use App\Models\ThirdPartyAccount;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function showAddAccountForm()
    {
        return view('client.account.add');
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


        return redirect()->route('client.account.index')->with('success', 'Account added successfully');
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
            "user_id" => auth()->user()->id,
        ]);
        if ($request->get('third_party_type') == Individual::TYPE) {
            $this->createIndividualAccount($request, $account);
        } else {
            $this->createCompanyAccount($request, $account);
        }

        return redirect()->route('client.account.index')->with('success', "Account Send For Verification");

    }

    public function index()
    {
        $user = auth()->user();

        $ownAccounts = BankAccount::where('user_id', $user->id)
            ->where('account_type', BankAccount::TYPE_OWN)
            ->get();

        $thirdPartyAccounts = BankAccount::query()->where('user_id', $user->id)
            ->where('account_type', BankAccount::TYPE_THIRD_PARTY)
            ->with(['thirdPartyAccount'])
            ->get();

        $cryptoWallets = $user->cryptoWallets()
            ->with('currency')
            ->get();

        return view('client.account.index', compact('ownAccounts', 'thirdPartyAccounts', 'cryptoWallets'));
    }

    private function createIndividualAccount(Request $request, BankAccount $bank)
    {
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
            "third_party_account_id" => $bank->id,
        ]);
        $this->createThirdpartyAccount($request, $bank->id, $individual->id);
        $this->createAddress($request, $individual->id);
    }

    private function createCompanyAccount(Request $request, BankAccount $bank)
    {
        $company = Company::create([
            "company_name" => $request->get('company_name'),
            "country" => $request->get('registration_country'),
            "registration_date" => $request->get('registration_date'),
            "registration_number" => $request->get('registration_number'),
            "email" => $request->get('email'),
            "contact" => $request->get('phone'),
            "relationship" => $request->get('counterparty_relationship'),
            "registration_proof" => $request->get('company_document_proof_path'),
            "third_party_account_id" => $bank->id,
        ]);
        $this->createThirdpartyAccount($request, $bank->id, null, $company->id);

        $this->createAddress($request, null, $company->id);
    }

    private function createAddress(Request $request, int $individual_id = null, ?int $company_id = null): void
    {
        Address::create([
            'country' => $request->get('country'),
            'state' => $request->get('state'),
            'postal_code' => $request->get('postal_code'),
            'city' => $request->get('city'),
            'address_line1' => $request->get('street_address'),
            'address_line2' => null,
            'user_id' => auth()->user()->id,
            'individual_id' => $individual_id,
            'company_id' => $company_id,
        ]);
    }

    private function createThirdpartyAccount(Request $request, int $bankAccountId, int $individual_id = null, ?int $company_id = null): void
    {
        ThirdPartyAccount::create([
            'third_party_type' => isset($individual_id) ? ThirdPartyAccount::TYPE_INDIVIDUAL : ThirdPartyAccount::TYPE_COMPANY,
            'individual_id' => $individual_id,
            'bank_account_id' => $bankAccountId,
            'company_id' => $company_id,
        ]);
    }
}
