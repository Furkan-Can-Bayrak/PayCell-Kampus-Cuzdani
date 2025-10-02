<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface AuthRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Email ile kullanıcı bulur
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Email ile kullanıcı bulur veya exception fırlatır
     *
     * @param string $email
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByEmailOrFail(string $email): User;

    /**
     * Kullanıcı oluşturur ve şifreyi hash'ler
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User;

    /**
     * Kullanıcı şifresini günceller
     *
     * @param int $userId
     * @param string $password
     * @return User
     */
    public function updatePassword(int $userId, string $password): User;

    /**
     * Kullanıcının email doğrulama durumunu günceller
     *
     * @param int $userId
     * @param \DateTime|null $verifiedAt
     * @return User
     */
    public function updateEmailVerification(int $userId, ?\DateTime $verifiedAt = null): User;
}
