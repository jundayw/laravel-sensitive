<?php

namespace Jundayw\LaravelSensitive\Concerns;

use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;

trait SensitiveListenTrait
{
    protected array $listens        = [];
    protected array $defaultListens = [
        SensitiveInterface::STATUS_PASS    => [],
        SensitiveInterface::STATUS_REPLACE => [],
        SensitiveInterface::STATUS_REVIEW  => [],
        SensitiveInterface::STATUS_BLOCK   => [],
    ];

    public function getListens(?int $listen = null): array
    {
        if (count($this->listens) === 0) {
            $this->listens = $this->defaultListens;
        }
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
        foreach ($this->getListens() as $status => $listens) {
            if ($status === ($scope & $status)) {
                $this->listens[$status][] = $listen;
            }
        }

        return $this;
    }

}
