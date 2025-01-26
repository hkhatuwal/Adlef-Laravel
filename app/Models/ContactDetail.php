<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'user_id',
        'is_email_verified',
        'is_phone_verified',
        'user_id',
        'country_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
