<?php

namespace App\Services;

use App\Models\Split;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Contracts\SplitServiceInterface;
use App\Services\Contracts\WalletServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SplitService extends BaseService implements SplitServiceInterface
{
    protected WalletServiceInterface $walletService;

    public function __construct(WalletServiceInterface $walletService)
    {
        $this->walletService = $walletService;
    }

    public function createSplitRequest(int $transactionId, int $requesterId, array $friends, string $shareType = 'equal'): Collection
    {
        return DB::transaction(function () use ($transactionId, $requesterId, $friends, $shareType) {
            $transaction = Transaction::findOrFail($transactionId);
            $totalAmount = $transaction->amount;
            $totalPeople = count($friends) + 1; // +1 for requester
            
            $splits = collect();
            
            foreach ($friends as $friend) {
                $shareAmount = $this->calculateShareAmount($totalAmount, $totalPeople, $friend, $shareType);
                
                $split = Split::create([
                    'transaction_id' => $transactionId,
                    'requester_id' => $requesterId,
                    'user_id' => $friend['id'],
                    'weight' => $friend['weight'] ?? 1.00,
                    'share_amount' => $shareAmount,
                    'status' => Split::STATUS_PENDING,
                    'meta' => [
                        'share_type' => $shareType,
                        'friend_name' => $friend['name'],
                        'merchant_name' => $transaction->merchant->name ?? 'Bilinmeyen',
                        'original_amount' => $totalAmount,
                        'created_at' => now()->toISOString(),
                    ],
                ]);
                
                $splits->push($split);
            }
            
            Log::info('Split request created', [
                'transaction_id' => $transactionId,
                'requester_id' => $requesterId,
                'friends_count' => count($friends),
                'total_amount' => $totalAmount,
                'share_type' => $shareType,
            ]);
            
            return $splits;
        });
    }

    public function acceptSplit(int $splitId, int $userId): array
    {
        return DB::transaction(function () use ($splitId, $userId) {
            $split = Split::with(['transaction', 'requester'])
                ->where('id', $splitId)
                ->where('user_id', $userId)
                ->where('status', Split::STATUS_PENDING)
                ->firstOrFail();
            
            // Split'i kabul et
            $split->update([
                'status' => Split::STATUS_ACCEPTED,
                'meta' => array_merge($split->meta ?? [], [
                    'accepted_at' => now()->toISOString(),
                ]),
            ]);
            
            // Para transferi yap
            $userWallet = $this->walletService->getUserWallet($userId);
            $requesterWallet = $this->walletService->getUserWallet($split->requester_id);
            
            // Kullanıcıdan para düş
            $userWallet = $this->walletService->subtractBalance($userWallet->id, $split->share_amount);
            
            // İstek sahibine para ekle
            $requesterWallet = $this->walletService->addBalance($requesterWallet->id, $split->share_amount);
            
            // Transaction kayıtları oluştur
            $this->createSplitTransactions($split, $userWallet->balance, $requesterWallet->balance);
            
            Log::info('Split accepted and processed', [
                'split_id' => $splitId,
                'user_id' => $userId,
                'requester_id' => $split->requester_id,
                'amount' => $split->share_amount,
                'user_new_balance' => $userWallet->balance,
                'requester_new_balance' => $requesterWallet->balance,
            ]);
            
            return [
                'split' => $split->fresh(),
                'user_balance' => $userWallet->balance,
                'requester_balance' => $requesterWallet->balance,
                'transferred_amount' => $split->share_amount,
            ];
        });
    }

    public function rejectSplit(int $splitId, int $userId): Split
    {
        $split = Split::where('id', $splitId)
            ->where('user_id', $userId)
            ->where('status', Split::STATUS_PENDING)
            ->firstOrFail();
        
        $split->update([
            'status' => Split::STATUS_REJECTED,
            'meta' => array_merge($split->meta ?? [], [
                'rejected_at' => now()->toISOString(),
            ]),
        ]);
        
        Log::info('Split rejected', [
            'split_id' => $splitId,
            'user_id' => $userId,
            'requester_id' => $split->requester_id,
            'amount' => $split->share_amount,
        ]);
        
        return $split->fresh();
    }

    public function getUserPendingSplits(int $userId): Collection
    {
        return Split::with(['transaction.merchant', 'requester'])
            ->forUser($userId)
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function calculateShareAmount(float $totalAmount, int $totalPeople, array $friend, string $shareType): float
    {
        switch ($shareType) {
            case 'equal':
                return $totalAmount / $totalPeople;
            case 'percentage':
                return $totalAmount * ($friend['percentage'] ?? 0) / 100;
            case 'custom':
                return $friend['amount'] ?? 0;
            default:
                return $totalAmount / $totalPeople;
        }
    }

    private function createSplitTransactions(Split $split, float $userNewBalance, float $requesterNewBalance): void
    {
        // Kullanıcıdan para düşme transaction'ı
        Transaction::create([
            'user_id' => $split->user_id,
            'merchant_id' => null,
            'amount' => $split->share_amount,
            'currency' => 'TRY',
            'type' => Transaction::TYPE_TRANSFER_OUT,
            'meta' => [
                'split_id' => $split->id,
                'split_payment' => true,
                'recipient_id' => $split->requester_id,
                'recipient_name' => $split->requester->name . ' ' . $split->requester->surname,
                'original_transaction_id' => $split->transaction_id,
                'merchant_name' => $split->meta['merchant_name'] ?? 'Bilinmeyen',
                'previous_balance' => $userNewBalance + $split->share_amount,
                'new_balance' => $userNewBalance,
            ],
        ]);

        // İstek sahibine para ekleme transaction'ı
        Transaction::create([
            'user_id' => $split->requester_id,
            'merchant_id' => null,
            'amount' => $split->share_amount,
            'currency' => 'TRY',
            'type' => Transaction::TYPE_TRANSFER_IN,
            'meta' => [
                'split_id' => $split->id,
                'split_payment' => true,
                'sender_id' => $split->user_id,
                'sender_name' => $split->user->name . ' ' . $split->user->surname,
                'original_transaction_id' => $split->transaction_id,
                'merchant_name' => $split->meta['merchant_name'] ?? 'Bilinmeyen',
                'previous_balance' => $requesterNewBalance - $split->share_amount,
                'new_balance' => $requesterNewBalance,
            ],
        ]);
    }
}
