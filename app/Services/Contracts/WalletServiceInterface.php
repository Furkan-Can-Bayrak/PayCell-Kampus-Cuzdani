<?php

namespace App\Services\Contracts;

use App\Models\Wallet;

interface WalletServiceInterface
{
    /**
     * Kullanıcının cüzdanını getirir
     *
     * @param int $userId
     * @return Wallet
     */
    public function getUserWallet(int $userId): Wallet;

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

    /**
     * Kullanıcıya para yükler
     *
     * @param int $userId
     * @param float $amount
     * @return Wallet
     */
    public function loadMoney(int $userId, float $amount): Wallet;
}
