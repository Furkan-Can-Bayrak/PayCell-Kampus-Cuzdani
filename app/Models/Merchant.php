<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
    ];

    /**
     * Merchant'ın kategorisi
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
