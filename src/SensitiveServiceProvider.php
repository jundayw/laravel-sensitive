<?php

namespace Jundayw\LaravelSensitive;

use Illuminate\Support\ServiceProvider;
use Jundayw\LaravelSensitive\Contracts\FilterInterface;
use Jundayw\LaravelSensitive\Contracts\InterceptorInterface;
use Jundayw\LaravelSensitive\Contracts\DatabaseInterface;
use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;
use Jundayw\LaravelSensitive\Support\DatabaseSensitive;

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

        $this->app->bind(SensitiveInterface::class, Sensitive::class);
        $this->app->bind(DatabaseInterface::class, DatabaseSensitive::class);
        $this->app->bind(InterceptorInterface::class, config('sensitive.driver',LocalInterceptor::class));
        $this->app->bind(FilterInterface::class, Filter::class);
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
                __DIR__ . '/../database/seeders'    => database_path('seeders'),
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
