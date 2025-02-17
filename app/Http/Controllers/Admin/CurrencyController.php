<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetAccount;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('admin.currencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:10', 'unique:currencies,symbol'],
            'conversion_rate' => ['required', 'numeric', 'min:0'],
            'type' => ['required', Rule::in([Currency::TYPE_FIAT, Currency::TYPE_CRYPTO])],
            'icon' => ['required', 'image', 'max:2048'], // Max 2MB
        ]);

        try {
            DB::beginTransaction();

            // Store the icon
            $iconPath = $request->file('icon')->store('logo', 'public');
            $validated['icon'] = $iconPath;

            // Create currency
            $currency = Currency::create($validated);

            // Create asset accounts for all users
            $users = User::all();
            foreach ($users as $user) {
                AssetAccount::create([
                    'name' => $currency->name . ' Account',
                    'currency_id' => $currency->id,
                    'user_id' => $user->id,
                    'balance' => 0,
                    'account_number' => AssetAccount::generateAccountNumber($currency,$user),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.currencies.index')->with('success', 'Currency created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create currency: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:10', Rule::unique('currencies')->ignore($currency->id)],
            'conversion_rate' => ['required', 'numeric', 'min:0'],
            'type' => ['required', Rule::in([Currency::TYPE_FIAT, Currency::TYPE_CRYPTO])],
            'icon' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        try {
            if ($request->hasFile('icon')) {
                // Store the new icon
                $iconPath = $request->file('icon')->store('logo', 'public');
                $validated['icon'] = $iconPath;
            }

            $currency->update($validated);
            return redirect()->route('admin.currencies.index')->with('success', 'Currency updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update currency: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Currency $currency)
    {
        try {
            DB::beginTransaction();

            // Delete associated asset accounts
            AssetAccount::where('currency_id', $currency->id)->delete();

            // Delete the currency
            $currency->delete();

            DB::commit();
            return redirect()->route('admin.currencies.index')->with('success', 'Currency deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete currency: ' . $e->getMessage());
        }
    }
}
