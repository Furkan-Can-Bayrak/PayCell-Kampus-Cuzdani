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
            return $this->repository->addBalance($wallet->id, $amount);
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
            
            // Her arkadaşın bakiyesine ekle
            foreach ($friendIds as $friendId) {
                $friendWallet = $this->repository->findOrCreateByUserId($friendId);
                $this->repository->addBalance($friendWallet->id, $amount);
            }
            
            return [
                'sender_balance' => $senderWallet->balance,
                'transferred_amount' => $totalAmount,
            ];
        });
    }
}
