<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\MigrationsEnded;

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
        // Automatically refresh Model PHPDocs after migrations
        Event::listen(MigrationsEnded::class, function () {
            Artisan::call('ide-helper:models', [
                '--write' => true,
                '--reset' => true,
            ]);
        });
    }
}
