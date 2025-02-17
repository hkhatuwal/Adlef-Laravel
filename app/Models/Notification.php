<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'error';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'is_read',
        'notifiable_type',
        'notifiable_id',
        'metadata'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'metadata' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
} 