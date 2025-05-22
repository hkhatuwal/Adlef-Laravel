<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{

    const  NOTIFICATION_DOCUMENT_VERIFIED="document_verified";
    const  NOTIFICATION_DOCUMENT_VERIFICATION_FAILED="document_verification_failed";

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'alias',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'marital_status',
        'citizenship_id',
        'has_dual_citizenship',
        'current_occupation',
        'annual_income_range',
        'account_purpose',
        'funds_source',
        'wealth_source',
        'anticipated_asset_class',
        'third_party_contributions',
        'country_code',
        'phone_number',
        'phone_type',
        'street_address_1',
        'street_address_2',
        'city',
        'state_province',
        'postal_code',
        'country_id',
        'is_hong_kong_tax_resident',
        'tax_identification_number',
        'tin_not_provided_reason',
        'secondary_tax_country_id',
        'document_path',
        'document_verified',
        'agreement_accepted',
        'document_type',
        'avatar'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'has_dual_citizenship' => 'boolean',
        'third_party_contributions' => 'boolean',
        'is_hong_kong_tax_resident' => 'boolean',
        'agreement_accepted' => 'boolean',
        'document_verified' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullName():string{
        return $this->first_name.' '. $this->middle_name.' '. $this->last_name;
    }
}
