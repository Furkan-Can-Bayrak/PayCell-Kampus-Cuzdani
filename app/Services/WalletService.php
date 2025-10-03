<?php

namespace App\Services;

use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;
use App\Services\Contracts\WalletServiceInterface;
use Illuminate\Support\Facades\DB;

class WalletService extends BaseService implements WalletServiceInterface
{
    public function __construct(WalletRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Kullanıcının cüzdanını getirir
     *
     * @param int $userId
     * @return Wallet
     */
    public function getUserWallet(int $userId): Wallet
    {
        return $this->repository->findOrCreateByUserId($userId);
    }

    /**
     * Cüzdan bakiyesini günceller
     *
     * @param int $walletId
     * @param float $amount
     * @return Wallet
     */
    public function updateBalance(int $walletId, float $amount): Wallet
    {
        return DB::transaction(function () use ($walletId, $amount) {
            return $this->repository->updateBalance($walletId, $amount);
        });
    }

    /**
     * Cüzdan bakiyesini artırır
     *
     * @param int $walletId
     * @param float $amount
     * @return Wallet
     */
    public function addBalance(int $walletId, float $amount): Wallet
    {
        return DB::transaction(function () use ($walletId, $amount) {
            return $this->repository->addBalance($walletId, $amount);
        });
    }

    /**
     * Kullanıcıya para yükler
     *
     * @param int $userId
     * @param float $amount
     * @return Wallet
     */
    public function loadMoney(int $userId, float $amount): Wallet
    {
        return DB::transaction(function () use ($userId, $amount) {
            $wallet = $this->repository->findOrCreateByUserId($userId);
            $previousBalance = $wallet->balance;
            $updatedWallet = $this->repository->addBalance($wallet->id, $amount);
            
            // Transaction kaydı oluştur
            \App\Models\Transaction::create([
                'user_id' => $userId,
                'merchant_id' => null,
                'amount' => $amount,
                'currency' => 'TRY',
                'type' => \App\Models\Transaction::TYPE_TOPUP,
                'meta' => [
                    'method' => 'wallet_topup',
                    'previous_balance' => $previousBalance,
                    'new_balance' => $updatedWallet->balance,
                ],
            ]);
            
            return $updatedWallet;
        });
    }

    /**
     * Arkadaşlara para gönderir
     *
     * @param int $senderId
     * @param array $friendIds
     * @param float $amount
     * @return array
     */
    public function sendMoney(int $senderId, array $friendIds, float $amount): array
    {
        return DB::transaction(function () use ($senderId, $friendIds, $amount) {
            $senderWallet = $this->repository->findOrCreateByUserId($senderId);
            $totalAmount = $amount * count($friendIds);
            
            // Gönderenin bakiyesinden düş
            $senderWallet = $this->repository->subtractBalance($senderWallet->id, $totalAmount);
            
            // Gönderen için transfer_out transaction
            \App\Models\Transaction::create([
                'user_id' => $senderId,
                'merchant_id' => null,
                'amount' => $totalAmount,
                'currency' => 'TRY',
                'type' => \App\Models\Transaction::TYPE_TRANSFER_OUT,
                'meta' => [
                    'recipient_count' => count($friendIds),
                    'amount_per_recipient' => $amount,
                    'recipient_ids' => $friendIds,
                ],
            ]);
            
            // Her arkadaşın bakiyesine ekle ve transaction oluştur
            foreach ($friendIds as $friendId) {
                $friendWallet = $this->repository->findOrCreateByUserId($friendId);
                $this->repository->addBalance($friendWallet->id, $amount);
                
                // Alıcı için transfer_in transaction
                \App\Models\Transaction::create([
                    'user_id' => $friendId,
                    'merchant_id' => null,
                    'amount' => $amount,
                    'currency' => 'TRY',
                    'type' => \App\Models\Transaction::TYPE_TRANSFER_IN,
                    'meta' => [
                        'sender_id' => $senderId,
                        'transfer_type' => 'friend_transfer',
                    ],
                ]);
            }
            
            return [
                'sender_balance' => $senderWallet->balance,
                'transferred_amount' => $totalAmount,
            ];
        });
    }

    /**
     * QR kod ile ödeme işlemi
     *
     * @param int $userId
     * @param float $amount
     * @param string $qrId
     * @param int $merchantId
     * @return array
     */
    public function processQrPayment(int $userId, float $amount, string $qrId, int $merchantId): array
    {
        return DB::transaction(function () use ($userId, $amount, $qrId, $merchantId) {
            $userWallet = $this->repository->findOrCreateByUserId($userId);
            $previousBalance = $userWallet->balance;
            
            // Kullanıcının bakiyesinden düş
            $userWallet = $this->repository->subtractBalance($userWallet->id, $amount);
            
            // Payment transaction kaydı oluştur
            \App\Models\Transaction::create([
                'user_id' => $userId,
                'merchant_id' => $merchantId,
                'amount' => $amount,
                'currency' => 'TRY',
                'type' => \App\Models\Transaction::TYPE_PAYMENT,
                'meta' => [
                    'qr_id' => $qrId,
                    'payment_method' => 'qr_code',
                    'previous_balance' => $previousBalance,
                    'new_balance' => $userWallet->balance,
                ],
            ]);
            
            \Log::info('QR Payment processed', [
                'user_id' => $userId,
                'merchant_id' => $merchantId,
                'amount' => $amount,
                'qr_id' => $qrId,
                'user_new_balance' => $userWallet->balance
            ]);
            
            return [
                'user_balance' => $userWallet->balance,
                'amount_paid' => $amount,
                'qr_id' => $qrId,
                'merchant_id' => $merchantId,
            ];
        });
    }
}
