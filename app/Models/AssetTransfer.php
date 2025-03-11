<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTransfer extends Model
{

    const  NOTIFICATION_TRANSFER_SUCCESS="asset_transfer_success";
    const  NOTIFICATION_TRANSFER_FAILED="asset_transfer_failed";

    const  NOTIFICATION_TRANSFER_IN_SUCCESS="asset_transfer_in_success";
    const  NOTIFICATION_TRANSFER_IN_FAILED="asset_transfer_in_success";
    const  TYPE_IN="in";
    const  TYPE_OUT="out";
    const  TYPE_THIRD_PARTY="third_party";
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'from_account_type',
        'to_account_type',
        'currency_id',
        'reference_number',
        'amount',
        'status',
        'fee',
        'transaction_cost',
        'transfer_type',
        'user_id',

    ];

    protected static function booted(): void
    {
        // Create activity record when transfer is created
        static::created(function ($transfer) {
            $transfer->recordActivity();
            $transfer->createNotification();
        });

        // Update activity record when transfer status changes
        static::updated(function ($transfer) {
            if ($transfer->isDirty('status')) {
                $transfer->updateActivity();
                $transfer->updateNotification();
            }
        });
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function from_account(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {

       return $this->morphTo(__FUNCTION__,'from_account_type','from_account_id');

    }
    public function to_account(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {

        return $this->morphTo(__FUNCTION__,'to_account_type','to_account_id');


    }

    public function activities()
    {
        return $this->morphMany(UserActivity::class, 'subject');
    }

    protected function recordActivity(): void
    {

        $description = $this->generateActivityDescription();

        UserActivity::create([
            'user_id' => $this->user_id,
            'activity_type' => UserActivity::TYPE_ASSET_TRANSFER,
            'action' => $this->transfer_type === self::TYPE_IN ?
                UserActivity::ACTION_TRANSFER_IN :
                UserActivity::ACTION_TRANSFER_OUT,
            'subject_type' => self::class,
            'subject_id' => $this->id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency_symbol' => $this->currency->symbol,
            'reference_number' => $this->reference_number,
            'description' => $description,
            'metadata' => [
                'fee' => $this->fee,
                'from_account_type' => class_basename($this->from_account_type),
                'to_account_type' => class_basename($this->to_account_type),
            ]
        ]);
    }

    protected function updateActivity(): void
    {
        $this->activities()
            ->latest()
            ->first()
            ->update([
                'status' => $this->status,
                'description' => $this->generateActivityDescription()
            ]);
    }

    protected function generateActivityDescription()
    {


        $amount = number_format($this->amount, 8) . ' ' . $this->currency->symbol;


        $fromModel= app($this->from_account_type)->find($this->from_account_id);
        $toModel= app($this->to_account_type)->find($this->to_account_id);
        $currency=Currency::find($this->currency_id);

        if ($this->transfer_type === self::TYPE_IN) {
            if ($currency->type==Currency::TYPE_CRYPTO){
                return "Transfer in {$amount}  to {$toModel->alias}";
            }
            return "Transfer in {$amount} from {$fromModel->bank_name} to {$toModel->name}";

        } else {
            if ($currency->type==Currency::TYPE_CRYPTO){
                return "Transfer out {$amount} from {$fromModel->name}  to {$toModel->alias}";
            }
            return "Transfer out {$amount} from {$fromModel->name} to {$toModel->bank_name}";
        }
    }

    protected function createNotification(): void
    {
        $amount = number_format($this->amount, 8) . ' ' . $this->currency->symbol;

        Notification::create([
            'user_id' => $this->user_id,
            'type' => Notification::TYPE_INFO,
            'title' => 'Transfer Initiated',
            'message' => "Your transfer of {$amount} has been initiated and is being processed.",
            'notifiable_type' => self::class,
            'notifiable_id' => $this->id,
            'metadata' => [
                'amount' => $this->amount,
                'currency' => $this->currency->symbol,
                'status' => $this->status,
                'transfer_type' => $this->transfer_type
            ]
        ]);
    }

    protected function updateNotification(): void
    {
        $amount = number_format($this->amount, 8) . ' ' . $this->currency->symbol;

        $type = match($this->status) {
            'completed' => Notification::TYPE_SUCCESS,
            'failed' => Notification::TYPE_ERROR,
            default => Notification::TYPE_INFO
        };

        $title = match($this->status) {
            'completed' => 'Transfer Completed',
            'failed' => 'Transfer Failed',
            'processing' => 'Transfer Processing',
            default => 'Transfer Status Updated'
        };

        $message = match($this->status) {
            'completed' => "Your transfer of {$amount} has been completed successfully.",
            'failed' => "Your transfer of {$amount} has failed. Please contact support.",
            'processing' => "Your transfer of {$amount} is being processed.",
            default => "Your transfer of {$amount} status has been updated to {$this->status}."
        };

        Notification::create([
            'user_id' => $this->user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'notifiable_type' => self::class,
            'notifiable_id' => $this->id,
            'metadata' => [
                'amount' => $this->amount,
                'currency' => $this->currency->symbol,
                'status' => $this->status,
                'transfer_type' => $this->transfer_type
            ]
        ]);
    }


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //
}
