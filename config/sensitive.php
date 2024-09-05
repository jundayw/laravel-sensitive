<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Interceptor Driver
    |--------------------------------------------------------------------------
    |
    | Supported Drivers:
    | \Jundayw\LaravelSensitive\Interceptors\LocalInterceptor::class
    |
    | Default:
    | \Jundayw\LaravelSensitive\Interceptors\LocalInterceptor::class
    |
    */

    'driver' => \Jundayw\LaravelSensitive\Interceptors\LocalInterceptor::class,

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | This configuration value allows you to customize the Database.
    |
    */

    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'table'      => 'sensitive',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "apc", "array", "database", "file",
    |         "memcached", "redis", "dynamodb", "octane", "null"
    |
    */

    'cache' => [
        'driver' => env('CACHE_DRIVER'),
        'ttl'    => env('CACHE_DRIVER_TTL', 3600),
    ],

    'migration' => true,

];
