<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OppwaTransaction extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    const PAYMENT_TYPE_DEBIT = 'DB';
    const PAYMENT_TYPE_PREAUTHORIZATION = 'PA';

    protected $fillable = [
        'transaction_id',
        'external_order_id',
        'oppwa_checkout_id',
        'oppwa_payment_id',
        'api_client_id',
        'amount',
        'currency',
        'payment_type',
        'description',
        'customer_email',
        'customer_name',
        'customer_phone',
        'payment_url',
        'result_url',
        'callback_url',
        'status',
        'oppwa_status',
        'oppwa_response',
        'failure_reason',
        'metadata',
        'ip_address',
        'user_agent',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'oppwa_response' => 'array',
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the API client that owns this transaction
     */
    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId(): string
    {
        do {
            $id = 'oppwa_' . Str::random(16);
        } while (self::where('transaction_id', $id)->exists());

        return $id;
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Check if transaction has failed
     */
    public function hasFailed(): bool
    {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_EXPIRED]);
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED ||
            ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Update transaction status
     */
    public function updateStatus(string $status, array $oppwaResponse = null, string $failureReason = null): void
    {
        $updateData = ['status' => $status];

        if ($oppwaResponse) {
            $updateData['oppwa_response'] = $oppwaResponse;
            $updateData['oppwa_status'] = $oppwaResponse['result']['code'] ?? null;
            $updateData['oppwa_payment_id'] = $oppwaResponse['id'] ?? null;
        }

        if ($failureReason) {
            $updateData['failure_reason'] = $failureReason;
        }

        Log::info("OPPWA Transaction Status Updated", [
            'transaction_id' => $this->transaction_id,
            'status' => $status,
            'oppwa_status' => $updateData['oppwa_status'] ?? null
        ]);

        $this->update($updateData);
    }

    /**
     * Get transaction details for API response
     */
    public function toApiResponse(): array
    {
        return [
            'transaction_id' => $this->transaction_id,
            'external_order_id' => $this->external_order_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'customer_email' => $this->customer_email,
            'customer_name' => $this->customer_name,
            'payment_url' => $this->payment_url,
            'result_url' => $this->result_url,
            'callback_url' => $this->callback_url,
            'oppwa_checkout_id' => $this->oppwa_checkout_id,
            'oppwa_payment_id' => $this->oppwa_payment_id,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }

    /**
     * Get webhook payload
     */
    public function getWebhookPayload(): array
    {
        return [
            'event' => 'oppwa.payment.' . $this->status,
            'transaction_id' => $this->transaction_id,
            'external_order_id' => $this->external_order_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'oppwa_checkout_id' => $this->oppwa_checkout_id,
            'oppwa_payment_id' => $this->oppwa_payment_id,
            'customer_email' => $this->customer_email,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }

    /**
     * Check if OPPWA response indicates success
     */
    public static function isOppwaResponseSuccessful(array $response): bool
    {
        if (!isset($response['result']['code'])) {
            return false;
        }

        $code = $response['result']['code'];
        return self::isSuccessfulResultCode($code);
    }

    /**
     * Check if OPPWA response indicates pending status
     */
    public static function isOppwaResponsePending(array $response): bool
    {
        if (!isset($response['result']['code'])) {
            return false;
        }

        $code = $response['result']['code'];
        return self::isPendingResultCode($code);
    }

    /**
     * Check if OPPWA response indicates failure
     */
    public static function isOppwaResponseFailed(array $response): bool
    {
        if (!isset($response['result']['code'])) {
            return true; // If no code, consider it failed
        }

        $code = $response['result']['code'];
        return self::isFailedResultCode($code);
    }

    /**
     * Determine transaction status from OPPWA response
     */
    public static function determineStatusFromOppwaResponse(array $response): string
    {
        if (!isset($response['result']['code'])) {
            return self::STATUS_FAILED;
        }

        $code = $response['result']['code'];

        if (self::isSuccessfulResultCode($code)) {
            return self::STATUS_COMPLETED;
        }

        if (self::isPendingResultCode($code)) {
            return self::STATUS_PENDING;
        }

        return self::STATUS_FAILED;
    }

    /**
     * Check if result code indicates successful transaction
     * Pattern: /^(000\.000\.|000\.100\.1|000\.[36])/
     */
    public static function isSuccessfulResultCode(string $code): bool
    {
        return preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $code) === 1;
    }

    /**
     * Check if result code indicates pending transaction
     * Pattern: /^(000\.200|800\.400\.5|100\.400\.500)/
     */
    public static function isPendingResultCode(string $code): bool
    {
        return preg_match('/^(000\.200|800\.400\.5|100\.400\.500)/', $code) === 1;
    }

    /**
     * Check if result code indicates failed transaction
     * Any code not matching success or pending patterns
     */
    public static function isFailedResultCode(string $code): bool
    {
        return !self::isSuccessfulResultCode($code) && !self::isPendingResultCode($code);
    }

    /**
     * Check if result code requires manual review
     * Pattern: /^(000\.400\.0[^3]|000\.400\.100)/
     */
    public static function requiresManualReview(string $code): bool
    {
        return preg_match('/^(000\.400\.0[^3]|000\.400\.100)/', $code) === 1;
    }

    /**
     * Get OPPWA status description based on result code
     */
    public static function getOppwaStatusDescription(string $code): string
    {
        $descriptions = self::getResultCodeDescriptions();
        return $descriptions[$code] ?? 'Unknown status code: ' . $code;
    }

    /**
     * Get comprehensive result code descriptions
     */
    public static function getResultCodeDescriptions(): array
    {
        return [
            // Successful transactions
            '000.000.000' => 'Transaction succeeded',
            '000.000.100' => 'Successful request',
            '000.100.110' => 'Request successfully processed in Merchant in Integrator Test Mode',
            '000.100.111' => 'Request successfully processed in Merchant in Validator Test Mode',
            '000.100.112' => 'Request successfully processed in Merchant in Connector Test Mode',
            '000.300.000' => 'Two-step transaction succeeded',
            '000.300.100' => 'Risk check successful',
            '000.300.101' => 'Risk bank account check successful',
            '000.300.102' => 'Risk report successful',
            '000.310.100' => 'Account updated',
            '000.310.101' => 'Account updated (Credit card expired)',
            '000.310.110' => 'No updates found, but account is valid',
            '000.600.000' => 'Transaction succeeded due to external update',

            // Success with manual review required
            '000.400.000' => 'Transaction succeeded (please review manually due to fraud suspicion)',
            '000.400.010' => 'Transaction succeeded (please review manually due to AVS return code)',
            '000.400.020' => 'Transaction succeeded (please review manually due to CVV return code)',
            '000.400.040' => 'Transaction succeeded (please review manually due to amount mismatch)',
            '000.400.050' => 'Transaction succeeded (please review manually because transaction is pending)',
            '000.400.060' => 'Transaction succeeded (approved at merchant\'s risk)',
            '000.400.070' => 'Transaction succeeded (waiting for external risk review)',
            '000.400.080' => 'Transaction succeeded (please review manually because the service was unavailable)',
            '000.400.090' => 'Transaction succeeded (please review manually due to external risk check)',
            '000.400.100' => 'Transaction succeeded, risk after payment rejected',

            // Pending transactions
            '000.200.000' => 'Transaction pending',
            '000.200.100' => 'Successfully created checkout',
            '000.200.101' => 'Successfully updated checkout',
            '000.200.102' => 'Successfully deleted checkout',
            '000.200.200' => 'Transaction initialized',
            '100.400.500' => 'Waiting for external risk',
            '800.400.500' => 'Waiting for confirmation of non-instant payment. Denied for now.',
            '800.400.501' => 'Waiting for confirmation of non-instant debit. Denied for now.',
            '800.400.502' => 'Waiting for confirmation of non-instant refund. Denied for now.',

            // 3D Secure and risk check failures
            '000.400.101' => 'Card not participating/authentication unavailable',
            '000.400.102' => 'User not enrolled',
            '000.400.103' => 'Technical Error in 3D system',
            '000.400.104' => 'Missing or malformed 3DSecure Configuration for Channel',
            '000.400.105' => 'Unsupported User Device - Authentication not possible',
            '000.400.106' => 'Invalid payer authentication response(PARes) in 3DSecure Transaction',
            '000.400.107' => 'Communication Error to VISA/Mastercard Directory Server',
            '000.400.108' => 'Cardholder Not Found - card number provided is not found in the ranges of the issuer',
            '000.400.200' => 'Risk management check communication error',

            // Bank declined transactions
            '800.100.153' => 'Bank declined - CVV is wrong',
            '800.100.154' => 'Bank declined - Card expired',
            '800.100.155' => 'Bank declined - Insufficient funds',
            '800.100.156' => 'Bank declined - Card blocked',
            '800.100.157' => 'Bank declined - Invalid card number',
            '800.100.158' => 'Bank declined - Card not supported',
            '800.100.159' => 'Bank declined - Transaction not permitted',
            '800.100.160' => 'Bank declined - Exceeded withdrawal limit',
            '800.100.161' => 'Bank declined - Exceeded withdrawal frequency',
            '800.100.162' => 'Bank declined - Invalid PIN',
            '800.100.163' => 'Bank declined - PIN tries exceeded',
            '800.100.164' => 'Bank declined - Card lost',
            '800.100.165' => 'Bank declined - Card stolen',
            '800.100.166' => 'Bank declined - Cardholder request',
            '800.100.167' => 'Bank declined - Do not honor',
            '800.100.168' => 'Bank declined - Pick up card',
            '800.100.169' => 'Bank declined - Refer to card issuer',
            '800.100.170' => 'Bank declined - Restricted card',
            '800.100.171' => 'Bank declined - Security violation',
            '800.100.172' => 'Bank declined - Service not allowed',
            '800.100.173' => 'Bank declined - Stop payment',
            '800.100.174' => 'Bank declined - Transaction not permitted',
            '800.100.175' => 'Bank declined - Try again later',
            '800.100.176' => 'Bank declined - Wrong PIN',

            // System errors
            '800.500.100' => 'Internal error',
            '800.500.101' => 'Internal error - Timeout',
            '800.500.102' => 'Internal error - Service unavailable',
            '800.500.103' => 'Internal error - Database error',
            '800.500.104' => 'Internal error - Configuration error',
            '800.500.105' => 'Internal error - Communication error',

            // Validation errors
            '100.100.100' => 'Request contains no creditcard, bank account number or bank name',
            '100.100.101' => 'Invalid creditcard, bank account number or bank name',
            '100.100.200' => 'Request contains no month',
            '100.100.201' => 'Invalid month',
            '100.100.300' => 'Request contains no year',
            '100.100.301' => 'Invalid year',
            '100.100.303' => 'Card expired',
            '100.100.304' => 'Card not yet valid',
            '100.100.400' => 'Request contains no cc/bank account holder',
            '100.100.401' => 'Cc/bank account holder too short or too long',
            '100.100.500' => 'Request contains no credit card brand',
            '100.100.501' => 'Invalid credit card brand',
            '100.100.600' => 'Empty CVV for VISA,MASTER, AMEX not allowed',
            '100.100.601' => 'Invalid CVV/brand combination',

            // Amount validation errors
            '100.550.300' => 'Request contains no amount or too low amount',
            '100.550.301' => 'Amount too large',
            '100.550.303' => 'Amount format invalid (only two decimals allowed)',
            '100.550.310' => 'Amount exceeds limit for the registered account',
            '100.550.311' => 'Exceeding account balance',
            '100.550.400' => 'Request contains no currency',
            '100.550.401' => 'Invalid currency',

            // Contact validation errors
            '100.700.100' => 'Customer surname may not be null',
            '100.700.200' => 'Customer givenName may not be null',
            '100.900.100' => 'Request contains no email address',
            '100.900.101' => 'Invalid email address (probably invalid syntax)',
            '100.900.200' => 'Invalid phone number',
            '100.900.300' => 'Invalid mobile phone number',

            // Address validation errors
            '100.800.100' => 'Request contains no street',
            '100.800.200' => 'Request contains no zip',
            '100.800.300' => 'Request contains no city',
            '100.800.400' => 'Invalid state/country combination',
            '100.800.500' => 'Request contains no country',

            // Authentication errors
            '800.900.100' => 'Sender authorization failed',
            '800.900.200' => 'Invalid phone number',
            '800.900.300' => 'Invalid authentication information',
            '800.900.301' => 'User authorization failed, user has no sufficient rights to process transaction',
            '800.900.302' => 'Authorization failed',
            '800.900.303' => 'No token created',
            '800.900.399' => 'Secure Registration Problem',
            '800.900.401' => 'Invalid IP number',
            '800.900.450' => 'Invalid birthdate',

            // Request validation errors
            '200.100.100' => 'Invalid Request/Transaction/Customer tag (not present or [partially] empty)',
            '200.100.101' => 'Invalid Request/Transaction/Customer/Contact tag (not present or [partially] empty)',
            '200.100.102' => 'Invalid Request/Transaction/Customer/Address tag (not present or [partially] empty)',
            '200.100.106' => 'Duplicate transaction. Please verify that the UUID is unique',
            '200.300.403' => 'Invalid HTTP method',
            '200.300.404' => 'Invalid or missing parameter',
            '200.300.405' => 'Duplicate entity',
            '200.300.406' => 'Entity not found',
            '200.300.407' => 'Entity not specific enough',
        ];
    }

    /**
     * Get result code category
     */
    public static function getResultCodeCategory(string $code): string
    {
        if (self::isSuccessfulResultCode($code)) {
            return 'success';
        }

        if (self::isPendingResultCode($code)) {
            return 'pending';
        }

        if (self::requiresManualReview($code)) {
            return 'manual_review';
        }

        return 'failed';
    }
}
