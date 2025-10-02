<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
    }

    /**
     * Kullanıcının cüzdanını bulur
     *
     * @param int $userId
     * @return Wallet|null
     */
    public function findByUserId(int $userId): ?Wallet
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Kullanıcının cüzdanını bulur veya oluşturur
     *
     * @param int $userId
     * @return Wallet
     */
    public function findOrCreateByUserId(int $userId): Wallet
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0.00]
        );
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
        $wallet = $this->findByIdOrFail($walletId);
        $wallet->update(['balance' => $amount]);
        return $wallet;
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
        $wallet = $this->findByIdOrFail($walletId);
        $wallet->increment('balance', $amount);
        return $wallet->fresh();
    }

    /**
     * Cüzdan bakiyesini azaltır
     *
     * @param int $walletId
     * @param float $amount
     * @return Wallet
     */
    public function subtractBalance(int $walletId, float $amount): Wallet
    {
        $wallet = $this->findByIdOrFail($walletId);
        $wallet->decrement('balance', $amount);
        return $wallet->fresh();
    }
}
