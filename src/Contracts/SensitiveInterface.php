<?php

namespace Jundayw\LaravelSensitive\Contracts;

use Illuminate\Support\Collection;

interface SensitiveInterface
{
    public const  STATUS_PASS    = 0b0001;
    public const  STATUS_REPLACE = 0b0010;
    public const  STATUS_REVIEW  = 0b0100;
    public const  STATUS_BLOCK   = 0b1000;
    public const  STATUS_ALL     = self::STATUS_PASS | self::STATUS_REVIEW | self::STATUS_REPLACE | self::STATUS_BLOCK;

    public function listen(int $scope, callable $listen): static;

    public function replacement(string $content, Collection $collection): string;

    public function filter(string $content, string $field, int $scope = SensitiveInterface::STATUS_ALL): FilterInterface;

}
