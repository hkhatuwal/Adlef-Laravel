<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminDepositAccountField extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_deposit_account_id',
        'field_name',
        'field_value',
        'field_type',
        'is_required',
        'display_order'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'display_order' => 'integer'
    ];

    public function depositAccount()
    {
        return $this->belongsTo(AdminDepositAccount::class);
    }
}
