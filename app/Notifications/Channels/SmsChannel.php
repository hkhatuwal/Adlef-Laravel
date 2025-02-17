<?php

namespace App\Notifications\Channels;

use App\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Log;

class SmsChannel implements NotificationChannel
{
    public function supports(string $channel): bool
    {
        return $channel === 'sms';
    }

    public function send(array $data): bool
    {
        try {
            $user = $data['user'];
            
            // Here you would integrate with your SMS service provider
            // Example using a hypothetical SMS service:
            // $smsService->send($user->phone, $data['message']);
            
            // For now, we'll just log it
            Log::info('SMS Notification', [
                'to' => $user->contactDetails->phone ?? 'No phone number',
                'message' => $data['message']
            ]);

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
} 