<?php

namespace Jundayw\LaravelSensitive\Concerns;

use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;

trait SensitiveListenTrait
{
    protected array $listens = [
        SensitiveInterface::STATUS_PASS    => [],
        SensitiveInterface::STATUS_REPLACE => [],
        SensitiveInterface::STATUS_REVIEW  => [],
        SensitiveInterface::STATUS_BLOCK   => [],
    ];

    public function getListens(?int $listen = null): array
    {
        if (is_null($listen)) {
            return $this->listens;
        }
        if (array_key_exists($listen, $this->listens)) {
            return $this->listens[$listen];
        }
        return [];
    }

    public function listen(int $scope, callable $listen): static
    {
        $isPass    = (SensitiveInterface::STATUS_PASS & $scope) === SensitiveInterface::STATUS_PASS;
        $isReplace = (SensitiveInterface::STATUS_REPLACE & $scope) === SensitiveInterface::STATUS_REPLACE;
        $isReview  = (SensitiveInterface::STATUS_REVIEW & $scope) === SensitiveInterface::STATUS_REVIEW;
        $isBlock   = (SensitiveInterface::STATUS_BLOCK & $scope) === SensitiveInterface::STATUS_BLOCK;

        if ($isPass || $scope === SensitiveInterface::STATUS_ALL) {
            $this->listens[SensitiveInterface::STATUS_PASS][] = $listen;
        }

        if ($isReplace || $scope === SensitiveInterface::STATUS_ALL) {
            $this->listens[SensitiveInterface::STATUS_REPLACE][] = $listen;
        }

        if ($isReview || $scope === SensitiveInterface::STATUS_ALL) {
            $this->listens[SensitiveInterface::STATUS_REVIEW][] = $listen;
        }

        if ($isBlock || $scope === SensitiveInterface::STATUS_ALL) {
            $this->listens[SensitiveInterface::STATUS_BLOCK][] = $listen;
        }

        return $this;
    }

}
