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
}
