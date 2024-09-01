<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'business_name',
        'job_title',
        'business_email',
        'phone',
        'fx_services',
        'asset_servicing',
        'settlement_clearing',
        'treasury_services',
        'other',
        'project_description',
        'how_did_you_hear',
    ];
}
