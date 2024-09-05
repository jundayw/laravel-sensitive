<?php

namespace Jundayw\LaravelSensitive;

use Illuminate\Support\ServiceProvider;

class SensitiveServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/sensitive.php', 'sensitive');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
                __DIR__ . '/../database/seeders' => database_path('seeders'),
            ], 'sensitive-migrations');

            $this->publishes([
                __DIR__ . '/../config/sensitive.php' => config_path('sensitive.php'),
            ], 'sensitive-config');
        }
    }

    /**
     * Register migration files.
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        if (config('sensitive.migration')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

}
