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

class UserController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
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
}
