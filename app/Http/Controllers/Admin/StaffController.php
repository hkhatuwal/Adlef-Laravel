<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        $staffMembers = User::role('staff')->latest()->paginate(10);
        return view('admin.staff.index', compact('staffMembers'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('staff');

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function edit(User $staff)
    {
        if (!$staff->hasRole('staff')) {
            abort(404);
        }
        
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        if (!$staff->hasRole('staff')) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'password' => $request->filled('password') ? ['confirmed', Password::defaults()] : [],
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $staff->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if (!$staff->hasRole('staff')) {
            abort(404);
        }

        $staff->removeRole('staff');
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
} 