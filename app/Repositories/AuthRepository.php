<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Email ile kullanıcı bulur
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Email ile kullanıcı bulur veya exception fırlatır
     *
     * @param string $email
     * @return User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByEmailOrFail(string $email): User
    {
        return $this->model->where('email', $email)->firstOrFail();
    }

    /**
     * Kullanıcı oluşturur ve şifreyi hash'ler
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->model->create($data);
    }

    /**
     * Kullanıcı şifresini günceller
     *
     * @param int $userId
     * @param string $password
     * @return User
     */
    public function updatePassword(int $userId, string $password): User
    {
        $user = $this->findByIdOrFail($userId);
        $user->update([
            'password' => Hash::make($password)
        ]);
        
        return $user;
    }

    /**
     * Kullanıcının email doğrulama durumunu günceller
     *
     * @param int $userId
     * @param \DateTime|null $verifiedAt
     * @return User
     */
    public function updateEmailVerification(int $userId, ?\DateTime $verifiedAt = null): User
    {
        $user = $this->findByIdOrFail($userId);
        $user->update([
            'email_verified_at' => $verifiedAt ?? now()
        ]);
        
        return $user;
    }
}
