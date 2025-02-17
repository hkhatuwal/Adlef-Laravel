<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $settingsKeyValues=[];
        foreach ($settings as $setting) {
            $settingsKeyValues[$setting->key] = $setting->value;
        }
        return view('admin.settings.index', compact('settingsKeyValues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable'
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    public function createSetting(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable',
            'type' => 'required|string',
            'group' => 'required|string',
            'label' => 'required|string',
            'description' => 'nullable|string'
        ]);

        Setting::create($validated);

        return redirect()->back()->with('success', 'New setting created successfully');
    }
}
