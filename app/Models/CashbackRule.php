<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashbackRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_type', 'name', 'description', 'category_id', 'rate', 'flat_amount', 
        'cap', 'first_time_only', 'is_active', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'flat_amount' => 'decimal:2',
        'cap' => 'decimal:2',
        'first_time_only' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Rule types
    const TYPE_CATEGORY_PERCENTAGE = 'category_percentage';
    const TYPE_FIRST_QR_BONUS = 'first_qr_bonus';
    const TYPE_FLAT_CASHBACK = 'flat_cashback';

    /**
     * İlişkiler
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('rule_type', $type);
    }

    public function scopeCurrentlyValid($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
        });
    }

    /**
     * Accessors
     */
    public function getFormattedRateAttribute(): string
    {
        return $this->rate ? '%' . ($this->rate * 100) : '';
    }

    public function getFormattedAmountAttribute(): string
    {
        return $this->flat_amount ? '₺ ' . number_format($this->flat_amount, 2, ',', '.') : '';
    }

    /**
     * Kural aktif mi kontrol et
     */
    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }
        
        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }

        return true;
    }
}
