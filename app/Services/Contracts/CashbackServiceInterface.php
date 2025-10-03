<?php

namespace App\Services\Contracts;

use App\Models\Transaction;
use Illuminate\Support\Collection;

interface CashbackServiceInterface
{
    /**
     * İşlem için uygulanabilir cashback kurallarını getir
     */
    public function getApplicableRules(Transaction $transaction): Collection;

    /**
     * Cashback hesapla ve uygula
     */
    public function processCashback(Transaction $transaction): array;

    /**
     * Kullanıcının belirli bir kural için daha önce cashback alıp almadığını kontrol et
     */
    public function hasUserUsedRule(int $userId, int $ruleId): bool;

    /**
     * Kategori bazlı cashback hesapla
     */
    public function calculateCategoryCashback(float $amount, float $rate, ?float $cap = null): float;

    /**
     * Sabit tutar cashback hesapla
     */
    public function calculateFlatCashback(float $flatAmount): float;
}
