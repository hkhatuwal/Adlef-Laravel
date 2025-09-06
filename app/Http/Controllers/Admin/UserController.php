<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\BankAccount;
use App\Models\CryptoWallet;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Commission;
use App\Models\AdminDepositAccount;
use App\Models\UserPaymentSettings;
use App\Services\ClientPaymentService;

class UserController extends Controller
{
    protected NotificationService $notificationService;
    protected ClientPaymentService $clientPaymentService;

    public function __construct(NotificationService $notificationService, ClientPaymentService $clientPaymentService)
    {
        $this->notificationService = $notificationService;
        $this->clientPaymentService = $clientPaymentService;
    }

    public function index()
    {
        $users = User::with(['profile', 'contactDetails'])->role('client')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['profile', 'contactDetails', 'businessDetails', 'addresses', 'bankAccounts', 'cryptoWallets']);
        return view('admin.users.show', compact('user'));
    }

    public function verifyDocument(UserProfile $userProfile)
    {
        if (!$userProfile->document_path) {
            return back()->with('error', 'No document found to verify.');
        }

        $userProfile->update([
            'document_verified' => true
        ]);

        // Send notification through multiple channels
        $this->notificationService->notify(
            $userProfile->user,
            [
                'type' => 'success',
                'title' => 'Document Verified',
                'message' => 'Your verification document has been reviewed and approved.',
                'notifiable_type' => UserProfile::NOTIFICATION_DOCUMENT_VERIFIED,
                'notifiable_id' => $userProfile->id,
                'metadata' => [
                    'document_type' => $userProfile->document_type ?? 'Identity Document',
                    'verified_at' => now()->format('Y-m-d H:i:s'),
                    'verified_by' => auth()->user()->name ?? 'Admin',
                    'document_path' => $userProfile->document_path
                ]
            ],
            ['database', 'email'] // Send through database and email
        );

        return back()->with('success', 'Document has been verified successfully.');
    }

    public function unverifyDocument(UserProfile $userProfile)
    {
        $userProfile->update([
            'document_verified' => false
        ]);

        // Send notification through multiple channels
        $this->notificationService->notify(
            $userProfile->user,
            [
                'type' => 'warning',
                'title' => 'Document Verification Revoked',
                'message' => 'Your document verification status has been revoked. Please contact support for more information.',
                'notifiable_type' => UserProfile::class,
                'notifiable_id' => $userProfile->id,
                'metadata' => [
                    'document_type' => $userProfile->document_type ?? 'Identity Document',
                    'unverified_at' => now()->format('Y-m-d H:i:s'),
                    'unverified_by' => auth()->user()->name ?? 'Admin',
                    'document_path' => $userProfile->document_path,
                    'reason' => 'Administrative action - please contact support'
                ]
            ],
            ['database', 'email'] // Send through database and email
        );

        return back()->with('success', 'Document has been unverified.');
    }

    public function accounts(User $user)
    {
        $user->load(['bankAccounts', 'cryptoWallets.currency']);
        return view('admin.users.accounts', compact('user'));
    }

    public function assets(User $user)
    {
        $user->load(['assetAccounts.currency']);
        return view('admin.users.assets', compact('user'));
    }

    public function showBankAccount(User $user, BankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== $user->id) {
            abort(404);
        }

        $bankAccount->load([
            'user',
            'thirdPartyAccount.individual.address',
            'thirdPartyAccount.company.address'
        ]);

        return view('admin.users.bank-account-details', compact('user', 'bankAccount'));
    }

    public function showCryptoWallet(User $user, CryptoWallet $cryptoWallet)
    {
        if ($cryptoWallet->user_id !== $user->id) {
            abort(404);
        }

        $cryptoWallet->load(['user', 'currency']);

        return view('admin.users.crypto-wallet-details', compact('user', 'cryptoWallet'));
    }

    public function verifyBankAccount(BankAccount $bankAccount)
    {
        $bankAccount->update([
            'is_verified' => true
        ]);

        // Send notification through multiple channels
        $this->notificationService->notify(
            $bankAccount->user,
            [
                'type' => 'success',
                'title' => 'Bank Account Verified',
                'message' => "Your bank account ({$bankAccount->bank_name} - {$bankAccount->account_number}) has been verified successfully.",
                'notifiable_type' => BankAccount::NOTIFICATION_TYPE_BANK_ACCOUNT_VERIFIED,
                'notifiable_id' => $bankAccount->id,
                'metadata' => [
                    'bank_name' => $bankAccount->bank_name,
                    'account_number' => $bankAccount->account_number,
                    'verified_at' => now()->format('Y-m-d H:i:s'),
                    'verified_by' => auth()->user()->name ?? 'Admin'
                ]
            ],
            ['database', 'email'] // Send through database and email
        );

        return back()->with('success', 'Bank account has been verified successfully.');
    }

    public function unverifyBankAccount(BankAccount $bankAccount)
    {
        $bankAccount->update([
            'is_verified' => false
        ]);

        // Send notification through multiple channels
//        $this->notificationService->notify(
//            $bankAccount->user,
//            [
//                'type' => 'warning',
//                'title' => 'Bank Account Verification Revoked',
//                'message' => "The verification status of your bank account ({$bankAccount->bank_name} - {$bankAccount->account_number}) has been revoked. Please contact support for more information.",
//                'notifiable_type' => BankAccount::NOTIFICATION_TYPE_BANK_ACCOUNT_UNVERIFIED,
//                'notifiable_id' => $bankAccount->id,
//                'metadata' => [
//                    'bank_name' => $bankAccount->bank_name,
//                    'account_number' => $bankAccount->account_number,
//                    'unverified_at' => now()->format('Y-m-d H:i:s'),
//                    'unverified_by' => auth()->user()->name,
//                ]
//            ],
//            ['database', 'email'] // Send through database and email
//        );

        return back()->with('success', 'Bank account verification has been revoked.');
    }

    public function verifyCryptoWallet(CryptoWallet $cryptoWallet)
    {
        $cryptoWallet->update([
            'is_verified' => true
        ]);

        // Send notification through multiple channels
        $this->notificationService->notify(
            $cryptoWallet->user,
            [
                'type' => 'success',
                'title' => 'Crypto Wallet Verified',
                'message' => "Your crypto wallet ({$cryptoWallet->currency->name} - {$cryptoWallet->formattedAddress()}) has been verified successfully.",
                'notifiable_type' => 'crypto_wallet_verified',
                'notifiable_id' => $cryptoWallet->id,
                'metadata' => [
                    'currency' => $cryptoWallet->currency->name,
                    'wallet_address' => $cryptoWallet->wallet_address,
                    'alias' => $cryptoWallet->alias,
                    'verified_at' => now()->format('Y-m-d H:i:s'),
                    'verified_by' => auth()->user()->name ?? 'Admin'
                ]
            ],
            ['database', 'email'] // Send through database and email
        );

        return back()->with('success', 'Crypto wallet has been verified successfully.');
    }

    public function unverifyCryptoWallet(CryptoWallet $cryptoWallet)
    {
        $cryptoWallet->update([
            'is_verified' => false
        ]);

        return back()->with('success', 'Crypto wallet verification has been revoked.');
    }

    public function commissions(User $user)
    {
        $currencies = Currency::all();
        $userCommissions = $user->commissions()->with('currency')->get();

        return view('admin.users.commissions', compact('user', 'currencies', 'userCommissions'));
    }

    public function updateCommissions(Request $request, User $user)
    {

        $request->validate([
            'commissions' => ['required', 'array'],
            'commissions.*.*.currency_id' => ['required', 'exists:currencies,id'],
            'commissions.*.*.commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'commissions.*.*.type' => ['required', 'in:' . Commission::TYPE_OTC . ',' . Commission::TYPE_ASSET_TRANSFER],
        ]);

        // Process OTC commissions
        if (isset($request->commissions['otc'])) {
            foreach ($request->commissions['otc'] as $commission) {
                $user->commissions()->updateOrCreate(
                    [
                        'currency_id' => $commission['currency_id'],
                        'type' => Commission::TYPE_OTC
                    ],
                    ['commission_rate' => $commission['commission_rate']]
                );
            }
        }

        // Process Asset Transfer commissions
        if (isset($request->commissions['transfer'])) {
            foreach ($request->commissions['transfer'] as $commission) {
                $user->commissions()->updateOrCreate(
                    [
                        'currency_id' => $commission['currency_id'],
                        'type' => Commission::TYPE_ASSET_TRANSFER
                    ],
                    ['commission_rate' => $commission['commission_rate']]
                );
            }
        }

        return redirect()->route('admin.users.commissions', $user)
            ->with('success', 'Commission rates updated successfully');
    }

    public function depositAccounts(User $user)
    {
        $availableAccounts = AdminDepositAccount::where('is_active', true)
            ->whereNotIn('id', $user->adminDepositAccounts->pluck('id'))
            ->get();

        return view('admin.users.deposit-accounts', compact('user', 'availableAccounts'));
    }

    public function assignDepositAccount(Request $request, User $user)
    {
        $validated = $request->validate([
            'admin_deposit_account_id' => 'required|exists:admin_deposit_accounts,id'
        ]);

        $user->adminDepositAccounts()->attach($validated['admin_deposit_account_id']);

        return redirect()
            ->route('admin.users.deposit-accounts', $user)
            ->with('success', 'Deposit account assigned successfully.');
    }

    public function removeDepositAccount(User $user, AdminDepositAccount $account)
    {
        $user->adminDepositAccounts()->detach($account->id);

        return redirect()
            ->route('admin.users.deposit-accounts', $user)
            ->with('success', 'Deposit account removed successfully.');
    }

    public function paymentSettings(User $user)
    {
        $paymentSettings = $user->paymentSettings ?? UserPaymentSettings::create([
            'user_id' => $user->id,
            ...UserPaymentSettings::getDefaultSettings()
        ]);

        $availableProviders = config('constants.internal_payment_providers');
        $userApiClients = $user->apiClients()->with('paymentTransactions')->get();
        $providerUsageStats = $this->clientPaymentService->getUserProviderUsageStats($user);

        return view('admin.users.payment-settings', compact('user', 'paymentSettings', 'availableProviders', 'userApiClients', 'providerUsageStats'));
    }

    public function updatePaymentSettings(Request $request, User $user)
    {
        $availableProviders = config('constants.internal_payment_providers');
        $allProviders = [];
        foreach ($availableProviders as $category => $providers) {
            $allProviders = array_merge($allProviders, $providers);
        }

        $validationRules = [
            'max_api_clients' => 'required|integer|min:1|max:100',
            'allowed_payment_providers' => 'required|array',
            'allowed_payment_providers.card' => 'array',
            'allowed_payment_providers.crypto' => 'array',
            'is_active' => 'boolean',
            'fee_type' => 'required|in:percentage,fixed',
            'fee_percentage' => 'required_if:fee_type,percentage|numeric|min:0|max:100',
            'fee_fixed' => 'required_if:fee_type,fixed|numeric|min:0|max:999999.99',
        ];

        // Add validation rules for each provider's limits
        foreach ($allProviders as $provider) {
            if (in_array($provider,$request->allowed_payment_providers['card']) || in_array($provider,$request->allowed_payment_providers['crypto'] )) {
                $validationRules["provider_limits.{$provider}.daily_limit"] = 'required|numeric|min:0|max:999999.99';
                $validationRules["provider_limits.{$provider}.monthly_limit"] = 'required|numeric|min:0|max:999999.99';
            }

        }

        $validated = $request->validate($validationRules);

        // Validate that monthly limits are not less than daily limits for each provider
        foreach ($allProviders as $provider) {
            $dailyLimit = $validated['provider_limits'][$provider]['daily_limit'] ?? 0;
            $monthlyLimit = $validated['provider_limits'][$provider]['monthly_limit'] ?? 0;

            if ($monthlyLimit < $dailyLimit) {
                return back()->withErrors(["provider_limits.{$provider}.monthly_limit" => "Monthly limit cannot be less than daily limit for {$provider}."]);
            }
        }

        // Check if reducing max_api_clients would affect existing clients
        $currentApiClientsCount = $user->apiClients()->count();
        if ($validated['max_api_clients'] < $currentApiClientsCount) {
            return back()->withErrors(['max_api_clients' => "Cannot reduce below current API clients count ({$currentApiClientsCount}). Please delete some API clients first."]);
        }

        $paymentSettings = $user->paymentSettings ?? new UserPaymentSettings(['user_id' => $user->id]);

        // Update basic settings
        $paymentSettings->max_api_clients = $validated['max_api_clients'];
        $paymentSettings->allowed_payment_providers = $validated['allowed_payment_providers'];
        $paymentSettings->is_active = $validated['is_active'] ?? false;

        // Update settlement fee settings
        $paymentSettings->fee_type = $validated['fee_type'];
        $paymentSettings->fee_percentage = $validated['fee_percentage'] ?? 0.00;
        $paymentSettings->fee_fixed = $validated['fee_fixed'] ?? 0.00;

        // Update provider limits
        $paymentSettings->provider_limits = $validated['provider_limits'];

        $paymentSettings->save();

        return redirect()
            ->route('admin.users.payment-settings', $user)
            ->with('success', 'Payment settings updated successfully.');
    }
}
