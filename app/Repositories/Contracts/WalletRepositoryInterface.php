<?php

namespace App\Repositories\Contracts;

use App\Models\Wallet;

interface WalletRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Kullanıcının cüzdanını bulur
     *
     * @param int $userId
     * @return Wallet|null
     */
    public function findByUserId(int $userId): ?Wallet;

    /**
     * Kullanıcının cüzdanını bulur veya oluşturur
     *
     * @param int $userId
     * @return Wallet
     */
    public function findOrCreateByUserId(int $userId): Wallet;

    /**
     * Cüzdan bakiyesini günceller
     *
     * @param int $walletId
     * @param float $amount
     * @return Wallet
     */
    public function updateBalance(int $walletId, float $amount): Wallet;

    /**
     * Cüzdan bakiyesini artırır
     *
     * @param int $walletId
     * @param float $amount
     * @return Wallet
     */
    public function addBalance(int $walletId, float $amount): Wallet;
}
