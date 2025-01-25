<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'country',
        'state',
        'postal_code',
        'city',
        'address_line1',
        'address_line2',
        'user_id',
        'individual_id',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
