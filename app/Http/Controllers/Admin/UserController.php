<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\BankAccount;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Currency;

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
                'notifiable_type' => UserProfile::class,
                'notifiable_id' => $userProfile->id,
                'metadata' => [
                    'document_type' => $userProfile->document_type ?? 'Identity Document',
                    'verified_at' => now()->format('Y-m-d H:i:s'),
                    'verified_by' => auth()->user()->name,
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
                    'unverified_by' => auth()->user()->name,
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
                'notifiable_type' => BankAccount::class,
                'notifiable_id' => $bankAccount->id,
                'metadata' => [
                    'bank_name' => $bankAccount->bank_name,
                    'account_number' => $bankAccount->account_number,
                    'verified_at' => now()->format('Y-m-d H:i:s'),
                    'verified_by' => auth()->user()->name
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
        $this->notificationService->notify(
            $bankAccount->user,
            [
                'type' => 'warning',
                'title' => 'Bank Account Verification Revoked',
                'message' => "The verification status of your bank account ({$bankAccount->bank_name} - {$bankAccount->account_number}) has been revoked. Please contact support for more information.",
                'notifiable_type' => BankAccount::class,
                'notifiable_id' => $bankAccount->id,
                'metadata' => [
                    'bank_name' => $bankAccount->bank_name,
                    'account_number' => $bankAccount->account_number,
                    'unverified_at' => now()->format('Y-m-d H:i:s'),
                    'unverified_by' => auth()->user()->name,
                ]
            ],
            ['database', 'email'] // Send through database and email
        );

        return back()->with('success', 'Bank account verification has been revoked.');
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
            'commissions.*.currency_id' => ['required', 'exists:currencies,id'],
            'commissions.*.commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($request->commissions as $commission) {
            $user->commissions()->updateOrCreate(
                ['currency_id' => $commission['currency_id']],
                ['commission_rate' => $commission['commission_rate']]
            );
        }

        return redirect()->route('admin.users.commissions', $user)
            ->with('success', 'Commission rates updated successfully');
    }
}
