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

    /**
     * Arkadaşlara para gönderir
     *
     * @param int $senderId
     * @param array $friendIds
     * @param float $amount
     * @return array
     */
    public function sendMoney(int $senderId, array $friendIds, float $amount): array;

    /**
     * QR kod ile ödeme işlemi
     *
     * @param int $userId
     * @param float $amount
     * @param string $qrId
     * @param int $merchantId
     * @return array
     */
    public function processQrPayment(int $userId, float $amount, string $qrId, int $merchantId): array;
}
