<?php

namespace Jundayw\LaravelSensitive\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Jundayw\LaravelSensitive\Contracts\FilterInterface;
use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;
use Jundayw\LaravelSensitive\Filter;
use Jundayw\LaravelSensitive\Sensitive as LaravelSensitive;

/**
 * @method static FilterInterface username(string $content)
 * @method static FilterInterface nickname(string $content)
 * @method static FilterInterface message(string $content)
 * @method static FilterInterface content(string $content)
 * @method static LaravelSensitive listen(int $scope, callable $listen)
 * @method static string replacement(string $content, Collection $collection)
 * @method static FilterInterface filter(string $content, string $field, int $scope = SensitiveInterface::STATUS_ALL)
 *
 * @see LaravelSensitive
 * @see SensitiveInterface
 * @see Filter
 * @see FilterInterface
 */
class Sensitive extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SensitiveInterface::class;
    }

}
