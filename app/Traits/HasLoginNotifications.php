<?php

namespace App\Traits;

use App\Models\Notification;

trait HasLoginNotifications
{
    public function createLoginNotification(): void
    {
        Notification::create([
            'user_id' => $this->id,
            'type' => Notification::TYPE_INFO,
            'title' => 'New Login Detected',
            'message' => 'A new login was detected on your account. If this wasn\'t you, please contact support immediately.',
            'notifiable_type' => self::class,
            'notifiable_id' => $this->id,
            'metadata' => [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString()
            ]
        ]);
    }
} 