<?php

namespace App\Providers;

use App\Core\Repositories\Contracts\EquityRepositoryInterface;
use App\Core\Repositories\EloquentEquityRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            EquityRepositoryInterface::class,
            EloquentEquityRepository::class
        );
    }
}
