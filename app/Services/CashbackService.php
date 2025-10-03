<?php

namespace App\Services;

use App\Models\CashbackRule;
use App\Models\Transaction;
use App\Services\Contracts\CashbackServiceInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashbackService implements CashbackServiceInterface
{
    protected WalletRepositoryInterface $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * İşlem için uygulanabilir cashback kurallarını getir
     */
    public function getApplicableRules(Transaction $transaction): Collection
    {
        $rules = CashbackRule::active()
            ->currentlyValid()
            ->get()
            ->filter(function ($rule) use ($transaction) {
                // Kategori bazlı kurallar
                if ($rule->rule_type === CashbackRule::TYPE_CATEGORY_PERCENTAGE) {
                    return $transaction->merchant && 
                           $transaction->merchant->category_id === $rule->category_id;
                }

                // İlk QR ödeme bonusu - sadece QR ödemeleri için
                if ($rule->rule_type === CashbackRule::TYPE_FIRST_QR_BONUS) {
                    return $transaction->type === Transaction::TYPE_PAYMENT && 
                           isset($transaction->meta['qr_id']);
                }

                return false;
            });

        // First-time-only kuralları için kontrol
        return $rules->filter(function ($rule) use ($transaction) {
            if ($rule->first_time_only) {
                return !$this->hasUserUsedRule($transaction->user_id, $rule->id);
            }
            return true;
        });
    }

    /**
     * Cashback hesapla ve uygula
     */
    public function processCashback(Transaction $transaction): array
    {
        $applicableRules = $this->getApplicableRules($transaction);
        $totalCashback = 0;
        $appliedRules = [];

        if ($applicableRules->isEmpty()) {
            return [
                'total_cashback' => 0,
                'applied_rules' => [],
                'transactions_created' => [],
            ];
        }

        return DB::transaction(function () use ($transaction, $applicableRules, &$totalCashback, &$appliedRules) {
            $createdTransactions = [];

            foreach ($applicableRules as $rule) {
                $cashbackAmount = $this->calculateCashbackForRule($rule, $transaction);
                
                if ($cashbackAmount > 0) {
                    // Wallet'a cashback ekle
                    $wallet = $this->walletRepository->findOrCreateByUserId($transaction->user_id);
                    $updatedWallet = $this->walletRepository->addBalance($wallet->id, $cashbackAmount);

                    // Cashback transaction oluştur
                    $cashbackTransaction = Transaction::create([
                        'user_id' => $transaction->user_id,
                        'merchant_id' => null,
                        'amount' => $cashbackAmount,
                        'currency' => $transaction->currency,
                        'type' => Transaction::TYPE_CASHBACK,
                        'meta' => [
                            'original_transaction_id' => $transaction->id,
                            'cashback_rule_id' => $rule->id,
                            'rule_name' => $rule->name,
                            'rule_type' => $rule->rule_type,
                            'original_amount' => $transaction->amount,
                        ],
                    ]);

                    $totalCashback += $cashbackAmount;
                    $appliedRules[] = [
                        'rule_id' => $rule->id,
                        'rule_name' => $rule->name,
                        'cashback_amount' => $cashbackAmount,
                    ];
                    $createdTransactions[] = $cashbackTransaction;

                    Log::info('Cashback applied', [
                        'user_id' => $transaction->user_id,
                        'original_transaction_id' => $transaction->id,
                        'rule_id' => $rule->id,
                        'cashback_amount' => $cashbackAmount,
                        'new_balance' => $updatedWallet->balance,
                    ]);
                }
            }

            return [
                'total_cashback' => $totalCashback,
                'applied_rules' => $appliedRules,
                'transactions_created' => $createdTransactions,
            ];
        });
    }

    /**
     * Kullanıcının belirli bir kural için daha önce cashback alıp almadığını kontrol et
     */
    public function hasUserUsedRule(int $userId, int $ruleId): bool
    {
        return Transaction::where('user_id', $userId)
            ->where('type', Transaction::TYPE_CASHBACK)
            ->whereJsonContains('meta->cashback_rule_id', $ruleId)
            ->exists();
    }

    /**
     * Kategori bazlı cashback hesapla
     */
    public function calculateCategoryCashback(float $amount, float $rate, ?float $cap = null): float
    {
        $cashback = $amount * $rate;
        
        if ($cap !== null && $cashback > $cap) {
            $cashback = $cap;
        }

        return round($cashback, 2);
    }

    /**
     * Sabit tutar cashback hesapla
     */
    public function calculateFlatCashback(float $flatAmount): float
    {
        return round($flatAmount, 2);
    }

    /**
     * Belirli bir kural için cashback hesapla
     */
    protected function calculateCashbackForRule(CashbackRule $rule, Transaction $transaction): float
    {
        switch ($rule->rule_type) {
            case CashbackRule::TYPE_CATEGORY_PERCENTAGE:
                return $this->calculateCategoryCashback(
                    $transaction->amount, 
                    $rule->rate, 
                    $rule->cap
                );

            case CashbackRule::TYPE_FIRST_QR_BONUS:
                return $this->calculateFlatCashback($rule->flat_amount);

            case CashbackRule::TYPE_FLAT_CASHBACK:
                return $this->calculateFlatCashback($rule->flat_amount);

            default:
                return 0;
        }
    }
}
