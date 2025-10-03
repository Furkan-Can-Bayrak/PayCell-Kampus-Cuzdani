<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Split extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'requester_id', 
        'user_id',
        'weight',
        'share_amount',
        'status',
        'meta',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'share_amount' => 'decimal:2',
        'meta' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    // İlişkiler
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByRequester($query, int $requesterId)
    {
        return $query->where('requester_id', $requesterId);
    }

    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->share_amount, 2, ',', '.') . ' TRY';
    }
}
