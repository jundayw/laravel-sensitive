<?php

namespace Jundayw\LaravelSensitive\Contracts;

use Illuminate\Support\Collection;

interface FilterInterface
{
    public function getRaw(): string;

    public function getContent(): string;

    public function getField(): string;

    public function getScope(): int;

    public function getCollection(): Collection;

    public function isPass(): bool;

    public function isReplace(): bool;

    public function isReview(): bool;

    public function isBlock(): bool;

    public function handle(string $raw, string $content, string $field, int $scope = SensitiveInterface::STATUS_ALL, Collection $collection): static;

    public function getEvents(?int $events = null): array;

    public function event(int $scope, string $event): static;

    public function dispatch(...$arguments): void;

    public function getValues(int $scope = SensitiveInterface::STATUS_ALL): array;

    public function getStopWords(int $scope = SensitiveInterface::STATUS_ALL): array;

}
