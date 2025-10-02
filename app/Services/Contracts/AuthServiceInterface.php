<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    /**
     * Kullanıcı kaydı oluşturur
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User;

    /**
     * Kullanıcı girişi yapar
     *
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials): bool;

    /**
     * Kullanıcı çıkışı yapar
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void;

    /**
     * Kullanıcı profilini günceller
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data): User;
}
