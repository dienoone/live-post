<?php

namespace App\Providers;

use App\Subscribers\Models\UserEventSubscriber;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Route;

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

        Event::subscribe(UserEventSubscriber::class);

        # Explicit bindings
        // Route::bind('user', function ($value) {
        //     return 12345;
        // });
    }
}
