<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    protected $fillable = [
        'registration_no',
        'registration_date',
        'country',
        'user_id'
    ];

    protected $casts = [
        'registration_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
