<?php

namespace App\Notifications\Channels;

use App\Contracts\NotificationChannel;
use App\Mail\DefaultNotification;
use App\Mail\DocumentVerified;
use App\Mail\NewBankAccountAdded;
use App\Mail\OtcTransferFailed;
use App\Mail\OtcTransferHold;
use App\Mail\OtcTransferSuccess;
use App\Mail\TransferinFailed;
use App\Mail\TransferinSuccess;
use App\Mail\TransferOutFailed;
use App\Mail\TransferOutSuccess;
use App\Models\AssetTransfer;
use App\Models\BankAccount;
use App\Models\OtcRequest;
use App\Models\UserProfile;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
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
            if (!isset($data['user'])) {
                Log::error("Email notification missing user data");
                return false;
            }

            $user = $data['user'];
            Log::debug("Send email");
            Log::debug(json_encode($data));

            Mail::to($user)
                ->send($this->getMail($data));

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function getMail(array $data): Mailable
    {
        if (!isset($data['notifiable_type'])) {
            Log::error("Email notification missing notifiable_type");
            return new TransferinSuccess($data); // Default fallback
        }

        $type = $data['notifiable_type'];

        if ($type == BankAccount::NOTIFICATION_TYPE_BANK_ACCOUNT_VERIFIED) {
            return new NewBankAccountAdded($data);
        }
        if ($type == OtcRequest::NOTIFICATION_OTC_SUCCESS) {
            return new OtcTransferSuccess($data);
        }
        if ($type == OtcRequest::NOTIFICATION_OTC_FAILED) {
            return new OtcTransferFailed($data);
        }
        if ($type == OtcRequest::NOTIFICATION_OTC_HOLD) {
            return new OtcTransferHold($data);
        }
        if ($type == UserProfile::NOTIFICATION_DOCUMENT_VERIFIED) {
            return new DocumentVerified($data);
        }

        if ($type == AssetTransfer::NOTIFICATION_TRANSFER_IN_SUCCESS) {
            return new TransferinSuccess($data);
        }

        if ($type == AssetTransfer::NOTIFICATION_TRANSFER_IN_FAILED) {
            return new TransferinFailed($data);
        }

        if ($type == AssetTransfer::NOTIFICATION_TRANSFER_SUCCESS) {
            return new TransferOutSuccess($data);
        }

        if ($type == AssetTransfer::NOTIFICATION_TRANSFER_FAILED) {
            return new TransferOutFailed($data);
        }

        // Default fallback for unhandled notification types
        return new DefaultNotification($data);
    }
}
