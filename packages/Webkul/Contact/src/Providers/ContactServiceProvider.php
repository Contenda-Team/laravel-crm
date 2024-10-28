<?php

namespace Webkul\Contact\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Contact\Models\PersonStatus;
use Webkul\Contact\Contracts\PersonStatus as PersonStatusContract;

class ContactServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            PersonStatusContract::class,
            PersonStatus::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
