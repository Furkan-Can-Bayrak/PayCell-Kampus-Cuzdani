<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService implements AuthServiceInterface
{
    public function __construct(AuthRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Kullanıcı kaydı oluşturur
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->createUser($data);
        });
    }

    /**
     * Kullanıcı girişi yapar
     *
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Kullanıcı çıkışı yapar
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Kullanıcı profilini günceller
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // Şifre varsa hash'le
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            return $this->repository->update($user->id, $data);
        });
    }
}