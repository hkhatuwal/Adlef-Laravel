<?php

namespace App\Notifications\Channels;

use App\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Mail;

class EmailChannel implements NotificationChannel
{
    public function supports(string $channel): bool
    {
        return $channel === 'email';
    }

    public function send(array $data): bool
    {
        try {
            $user = $data['user'];
            
            Mail::send('emails.notification', $data, function ($message) use ($user, $data) {
                $message->to($user->email)
                    ->subject($data['title']);
            });

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
} 