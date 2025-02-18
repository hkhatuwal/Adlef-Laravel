<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'string'
    ];

    // Helper method to get setting value
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper method to set setting value
    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function settings()
    {
        return cache()->remember('settings.all', 60, function () {
            $settings = Setting::all();
            $settingsKeyValues = [];
            foreach ($settings as $setting) {
                $settingsKeyValues[$setting->key] = $setting->value;
            }
            return $settingsKeyValues;
        });
    }
}
