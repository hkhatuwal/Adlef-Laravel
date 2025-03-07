<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminDepositAccount extends Model
{
    use HasFactory;

    public const TYPE_BANK = 'Bank';
    public const TYPE_WALLET = 'CryptoWallet';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'account_type'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function isBankAccount(): bool
    {
        return $this->account_type === self::TYPE_BANK;
    }

    public function isWallet(): bool
    {
        return $this->account_type === self::TYPE_WALLET;
    }

    public function fields()
    {
        return $this->hasMany(AdminDepositAccountField::class)->orderBy('display_order');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_admin_deposit_accounts')
            ->withTimestamps();
    }
}
