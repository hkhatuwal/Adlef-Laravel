<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasLoginNotifications;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasLoginNotifications;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_admin',
        'email',
        'password',
        'account_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function businessDetails()
    {
        return $this->hasOne(BusinessDetail::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function cryptoWallets()
    {
        return $this->hasMany(CryptoWallet::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function contactDetails()
    {
        return $this->hasOne(ContactDetail::class);
    }

    public function areDetailsVerified(): bool
    {
        return isset($this->contactDetails) && $this->contactDetails->is_email_verified && $this->contactDetails->is_phone_verified;
    }

    public static function generateAccountNumber()
    {
        do {
            $accountNumber = now()->format('Ym') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('account_number', $accountNumber)->exists());

        return (int) $accountNumber;
    }

    /**
     * Get all asset accounts belonging to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assetAccounts()
    {
        return $this->hasMany(AssetAccount::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}
