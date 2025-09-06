<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Settlement extends Model
{
    protected $fillable = [
        'user_id',
        'reference_id',
        'amount',
        'fee_amount',
        'cost',
        'net_amount',
        'status',
        'notes',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Method constants
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_PAYPAL = 'paypal';
    const METHOD_STRIPE = 'stripe';

    /**
     * Get the user that owns the settlement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the settlement
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Generate a unique reference ID
     */
    public static function generateReferenceId(): string
    {
        do {
            $referenceId = 'SETT-' . strtoupper(Str::random(8));
        } while (static::where('reference_id', $referenceId)->exists());

        return $referenceId;
    }

    /**
     * Create a new settlement request
     */
    public static function createRequest(array $data): self
    {
        return static::create([
            'user_id' => $data['user_id'],
            'reference_id' => static::generateReferenceId(),
            'amount' => $data['amount'],
            'fee_amount' => $data['fee_amount'],
            'net_amount' => $data['net_amount'],
            'status' => static::STATUS_PENDING,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Mark settlement as processing
     */
    public function markAsProcessing(int $adminId): void
    {
        $this->update([
            'status' => static::STATUS_PROCESSING,
            'processed_by' => $adminId,
        ]);
    }

    /**
     * Mark settlement as completed
     */
    public function markAsCompleted(int $adminId, ?string $adminNotes = null): void
    {
        DB::transaction(function () use ($adminId, $adminNotes) {
            // Update settlement status
            $this->update([
                'status' => static::STATUS_COMPLETED,
                'processed_by' => $adminId,
                'processed_at' => now(),
                'admin_notes' => $adminNotes,
            ]);

            // Transfer funds to user's USD AssetAccount
            $this->transferToAssetAccount();
        });
    }

    /**
     * Transfer settlement amount to user's USD AssetAccount
     */
    protected function transferToAssetAccount(): void
    {
        $user = $this->user;

        // Get USD currency
        $usdCurrency = Currency::where('symbol', 'USD')->first();
        if (!$usdCurrency) {
            throw new \Exception('USD currency not found');
        }

        // Get or create USD AssetAccount for user
        $assetAccount = AssetAccount::firstOrCreate(
            [
                'user_id' => $user->id,
                'currency_id' => $usdCurrency->id,
            ],
            [
                'name' => 'USD Account',
                'balance' => 0.00,
                'account_number' => AssetAccount::generateAccountNumber($usdCurrency, $user),
            ]
        );

        // Add net settlement amount to AssetAccount
        $assetAccount->increment('balance', $this->net_amount);

        // Deduct amount from PaymentGatewayWallets
        $this->deductFromGateways();
    }

    /**
     * Deduct settlement amount from payment gateway wallets
     */
    protected function deductFromGateways(): void
    {
        $remainingAmount = $this->amount;

        // Get all user's payment gateway wallets ordered by balance (largest first)
        $wallets = PaymentGatewayWallet::where('user_id', $this->user_id)
            ->where('balance_usd', '>', 0)
            ->orderBy('balance_usd', 'desc')
            ->get();

        foreach ($wallets as $wallet) {
            if ($remainingAmount <= 0) {
                break;
            }

            $deductAmount = min($remainingAmount, $wallet->balance_usd);

            if ($wallet->subtractBalance($deductAmount)) {
                $remainingAmount -= $deductAmount;

                // Log the deduction
                Log::info('Settlement wallet deduction', [
                    'settlement_id' => $this->id,
                    'reference_id' => $this->reference_id,
                    'wallet_id' => $wallet->id,
                    'gateway_name' => $wallet->gateway_name,
                    'deducted_amount' => $deductAmount,
                    'remaining_balance' => $wallet->balance_usd,
                    'remaining_settlement' => $remainingAmount
                ]);
            }
        }

        if ($remainingAmount > 0) {
            throw new \Exception('Insufficient wallet balance for settlement');
        }
    }

    /**
     * Mark settlement as failed
     */
    public function markAsFailed(int $adminId, string $reason): void
    {
        $this->update([
            'status' => static::STATUS_FAILED,
            'processed_by' => $adminId,
            'processed_at' => now(),
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Cancel settlement
     */
    public function cancel(int $adminId, string $reason): void
    {
        $this->update([
            'status' => static::STATUS_CANCELLED,
            'processed_by' => $adminId,
            'processed_at' => now(),
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            static::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            static::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            static::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            static::STATUS_FAILED => 'bg-red-100 text-red-800',
            static::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get method display name
     */
    public function getMethodDisplayName(): string
    {
        return match ($this->method) {
            static::METHOD_BANK_TRANSFER => 'Bank Transfer',
            static::METHOD_PAYPAL => 'PayPal',
            static::METHOD_STRIPE => 'Stripe',
            default => ucfirst($this->method),
        };
    }

    /**
     * Check if settlement can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [static::STATUS_PENDING, static::STATUS_PROCESSING]);
    }

    /**
     * Check if settlement is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === static::STATUS_COMPLETED;
    }

    /**
     * Check if settlement is pending
     */
    public function isPending(): bool
    {
        return $this->status === static::STATUS_PENDING;
    }
}
