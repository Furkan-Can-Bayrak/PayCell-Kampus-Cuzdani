<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(\App\Repositories\Contracts\WalletRepositoryInterface::class, \App\Repositories\WalletRepository::class);
        
        // Service bindings
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(\App\Services\Contracts\WalletServiceInterface::class, \App\Services\WalletService::class);
        $this->app->bind(\App\Services\Contracts\SplitServiceInterface::class, \App\Services\SplitService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
